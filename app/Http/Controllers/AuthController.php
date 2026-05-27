<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Consultorio;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Especialidad;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Demasiados intentos. Intenta en 1 minuto.');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            $user = Auth::user();

            if (
                $user->es_demo &&
                now()->greaterThan(
                    $user->created_at->copy()->addMinutes(5)
                )
            ) {

                // eliminar datos demo
                $this->eliminarDemo($user);

                Auth::logout();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'La sesión demo ha expirado.'
                    ]);
            }

            // 1. Verificar si el usuario está activo
            if (!$user->activo) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta está desactivada.']);
            }

            // 2. Admin puede pasar sin más validaciones
            if ($user->roles->contains('name', 'admin')) {
                return redirect()->route('pacientes.inicio');
            }

            // 3. Verificar consultorio asignado
            if (!$user->consultorio_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta no tiene consultorio asignado. Contacta al administrador.'
                ]);
            }

            $consultorio = $user->consultorio;

            // 4. Verificar que el consultorio esté activo
            if (!$consultorio->activo) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'El consultorio está desactivado. Contacta al administrador.'
                ]);
            }

            if (!$user->es_demo) {

                // Verificar suscripción
                $suscripcion = $consultorio->suscripcionActiva;

                if (!$suscripcion) {

                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'El consultorio no tiene una suscripción activa.'
                    ]);
                }

                if (!$suscripcion->estaActiva()) {

                    Auth::logout();

                    return back()->withErrors([
                        'email' => "La suscripción expiró el {$suscripcion->fecha_fin->format('d/m/Y')}."
                    ]);
                }

                // pago
                $ultimoPago = $suscripcion->pagos()
                    ->where('estado', 'aprobado')
                    ->latest()
                    ->first();

                if (!$ultimoPago) {

                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'No se encontró registro de pago.'
                    ]);
                }

                // límites
                $validacion = $this->verificarLimitesPlan($consultorio, $suscripcion->plan);

                if (!$validacion['valido']) {
                    session()->flash('warning', $validacion['mensaje']);
                }

                // alerta vencimiento
                $diasRestantes = $suscripcion->diasRestantes();

                if ($diasRestantes <= 7) {
                    session()->flash(
                        'warning',
                        "⚠️ La suscripción vence en {$diasRestantes} días."
                    );
                }
            }

            /* 6. Verificar último pago
            if (!$user->es_demo) {

                $ultimoPago = $suscripcion->pagos()
                    ->where('estado', 'aprobado')
                    ->latest()
                    ->first();

                if (!$ultimoPago) {

                    Auth::logout();

                    return back()->withErrors([
                        'email' => 'No se encontró registro de pago para la suscripción actual. Contacta al administrador.'
                    ]);
                }
            }*/

            // 7. Verificar especialidad para doctores
            if ($user->roles->contains('name', 'doctor') && !$user->especialidad_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'El doctor no tiene especialidad asignada. Contacta al administrador.'
                ]);
            }

            /* 8. Verificar límites del plan
            $validacion = $this->verificarLimitesPlan($consultorio, $suscripcion->plan);
            if (!$validacion['valido']) {
                session()->flash('warning', $validacion['mensaje']);
            }

            // 9. Alertas de vencimiento
            $diasRestantes = $suscripcion->diasRestantes();
            if ($diasRestantes <= 7) {
                session()->flash('warning', "⚠️ La suscripción vence en {$diasRestantes} días.");
            }*/

            return redirect()->route('pacientes.inicio');
        }

        RateLimiter::hit($key, 60);
        return back()->with('error', 'Credenciales incorrectas');
    }

    private function verificarLimitesPlan($consultorio, $plan)
    {
        $doctores = $consultorio->doctores()->count();
        $secretarias = $consultorio->secretarias()->count();
        $enfermeras = $consultorio->enfermeras()->count();

        if ($plan->max_doctores !== null && $doctores > $plan->max_doctores) {
            return [
                'valido' => false,
                'mensaje' => "⚠️ Se ha excedido el límite de doctores del plan ({$doctores}/{$plan->max_doctores})."
            ];
        }

        if ($plan->max_secretarias !== null && $secretarias > $plan->max_secretarias) {
            return [
                'valido' => false,
                'mensaje' => "⚠️ Se ha excedido el límite de secretarias del plan ({$secretarias}/{$plan->max_secretarias})."
            ];
        }

        if ($plan->max_enfermeras !== null && $enfermeras > $plan->max_enfermeras) {
            return [
                'valido' => false,
                'mensaje' => "⚠️ Se ha excedido el límite de enfermeras del plan ({$enfermeras}/{$plan->max_enfermeras})."
            ];
        }

        return ['valido' => true, 'mensaje' => ''];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function crearDemo(Request $request)
    {
        $key = 'demo:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 2)) {

            $seconds = RateLimiter::availableIn($key);

            $hours = floor($seconds / 3600);

            $minutes = floor(($seconds % 3600) / 60);

            return back()->with(
                'error',
                "Solo puedes generar 2 demos cada 24 horas. "
                    . "Intenta nuevamente en {$hours}h {$minutes}m."
            );
        }

        RateLimiter::hit($key, 60 * 60 * 24);

        DB::beginTransaction();

        try {

            $request->validate([
                'especialidad_id' => 'required|exists:especialidades,id'
            ]);

            // =========================
            // CONSULTORIO TEMPORAL
            // =========================

            $consultorio = Consultorio::create([

                'nombre' => 'Demo ' . rand(1000, 9999),

                'email' => 'demo' . time() . '@doctorclick.com',

                'telefono' => '8090000000',

                'activo' => true,

            ]);

            // =========================
            // USUARIO DEMO
            // =========================

            $password = 'demo123';

            $user = User::create([

                'name' => 'Usuario Demo',

                'email' => 'demo' . time() . '@demo.com',

                'password' => bcrypt($password),

                'consultorio_id' => $consultorio->id,

                'especialidad_id' => $request->especialidad_id,

                'es_demo' => true,
            ]);

            // =========================
            // ROL
            // =========================

            $user->assignRole('doctor');

            $user->givePermissionTo([
                'ver pacientes',
                'crear pacientes',
                'editar pacientes',
                'eliminar pacientes',

                'ver citas',
                'crear citas',
                'editar citas',
                'eliminar citas',

                'ver consultas',
                'crear consultas',

                'ver antecedentes',
                'crear antecedentes',

                'ver estudios',
                'crear estudios',
                'descargar estudios',

                'crear diagnosticos',
                'crear tratamientos',
                'crear procedimientos',
                'crear signos vitales',
                'crear examen fisico',
                'crear evoluciones',

                'generar recetas',

                'ver caja',
                'abrir caja',
                'registrar pagos',
                'registrar egresos',
                'cerrar caja',
                'ver conciliacion caja',
            ]);

            // =========================
            // PLAN DEMO
            // =========================

            $plan = Plan::first();

            Suscripcion::create([

                'consultorio_id' => $consultorio->id,

                'plan_id' => $plan->id,

                'fecha_inicio' => now(),

                'fecha_fin' => now()->addMinutes(5),

                'estado' => 'activa',

                'tipo_pago' => 'mensual',

                'monto_pagado' => 0,

                'observaciones' => 'Cuenta demo gratuita',

            ]);

            DB::commit();

            Auth::loginUsingId($user->id);

            return redirect()->route('pacientes.inicio');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    private function eliminarDemo($user)
    {
        DB::beginTransaction();

        try {

            $consultorioId = $user->consultorio_id;

            // =========================
            // ELIMINAR DATOS RELACIONADOS
            // =========================

            DB::table('consultas')
                ->where('doctor_id', $user->id)
                ->delete();

            DB::table('citas')
                ->where('doctor_id', $user->id)
                ->delete();

            DB::table('pacientes')
                ->where('consultorio_id', $consultorioId)
                ->delete();

            DB::table('suscripciones')
                ->where('consultorio_id', $consultorioId)
                ->delete();

            // roles spatie
            DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->delete();

            // =========================
            // ELIMINAR USER
            // =========================

            User::where('id', $user->id)->delete();

            // =========================
            // ELIMINAR CONSULTORIO
            // =========================

            Consultorio::where('id', $consultorioId)->delete();

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error($e->getMessage());
        }
    }

    public function landing()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();

        return view('landing', compact('especialidades'));
    }
}
