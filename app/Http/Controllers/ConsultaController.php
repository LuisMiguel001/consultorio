<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;

class ConsultaController extends Controller
{
    public function create(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $cita_id = $request->cita;

        return view('consultas.create', compact('paciente', 'cita_id'));
    }

    public function show(Consulta $consulta)
    {
        $consulta->load([
            'estudios',
            // después agregaremos:
            // 'diagnosticos',
            // 'tratamientos',
            // 'procedimientos',
            // 'signosVitales'
        ]);

        return view('consultas.show', compact('consulta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_consulta' => 'required|date',
            'tipo_consulta' => 'required'
        ]);

        $consulta = Consulta::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => Auth::id(),
            'fecha_consulta' => $request->fecha_consulta,
            'tipo_consulta' => $request->tipo_consulta,
            'motivo_consulta' => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,
            'plan' => $request->plan,
            'observaciones' => $request->observaciones,
        ]);

        $cita = Cita::where('paciente_id', $request->paciente_id)
            ->where('estado_cita', 'Programada')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        if ($cita) {
            $cita->update([
                'estado_cita' => 'Realizada'
            ]);
        }

        return redirect()->route('pacientes.show', $request->paciente_id)
            ->with('success', 'Consulta registrada y cita marcada como realizada');
    }
}
