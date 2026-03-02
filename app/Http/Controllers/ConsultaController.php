<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;

class ConsultaController extends Controller
{
    public function create($paciente_id)
    {
        $paciente = Paciente::findOrFail($paciente_id);

        return view('consultas.create', compact('paciente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_consulta' => 'required|date',
            'tipo_consulta' => 'required'
        ]);

        Consulta::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => Auth::id(), // 🔥 importante
            'fecha_consulta' => $request->fecha_consulta,
            'tipo_consulta' => $request->tipo_consulta,
            'motivo_consulta' => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,
            'plan' => $request->plan,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $request->paciente_id)
            ->with('success', 'Consulta registrada correctamente');
    }
}
