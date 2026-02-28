<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|unique:pacientes|max:20',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:15'
        ]);

        Paciente::create($request->all());

        return redirect()->back()->with('success', 'Paciente registrado correctamente');
    }
}
