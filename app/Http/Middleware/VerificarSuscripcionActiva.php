<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Consultorio;

class VerificarSuscripcionActiva
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Admin siempre puede acceder
        if ($user->roles->contains('name', 'admin')) {
            return $next($request);
        }

        // Verificar que tenga consultorio
        if (!$user->consultorio_id) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'No tienes un consultorio asignado. Contacta al administrador.');
        }

        $consultorio = $user->consultorio;

        // Verificar que el consultorio esté activo
        if (!$consultorio->activo) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'El consultorio está desactivado. Contacta al administrador.');
        }

        // Obtener suscripción activa
        $suscripcion = $consultorio->suscripcionActiva;

        // Sin suscripción
        if (!$suscripcion) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'El consultorio no tiene una suscripción activa. Por favor, renueva tu plan.');
        }

        // Verificar que la suscripción esté activa
        if (!$suscripcion->estaActiva()) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'La suscripción del consultorio ha expirado. Por favor, renueva tu plan.');
        }

        // Verificar límites del plan
        $validacion = $this->verificarLimitesPlan($consultorio, $suscripcion->plan);

        if (!$validacion['valido']) {
            // No cerrar sesión, solo mostrar advertencia
            session()->flash('warning', $validacion['mensaje']);
        }

        // Advertencia si está próximo a vencer (últimos 7 días)
        if ($suscripcion->diasRestantes() <= 7) {
            $dias = $suscripcion->diasRestantes();
            session()->flash('warning', "Tu suscripción vence en {$dias} días. Por favor, renueva pronto.");
        }

        return $next($request);
    }

    private function verificarLimitesPlan($consultorio, $plan)
    {
        $doctores = $consultorio->doctores()->count();
        $secretarias = $consultorio->secretarias()->count();
        $enfermeras = $consultorio->enfermeras()->count();

        // Verificar límite de doctores
        if ($plan->max_doctores !== null && $doctores > $plan->max_doctores) {
            return [
                'valido' => false,
                'mensaje' => "Has excedido el límite de doctores ({$doctores}/{$plan->max_doctores}). Actualiza tu plan."
            ];
        }

        // Verificar límite de secretarias
        if ($plan->max_secretarias !== null && $secretarias > $plan->max_secretarias) {
            return [
                'valido' => false,
                'mensaje' => "Has excedido el límite de secretarias ({$secretarias}/{$plan->max_secretarias}). Actualiza tu plan."
            ];
        }

        // Verificar límite de enfermeras
        if ($plan->max_enfermeras !== null && $enfermeras > $plan->max_enfermeras) {
            return [
                'valido' => false,
                'mensaje' => "Has excedido el límite de enfermeras ({$enfermeras}/{$plan->max_enfermeras}). Actualiza tu plan."
            ];
        }

        return ['valido' => true, 'mensaje' => ''];
    }
}
