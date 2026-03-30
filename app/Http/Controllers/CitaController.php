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
        // Marcar citas atrasadas
        Cita::where('estado_cita', 'Programada')
            ->where(function ($q) {
                $q->where('fecha', '<', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->where('fecha', now()->toDateString())
                            ->where('hora', '<', now()->format('H:i'));
                    });
            })
            ->update(['estado_cita' => 'Atrasada']);

        $doctorId = Auth::user()->doctor_principal;

        $query = Cita::with('paciente')
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
                    ->orWhereRaw("REPLACE(cedula,'-','') LIKE ?", ["%" . str_replace('-', '', $buscar) . "%"]);
            });
        }

        // 🎯 FILTRO POR ESTADO
        if ($request->filled('filtro_estado')) {
            $query->where('estado_cita', $request->filtro_estado);
        }

        // 🎯 FILTRO POR PRIORIDAD
        if ($request->filled('filtro_prioridad')) {
            $query->where('prioridad', $request->filtro_prioridad);
        }

        // 📅 FILTRO POR RANGO DE FECHAS
        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        // 📊 JERARQUÍA DE ESTADOS
        $query->orderByRaw("
        CASE
            WHEN estado_cita = 'Programada' THEN 1
            WHEN estado_cita = 'Realizada' THEN 2
            WHEN estado_cita = 'Atrasada' THEN 3
            WHEN estado_cita = 'Cancelada' THEN 4
            ELSE 5
        END
    ");

        // luego ordenar por fecha y hora
        $query->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc');

        $citas = $query->paginate(25)->withQueryString();

        return view('agenda.index', compact('citas'));
    }

    public function create()
    {
        $doctorId = Auth::user()->doctor_principal;

        $pacientes = Paciente::orderBy('nombre')
            ->where('doctor_id', $doctorId)
            ->whereNull('deleted_at')
            ->get();
        return view('agenda.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id'        => 'required|exists:pacientes,id',
            'servicio_especifico' => 'nullable|string|max:255',
            'fecha'              => 'required|date|after_or_equal:today',
            'hora'               => 'required',
            'duracion_minutos'   => 'nullable|integer|min:15',
            'prioridad'          => 'nullable|in:Normal,Preferente,Urgente',
        ]);

        $doctorId = Auth::user()->doctor_principal;

        // Verificar que el paciente pertenece al doctor
        Paciente::where('id', $request->paciente_id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        // Validar que no sea en el pasado
        $fechaHoraCita = Carbon::parse($request->fecha . ' ' . $request->hora);
        if ($fechaHoraCita->lt(Carbon::now())) {
            return back()->withErrors([
                'hora' => 'No se pueden registrar citas en horas pasadas'
            ])->withInput();
        }

        $duracion = (int) ($request->duracion_minutos ?? $cita->duracion_minutos ?? 30);
        $horaInicio = $request->hora;
        $horaFin    = $fechaHoraCita->copy()->addMinutes($duracion)->format('H:i');

        $solapamiento = Cita::where('doctor_id', $doctorId)
            ->where('fecha', $request->fecha)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereRaw("hora < ?", [$horaFin])
                    ->whereRaw("(hora::time + (duracion_minutos || ' minutes')::interval)::time > ?::time", [$horaInicio]);
            })
            ->exists();

        if ($solapamiento) {
            return back()->withErrors([
                'hora' => 'El horario se solapa con otra cita programada en ese rango de tiempo.'
            ])->withInput();
        }

        $cita = Cita::create([
            'paciente_id'         => $request->paciente_id,
            'doctor_id'           => $doctorId,
            'servicio_especifico' => $request->servicio_especifico,
            'fecha'               => $request->fecha,
            'hora'                => $request->hora,
            'duracion_minutos'    => $duracion,
            'notas_previas'       => $request->notas_previas,
            'motivo_consulta'     => $request->motivo_consulta,
            'tipo_consulta'            => $request->tipo_consulta,
            'prioridad'           => $request->prioridad ?? 'Normal',
            'estado_cita'         => 'Programada',
            'recordatorio_enviado' => false,
            'requiere_ayuno'         => $request->has('requiere_ayuno'),
            'estudios_previos'       => $request->has('estudios_previos'),
        ]);

        // ✅ Despachar recordatorio según elección del usuario
        if ($request->has('enviar_recordatorio')) {
            $horasAntes = (int) ($request->horas_recordatorio ?? 24);

            $fechaCita = Carbon::createFromFormat(
                'Y-m-d H:i',
                Carbon::parse($cita->fecha)->format('Y-m-d') . ' ' .
                    Carbon::parse($cita->hora)->format('H:i')
            );

            $horasDiff = Carbon::now()->diffInHours($fechaCita, false);

            if ($horasDiff > 1) {
                // Si la cita está más lejos que el tiempo elegido → enviar en el momento correcto
                // Si la cita está más cerca → enviar en 5 minutos
                $delay = $horasDiff > $horasAntes
                    ? $fechaCita->copy()->subHours($horasAntes)
                    : Carbon::now()->addMinutes(5);

                EnviarRecordatorioCita::dispatch($cita)->delay($delay);
            }
        }

        return redirect()->route('citas.index')
            ->with('success', 'Procedimiento agendado correctamente para ' . $cita->paciente->nombre);
    }

    public function realizar($id)
    {
        $doctorId = Auth::user()->doctor_principal;

        $cita = Cita::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $cita->estado_cita = "Realizada";
        $cita->save();

        return redirect()->back()
            ->with('success', 'Cita marcada como realizada');
    }

    public function edit($id)
    {
        $doctorId = Auth::user()->doctor_principal;

        $cita = Cita::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $pacientes = Paciente::where('doctor_id', $doctorId)
            ->orderBy('nombre')
            ->get();

        return view('agenda.edit', compact('cita', 'pacientes'));
    }

    public function update(Request $request, $id)
    {
        $doctorId = Auth::user()->doctor_principal;

        $cita = Cita::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora'        => 'required',
        ]);

        $fechaHoraCita = Carbon::parse($request->fecha . ' ' . $request->hora);
        if ($fechaHoraCita->lt(Carbon::now())) {
            return back()->withErrors([
                'hora' => 'No se pueden registrar citas en horas pasadas'
            ])->withInput();
        }

        $duracion   = (int) ($request->duracion_minutos ?? $cita->duracion_minutos ?? 30);
        $horaInicio = $request->hora;
        $horaFin    = Carbon::parse($request->fecha . ' ' . $request->hora)
            ->addMinutes($duracion)->format('H:i');

        $solapamiento = Cita::where('doctor_id', $doctorId)
            ->where('fecha', $request->fecha)
            ->where('estado_cita', 'Programada')
            ->where('id', '!=', $id)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->whereRaw("hora < ?", [$horaFin])
                    ->whereRaw("(hora::time + (duracion_minutos || ' minutes')::interval)::time > ?::time", [$horaInicio]);
            })->exists();

        if ($solapamiento) {
            return back()->withErrors([
                'hora' => 'El horario se solapa con otra cita programada en ese rango de tiempo.'
            ])->withInput();
        }

        $cita->update([
            'paciente_id'         => $request->paciente_id,
            'servicio_especifico' => $request->servicio_especifico,
            'fecha'               => $request->fecha,
            'hora'                => $request->hora,
            'duracion_minutos'    => $duracion,
            'notas_previas'       => $request->notas_previas,
            'motivo_consulta'     => $request->motivo_consulta,
            'tipo_consulta'       => $request->tipo_consulta,
            'prioridad'           => $request->prioridad ?? 'Normal',
            'requiere_ayuno'      => $request->has('requiere_ayuno'),
            'estudios_previos'    => $request->has('estudios_previos'),
            'recordatorio_enviado' => false,
            'estado_cita'         => 'Programada',
        ]);

        // Re-despachar recordatorio
        if ($request->has('enviar_recordatorio')) {
            $horasAntes = (int) ($request->horas_recordatorio ?? 24);

            // ✅ Usar $request->fecha y $request->hora directamente, no $cita->fecha
            $fechaCita = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->fecha . ' ' . $request->hora
            );

            $horasDiff = Carbon::now()->diffInHours($fechaCita, false);

            if ($horasDiff > 1) {
                $delay = $horasDiff > $horasAntes
                    ? $fechaCita->copy()->subHours($horasAntes)
                    : Carbon::now()->addMinutes(5);

                EnviarRecordatorioCita::dispatch($cita->fresh())->delay($delay);
            }
        }

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada correctamente');
    }

    public function destroy($id)
    {

        $doctorId = Auth::user()->doctor_principal;

        $cita = Cita::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $cita->estado_cita = 'Cancelada';
        $cita->save();

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita cancelada correctamente');
    }

    public function buscarPacientes(Request $request)
    {
        $buscar = $request->buscar;

        $doctorId = Auth::user()->doctor_principal;

        return Paciente::where('doctor_id', $doctorId)
            ->where(function ($q) use ($buscar) {

                $q->where(DB::raw("CONCAT(nombre,' ',apellido)"), 'ILIKE', "%$buscar%")
                    ->orWhere('nombre', 'ILIKE', "%$buscar%")
                    ->orWhere('apellido', 'ILIKE', "%$buscar%")
                    ->orWhere('cedula', 'ILIKE', "%$buscar%");
            })
            ->whereNull('deleted_at')
            ->limit(10)
            ->get();
    }
}
