<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\EnviarRecordatorioCita;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        // Marcar citas atrasadas SOLO del consultorio actual
        Cita::where('consultorio_id', $consultorioId)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) {
                $q->where('fecha', '<', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->where('fecha', now()->toDateString())
                            ->where('hora', '<', now()->format('H:i'));
                    });
            })
            ->update([
                'estado_cita' => 'Atrasada'
            ]);

        $query = Cita::with(['paciente', 'doctor'])
            ->where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->whereHas('paciente', function ($q) {
                $q->whereNull('deleted_at');
            });

        // 🔎 BUSCADOR
        if ($request->filled('buscar')) {

            $buscar = strtolower($request->buscar);

            $query->whereHas('paciente', function ($q) use ($buscar) {

                $q->whereRaw("LOWER(nombre) LIKE ?", ["%{$buscar}%"])
                    ->orWhereRaw("LOWER(apellido) LIKE ?", ["%{$buscar}%"])
                    ->orWhereRaw(
                        "REPLACE(cedula,'-','') LIKE ?",
                        ["%" . str_replace('-', '', $buscar) . "%"]
                    );
            });
        }

        // 🎯 FILTRO ESTADO
        if ($request->filled('filtro_estado')) {
            $query->where('estado_cita', $request->filtro_estado);
        }

        // 🎯 FILTRO PRIORIDAD
        if ($request->filled('filtro_prioridad')) {
            $query->where('prioridad', $request->filtro_prioridad);
        }

        // 📅 FILTRO FECHAS
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // 📊 ORDEN DE ESTADOS
        $query->orderByRaw("
            CASE
                WHEN estado_cita = 'Programada' THEN 1
                WHEN estado_cita = 'Realizada' THEN 2
                WHEN estado_cita = 'Atrasada' THEN 3
                WHEN estado_cita = 'Cancelada' THEN 4
                ELSE 5
            END
        ");

        $query->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc');

        $citas = $query->paginate(25)->withQueryString();

        return view('agenda.index', compact('citas'));
    }

    public function create()
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $pacientes = Paciente::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->whereNull('deleted_at')
            ->orderBy('nombre')
            ->get();

        return view('agenda.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $request->validate([
            'paciente_id'         => 'required|exists:pacientes,id',
            'servicio_especifico' => 'nullable|string|max:255',
            'fecha'               => 'required|date|after_or_equal:today',
            'hora'                => 'required',
            'duracion_minutos'    => 'nullable|integer|min:15',
            'prioridad'           => 'nullable|in:Normal,Preferente,Urgente',
        ]);

        // Validar paciente del consultorio y doctor
        Paciente::where('id', $request->paciente_id)
            ->where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        // Validar fecha pasada
        $fechaHoraCita = Carbon::parse(
            $request->fecha . ' ' . $request->hora
        );

        if ($fechaHoraCita->lt(now())) {

            return back()
                ->withErrors([
                    'hora' => 'No se pueden registrar citas en horas pasadas'
                ])
                ->withInput();
        }

        $duracion = (int) ($request->duracion_minutos ?? 30);

        $horaInicio = $request->hora;

        $horaFin = $fechaHoraCita
            ->copy()
            ->addMinutes($duracion)
            ->format('H:i');

        // VALIDAR SOLAPAMIENTO SOLO DEL DOCTOR
        $solapamiento = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('fecha', $request->fecha)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($horaInicio, $horaFin) {

                $q->whereRaw("hora < ?", [$horaFin])
                    ->whereRaw("
                        (hora::time +
                        (duracion_minutos || ' minutes')::interval)::time
                        > ?::time
                    ", [$horaInicio]);
            })
            ->exists();

        if ($solapamiento) {

            return back()
                ->withErrors([
                    'hora' => 'El horario se solapa con otra cita.'
                ])
                ->withInput();
        }

        $cita = Cita::create([
            'consultorio_id'      => $consultorioId,
            'paciente_id'         => $request->paciente_id,
            'doctor_id'           => $doctorId,
            'servicio_especifico' => $request->servicio_especifico,
            'fecha'               => $request->fecha,
            'hora'                => $request->hora,
            'duracion_minutos'    => $duracion,
            'notas_previas'       => $request->notas_previas,
            'motivo_consulta'     => $request->motivo_consulta,
            'tipo_consulta'       => $request->tipo_consulta,
            'prioridad'           => $request->prioridad ?? 'Normal',
            'estado_cita'         => 'Programada',
            'recordatorio_enviado' => false,
            'requiere_ayuno'      => $request->has('requiere_ayuno'),
            'estudios_previos'    => $request->has('estudios_previos'),
        ]);

        // RECORDATORIOS
        if ($request->has('enviar_recordatorio')) {

            $horasAntes = (int) ($request->horas_recordatorio ?? 24);

            $fechaCita = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->fecha . ' ' . $request->hora
            );

            $horasDiff = now()->diffInHours($fechaCita, false);

            if ($horasDiff > 1) {

                $delay = $horasDiff > $horasAntes
                    ? $fechaCita->copy()->subHours($horasAntes)
                    : now()->addMinutes(5);

                EnviarRecordatorioCita::dispatch($cita)
                    ->delay($delay);
            }
        }

        return redirect()
            ->route('citas.index')
            ->with(
                'success',
                'Cita registrada correctamente.'
            );
    }

    public function edit($id)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $cita = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('id', $id)
            ->firstOrFail();

        $pacientes = Paciente::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->whereNull('deleted_at')
            ->orderBy('nombre')
            ->get();

        return view('agenda.edit', compact('cita', 'pacientes'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $cita = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora'        => 'required',
        ]);

        $fechaHoraCita = Carbon::parse(
            $request->fecha . ' ' . $request->hora
        );

        if ($fechaHoraCita->lt(now())) {

            return back()
                ->withErrors([
                    'hora' => 'No se permiten horas pasadas'
                ])
                ->withInput();
        }

        $duracion = (int) ($request->duracion_minutos ?? 30);

        $horaInicio = $request->hora;

        $horaFin = $fechaHoraCita
            ->copy()
            ->addMinutes($duracion)
            ->format('H:i');

        $solapamiento = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('fecha', $request->fecha)
            ->where('id', '!=', $id)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($horaInicio, $horaFin) {

                $q->whereRaw("hora < ?", [$horaFin])
                    ->whereRaw("
                        (hora::time +
                        (duracion_minutos || ' minutes')::interval)::time
                        > ?::time
                    ", [$horaInicio]);
            })
            ->exists();

        if ($solapamiento) {

            return back()
                ->withErrors([
                    'hora' => 'Existe conflicto con otra cita.'
                ])
                ->withInput();
        }

        $cita->update([
            'paciente_id'          => $request->paciente_id,
            'servicio_especifico'  => $request->servicio_especifico,
            'fecha'                => $request->fecha,
            'hora'                 => $request->hora,
            'duracion_minutos'     => $duracion,
            'notas_previas'        => $request->notas_previas,
            'motivo_consulta'      => $request->motivo_consulta,
            'tipo_consulta'        => $request->tipo_consulta,
            'prioridad'            => $request->prioridad ?? 'Normal',
            'estado_cita'          => 'Programada',
            'recordatorio_enviado' => false,
            'requiere_ayuno'       => $request->has('requiere_ayuno'),
            'estudios_previos'     => $request->has('estudios_previos'),
        ]);

        return redirect()
            ->route('citas.index')
            ->with(
                'success',
                'Cita actualizada correctamente.'
            );
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $cita = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('id', $id)
            ->firstOrFail();

        $cita->update([
            'estado_cita' => 'Cancelada'
        ]);

        return redirect()
            ->route('citas.index')
            ->with(
                'success',
                'Cita cancelada correctamente.'
            );
    }

    public function realizar($id)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $cita = Cita::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('id', $id)
            ->firstOrFail();

        $cita->update([
            'estado_cita' => 'Realizada'
        ]);

        return back()
            ->with(
                'success',
                'Cita marcada como realizada.'
            );
    }

    public function buscarPacientes(Request $request)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $buscar = $request->buscar;

        return Paciente::where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where(function ($q) use ($buscar) {

                $q->where(
                    DB::raw("CONCAT(nombre,' ',apellido)"),
                    'ILIKE',
                    "%$buscar%"
                )
                    ->orWhere('nombre', 'ILIKE', "%$buscar%")
                    ->orWhere('apellido', 'ILIKE', "%$buscar%")
                    ->orWhere('cedula', 'ILIKE', "%$buscar%");
            })
            ->whereNull('deleted_at')
            ->limit(10)
            ->get();
    }
}
