<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\ConsultaGinecologica;
use App\Models\CuentaPaciente;
use App\Models\DetalleCuenta;
use App\Models\Servicio;

class ConsultaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREAR CONSULTA
    |--------------------------------------------------------------------------
    */

    public function create(Request $request, $id)
    {
        $user = Auth::user();

        $query = Paciente::where(
            'consultorio_id',
            $user->consultorio_id
        );

        if ($user->roles->contains('name', 'doctor')) {

            $query->where(
                'doctor_id',
                $user->doctor_principal
            );
        }

        $paciente = $query->findOrFail($id);

        $cita_id = $request->cita;

        /*
        |--------------------------------------------------------------------------
        | SERVICIOS
        |--------------------------------------------------------------------------
        */

        $servicios = Servicio::where(
            'consultorio_id',
            $user->consultorio_id
        )
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view(
            'consultas.create',
            compact(
                'paciente',
                'cita_id',
                'servicios'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VER CONSULTA
    |--------------------------------------------------------------------------
    */

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
            'ginecologia',
            'cuenta.detalles.servicio'
        ]);

        return view(
            'consultas.show',
            compact('consulta')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR CONSULTA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = Auth::user();

        $consultorio = $user->consultorio;

        $validacion = $consultorio
            ->puedeRealizarAccion('crear_consulta');

        $doctorId = $user->doctor_principal;

        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN PLAN
        |--------------------------------------------------------------------------
        */

        if (!$validacion['puede']) {

            return back()->withErrors([
                'error' => $validacion['mensaje']
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PACIENTE
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | CREAR CONSULTA
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | GINECOLOGÍA
        |--------------------------------------------------------------------------
        */

        if (
            $user->especialidad &&
            $user->especialidad->slug === 'ginecologia'
        ) {

            ConsultaGinecologica::create([
                'consulta_id'                => $consulta->id,
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

        /*
        |--------------------------------------------------------------------------
        | CREAR CUENTA DEL PACIENTE
        |--------------------------------------------------------------------------
        */

        $total = 0;

        $cuenta = CuentaPaciente::create([
            'consultorio_id' => $consultorio->id,
            'paciente_id'    => $paciente->id,
            'consulta_id'    => $consulta->id,
            'total'          => 0,
            'estado'         => 'pendiente'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SERVICIOS
        |--------------------------------------------------------------------------
        */

        if ($request->has('servicios')) {

            foreach ($request->servicios as $servicioId) {

                $servicio = Servicio::where(
                    'consultorio_id',
                    $consultorio->id
                )
                    ->where('activo', 1)
                    ->find($servicioId);

                if (!$servicio) {
                    continue;
                }

                $subtotal = $servicio->precio;

                DetalleCuenta::create([
                    'cuenta_paciente_id' => $cuenta->id,
                    'servicio_id'        => $servicio->id,
                    'precio'             => $servicio->precio,
                    'cantidad'           => 1,
                    'subtotal'           => $subtotal
                ]);

                $total += $subtotal;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR TOTAL
        |--------------------------------------------------------------------------
        */

        $cuenta->update([
            'total' => $total
        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR CITA
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | USO PLAN
        |--------------------------------------------------------------------------
        */

        $consultorio->incrementarUso('consulta');

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with(
                'success',
                'Consulta registrada correctamente.'
            );
    }
}
