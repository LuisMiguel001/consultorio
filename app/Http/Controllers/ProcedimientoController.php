<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class ProcedimientoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $user = Auth::user();

        if ($consulta->paciente->consultorio_id != $user->consultorio_id) {
            abort(403);
        }

        if ($consulta->doctor_id != $user->doctor_principal) {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'required|string',
            'fecha' => 'required|date',
            'estado' => 'required|string'
        ]);

        $consulta->procedimientos()->create($data);

        return back()->with(
            'success',
            'Procedimiento registrado correctamente'
        );
    }
}
