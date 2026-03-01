<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with('paciente')
            ->where('doctor_id', Auth::id())
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'asc')
            ->paginate(15);

        return view('agenda.index', compact('citas'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        return view('agenda.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'servicio_especifico' => 'required|in:Cirugía de Corazón Abierto,Cirugía Venosa con Láser,Insuficiencia Renal,Tratamiento de Várices,Termodiálisis',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracion_minutos' => 'nullable|integer|min:15',
            'prioridad' => 'nullable|in:Normal,Preferente,Urgente',
        ]);

        // Verificar disponibilidad
        $existe = Cita::where('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->where('doctor_id', Auth::id())
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'hora' => 'Ya tiene una cita programada para esa fecha y hora'
            ])->withInput();
        }

        $cita = Cita::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => Auth::id(),
            'servicio_especifico' => $request->servicio_especifico,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'duracion_minutos' => $request->duracion_minutos ?? 30,
            'notas_previas' => $request->notas_previas,
            'prioridad' => $request->prioridad ?? 'Normal',
            'estado_cita' => 'Programada',
            'recordatorio_enviado' => false,
            'requiere_ayuno' => $request->has('requiere_ayuno'),
            'estudios_previos' => $request->has('estudios_previos'),
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Procedimiento agendado correctamente para ' . $cita->paciente->nombre);
    }
}
