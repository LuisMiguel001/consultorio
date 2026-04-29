<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\ConsultaGinecologica;

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
        $user = Auth::user();

        $consulta = Consulta::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id' => $user->id,
            'fecha_consulta' => $request->fecha_consulta,
            'tipo_consulta' => $request->tipo_consulta,
            'motivo_consulta' => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,
            'plan' => $request->plan,
            'observaciones' => $request->observaciones,
        ]);

        if ($user->especialidad->slug === 'ginecologia') {

            ConsultaGinecologica::create([
                'consulta_id' => $consulta->id,
                'fecha_ultima_menstruacion' => $request->fum,
                'ciclo_menstrual' => $request->ciclo,
                'gestas' => $request->gestas,
                'partos' => $request->partos,
                'abortos' => $request->abortos,
                'cesareas' => $request->cesareas,
                'embarazo_actual' => $request->embarazo,
                'semanas_gestacion' => $request->semanas,
                'metodo_anticonceptivo' => $request->metodo,
                'vida_sexual' => $request->vida_sexual,
                'examen_pelvico' => $request->examen_pelvico,
                'mamas' => $request->mamas,
            ]);
        }

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
