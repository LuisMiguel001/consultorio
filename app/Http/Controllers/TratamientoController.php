<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class TratamientoController extends Controller
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
            'medicamento' => 'required|string',
            'dosis' => 'required|string',
            'frecuencia' => 'required|string',
            'duracion' => 'required|string',
            'via_administracion' => 'required|string',
            'indicaciones' => 'nullable|string'
        ]);

        $consulta->tratamientos()->create($data);

        return back()->with('success', 'Tratamiento agregado correctamente');
    }
}
