<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class DiagnosticoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $user = Auth::user();

        if (
            $consulta->paciente->consultorio_id !=
            $user->consultorio_id
        ) {
            abort(404);
        }

        if (
            $user->roles->contains('name', 'doctor') &&
            $consulta->doctor_id !=
                $user->doctor_principal
        ) {
            abort(404);
        }

        $request->validate([
            'diagnostico' => 'required|string',
            'tipo' => 'required|string',
            'codigo_cie10' => 'nullable|string'
        ]);

        $consulta->diagnosticos()->create([
            'diagnostico' => $request->diagnostico,
            'tipo' => $request->tipo,
            'codigo_cie10' => $request->codigo_cie10,
        ]);

        return back()->with(
            'success',
            'Diagnóstico agregado correctamente'
        );
    }
}
