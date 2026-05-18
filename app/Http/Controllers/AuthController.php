<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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

            // 5. Verificar suscripción activa
            $suscripcion = $consultorio->suscripcionActiva;

            if (!$suscripcion) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'El consultorio no tiene una suscripción activa. Contacta al administrador para renovar el plan.'
                ]);
            }

            if (!$suscripcion->estaActiva()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => "La suscripción expiró el {$suscripcion->fecha_fin->format('d/m/Y')}. Contacta al administrador para renovar."
                ]);
            }

            // 6. Verificar último pago
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

            // 7. Verificar especialidad para doctores
            if ($user->roles->contains('name', 'doctor') && !$user->especialidad_id) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'El doctor no tiene especialidad asignada. Contacta al administrador.'
                ]);
            }

            // 8. Verificar límites del plan
            $validacion = $this->verificarLimitesPlan($consultorio, $suscripcion->plan);
            if (!$validacion['valido']) {
                session()->flash('warning', $validacion['mensaje']);
            }

            // 9. Alertas de vencimiento
            $diasRestantes = $suscripcion->diasRestantes();
            if ($diasRestantes <= 7) {
                session()->flash('warning', "⚠️ La suscripción vence en {$diasRestantes} días.");
            }

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
}
