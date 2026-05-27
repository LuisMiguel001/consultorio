<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Consultorio;

class DemoExpiradoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (
            $user &&
            $user->es_demo &&
            now()->greaterThan(
                $user->created_at->addMinutes(5)
            )
        ) {

            $consultorioId = $user->consultorio_id;

            DB::beginTransaction();

            try {

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

            Auth::logout();

            return redirect('/')
                ->with(
                    'error',
                    'Tu demo ha expirado y los datos fueron eliminados.'
                );
        }

        return $next($request);
    }
}
