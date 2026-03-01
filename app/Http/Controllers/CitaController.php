<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with('paciente')->orderBy('fecha')->get();
        return view('agenda.index', compact('citas'));
    }

    public function create()
    {
        $pacientes = Paciente::all();
        return view('agenda.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'duracion_minutos' => 'required|integer|min:1',
            'tipo_consulta' => 'required'
        ]);

        $fechaHora = strtotime($request->fecha . ' ' . $request->hora);

        if ($fechaHora < time()) {
            return back()->withErrors([
                'fecha' => 'No puede agendar citas en el pasado'
            ]);
        }

        Cita::create($request->all());

        return redirect()->route('agenda.citas.index')
            ->with('success', 'Cita agendada correctamente');
    }
}
