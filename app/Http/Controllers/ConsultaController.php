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
        $user = Auth::user();

        $query = Paciente::where(
            'consultorio_id',
            $user->consultorio_id
        );

        if (
            $user->roles->contains('name', 'doctor')
        ) {
            $query->where(
                'doctor_id',
                $user->doctor_principal
            );
        }

        $paciente = $query->findOrFail($id);

        $cita_id = $request->cita;

        return view(
            'consultas.create',
            compact('paciente', 'cita_id')
        );
    }

    public function show(Consulta $consulta)
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

        $consulta->load([
            'paciente',
            'doctor',
            'estudios',
            'diagnosticos',
            'tratamientos',
            'procedimientos',
            'signoVital',
            'examenFisico',
            'evoluciones',
            'ginecologia'
        ]);

        return view(
            'consultas.show',
            compact('consulta')
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;

        $pacienteQuery = Paciente::where(
            'consultorio_id',
            $user->consultorio_id
        );

        if (
            $user->roles->contains('name', 'doctor')
        ) {
            $pacienteQuery->where(
                'doctor_id',
                $doctorId
            );
        }

        $paciente = $pacienteQuery
            ->where('id', $request->paciente_id)
            ->firstOrFail();

        // =========================
        // CREAR CONSULTA
        // =========================

        $consulta = Consulta::create([
            'paciente_id'       => $paciente->id,
            'doctor_id'         => $doctorId,
            'fecha_consulta'    => $request->fecha_consulta,
            'tipo_consulta'     => $request->tipo_consulta,
            'motivo_consulta'   => $request->motivo_consulta,
            'enfermedad_actual' => $request->enfermedad_actual,
            'plan'              => $request->plan,
            'observaciones'     => $request->observaciones,
        ]);

        if (
            $user->especialidad &&
            $user->especialidad->slug === 'ginecologia'
        ) {

            ConsultaGinecologica::create([
                'consulta_id'               => $consulta->id,
                'fecha_ultima_menstruacion' => $request->fum,
                'ciclo_menstrual'           => $request->ciclo,
                'gestas'                    => $request->gestas,
                'partos'                    => $request->partos,
                'abortos'                   => $request->abortos,
                'cesareas'                  => $request->cesareas,
                'embarazo_actual'           => $request->embarazo,
                'semanas_gestacion'         => $request->semanas,
                'metodo_anticonceptivo'     => $request->metodo,
                'vida_sexual'               => $request->vida_sexual,
                'examen_pelvico'            => $request->examen_pelvico,
                'mamas'                     => $request->mamas,
            ]);
        }

        $cita = Cita::where(
            'consultorio_id',
            $user->consultorio_id
        )
            ->where(
                'doctor_id',
                $doctorId
            )
            ->where(
                'paciente_id',
                $paciente->id
            )
            ->where(
                'estado_cita',
                'Programada'
            )
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        if ($cita) {

            $cita->update([
                'estado_cita' => 'Realizada'
            ]);
        }

        return redirect()
            ->route(
                'pacientes.show',
                $paciente->id
            )
            ->with(
                'success',
                'Consulta registrada y cita marcada como realizada'
            );
    }
}
