<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $consultorio = $user->consultorio;

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $validacion = $consultorio->puedeRealizarAccion('crear_paciente');

        if (!$validacion['puede']) {
            return back()->withErrors(['error' => $validacion['mensaje']]);
        }

        $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'cedula'            => 'nullable|min:13|max:13|unique:pacientes,cedula',
            'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required|string|max:10',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|max:20',
            'nss'               => 'nullable|max:9'
        ]);

        $data = $request->all();

        $data['doctor_id'] = $doctorId;
        $data['consultorio_id'] = $consultorioId;

        Paciente::create($data);

        if ($request->accion == 'nuevo') {

            return redirect()->route('pacientes.create')->with('success', 'Paciente creado correctamente');
        }

        $consultorio->incrementarUso('paciente');

        return redirect()->route('pacientes.lista')->with('success', 'Paciente creado correctamente');
    }

    public function create()
    {
        return view('pacientes');
    }

    public function lista(Request $request)
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $query = Paciente::where(
            'consultorio_id',
            $consultorioId
        );

        // SOLO DOCTORES ven sus pacientes
        if ($user->roles->contains('name', 'doctor')) {
            $query->where('doctor_id', $doctorId);
        }

        if ($request->filled('buscar')) {

            $buscar = strtolower($request->buscar);

            $buscar = preg_replace(
                '/[^a-z0-9\s]/i',
                ' ',
                $buscar
            );

            $palabras = array_filter(
                explode(' ', $buscar)
            );

            $query->where(function ($q) use ($palabras) {

                foreach ($palabras as $palabra) {

                    $q->where(function ($sub) use ($palabra) {

                        $sub->whereRaw(
                            "LOWER(nombre) LIKE ?",
                            ["%{$palabra}%"]
                        )
                            ->orWhereRaw(
                                "LOWER(apellido) LIKE ?",
                                ["%{$palabra}%"]
                            )
                            ->orWhereRaw(
                                "LOWER(CONCAT(nombre,' ',apellido)) LIKE ?",
                                ["%{$palabra}%"]
                            )
                            ->orWhereRaw(
                                "REPLACE(cedula,'-','') LIKE ?",
                                ["%" . str_replace('-', '', $palabra) . "%"]
                            )
                            ->orWhereRaw(
                                "REPLACE(telefono,'-','') LIKE ?",
                                ["%" . str_replace('-', '', $palabra) . "%"]
                            )
                            ->orWhereRaw(
                                "LOWER(nss) LIKE ?",
                                ["%{$palabra}%"]
                            );
                    });
                }
            });
        }

        // 📅 FILTRO FECHAS
        if ($request->filled('fecha_desde')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->fecha_desde
            );
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->fecha_hasta
            );
        }

        $pacientes = $query
            ->orderByRaw("
                COALESCE(
                    updated_at,
                    created_at,
                    '1970-01-01'
                ) DESC
            ")
            ->paginate(20)
            ->withQueryString();

        return view(
            'lista_pacientes',
            compact('pacientes')
        );
    }

    public function inicio()
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        // =========================
        // TOTAL PACIENTES
        // =========================

        $totalPacientes = Paciente::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->count();

        // =========================
        // CITAS HOY
        // =========================

        $citasHoy = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->whereDate('fecha', Carbon::today())
            ->where('estado_cita', 'Programada')
            ->count();

        // =========================
        // ATENDIDOS HOY
        // =========================

        $atendidosHoy = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->whereDate('fecha', Carbon::today())
            ->where('estado_cita', 'Realizada')
            ->count();

        // =========================
        // SEMANA
        // =========================

        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();

        $atendidosSemana = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->whereBetween('fecha', [
                $inicioSemana,
                $finSemana
            ])
            ->where('estado_cita', 'Realizada')
            ->count();

        // =========================
        // MES
        // =========================

        $atendidosMes = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->where('estado_cita', 'Realizada')
            ->count();

        // =========================
        // PROMEDIO SEMANAL
        // =========================

        $promedioSemanaReal = 0;

        $fechasSemanas = [];

        for ($i = 4; $i > 0; $i--) {

            $inicio = Carbon::now()
                ->subWeeks($i)
                ->startOfWeek();

            $fin = Carbon::now()
                ->subWeeks($i)
                ->endOfWeek();

            $fechasSemanas[] = [
                'inicio' => $inicio,
                'fin' => $fin,
            ];
        }

        $totalesSemanas = [];

        foreach ($fechasSemanas as $semana) {

            $totalesSemanas[] = Cita::where(
                'consultorio_id',
                $consultorioId
            )
                ->where('doctor_id', $doctorId)
                ->whereBetween('fecha', [
                    $semana['inicio'],
                    $semana['fin']
                ])
                ->where('estado_cita', 'Realizada')
                ->count();
        }

        if (count($totalesSemanas) > 0) {

            $promedioSemanaReal = round(
                array_sum($totalesSemanas) /
                    count($totalesSemanas),
                1
            );
        }

        // =========================
        // GRÁFICO COMPARATIVO
        // =========================

        $citasPorMesComparativo = Cita::select(
            DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
            DB::raw("
                COUNT(
                    CASE
                        WHEN estado_cita = 'Realizada'
                        THEN 1
                    END
                ) as realizadas
            "),
            DB::raw('COUNT(*) as totales')
        )
            ->where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // =========================
        // TASA ASISTENCIA
        // =========================

        $citasProgramadas = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Programada')
            ->count();

        $citasRealizadas = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Realizada')
            ->count();

        $totalCitasConsideradas =
            $citasProgramadas +
            $citasRealizadas;

        $tasaAsistencia =
            $totalCitasConsideradas > 0
            ? round(
                ($citasRealizadas /
                    $totalCitasConsideradas) * 100,
                1
            )
            : 0;

        // =========================
        // CITAS POR MES
        // =========================

        $citasPorMes = Cita::select(
            DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // =========================
        // PRÓXIMAS CITAS
        // =========================

        $ahora = Carbon::now();

        $proximasCitas = Cita::with('paciente')
            ->where('consultorio_id', $consultorioId)
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($ahora) {

                $q->whereDate(
                    'fecha',
                    '>',
                    $ahora->toDateString()
                )
                    ->orWhere(function ($q2) use ($ahora) {

                        $q2->whereDate(
                            'fecha',
                            $ahora->toDateString()
                        )
                            ->whereTime(
                                'hora',
                                '>=',
                                $ahora->format('H:i:s')
                            );
                    });
            })
            ->whereHas('paciente', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        // =========================
        // PROMEDIO MENSUAL
        // =========================

        $citasUltimos6Meses = Cita::where(
            'consultorio_id',
            $consultorioId
        )
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Realizada')
            ->whereBetween('fecha', [
                now()->subMonths(6)->startOfDay(),
                now()->endOfDay()
            ])
            ->count();

        $promedioMensual = round(
            $citasUltimos6Meses / 6,
            1
        );

        return view(
            'inicio',
            compact(
                'totalPacientes',
                'atendidosHoy',
                'atendidosSemana',
                'atendidosMes',
                'citasPorMes',
                'citasHoy',
                'promedioMensual',
                'promedioSemanaReal',
                'proximasCitas',
                'citasPorMesComparativo',
                'tasaAsistencia'
            )
        );
    }

    public function show(Paciente $paciente)
    {
        $user = Auth::user();

        // SEGURIDAD SAAS
        if (
            $paciente->consultorio_id !=
            $user->consultorio_id
        ) {
            abort(404);
        }

        // SEGURIDAD MÉDICA
        if (
            $user->roles->contains('name', 'doctor') &&
            $paciente->doctor_id !=
            $user->doctor_principal
        ) {
            abort(404);
        }

        $paciente->load([
            'consultas.doctor',
            'consultas.diagnosticos',
            'consultas.tratamientos',
            'consultas.procedimientos',
            'consultas.estudios',
            'consultas.evoluciones',
            'antecedentes.usuario'
        ]);

        $eventos = collect();

        foreach ($paciente->consultas as $consulta) {

            $eventos->push([
                'tipo' => 'Consulta',
                'fecha' => $consulta->created_at,
                'contenido' =>
                "Consulta {$consulta->tipo_consulta} - Dr. {$consulta->doctor->name}"
            ]);

            foreach ($consulta->diagnosticos as $diag) {

                $eventos->push([
                    'tipo' => 'Diagnóstico',
                    'fecha' => $diag->created_at,
                    'contenido' => $diag->descripcion
                ]);
            }

            foreach ($consulta->tratamientos as $med) {

                $eventos->push([
                    'tipo' => 'Medicamento',
                    'fecha' => $med->created_at,
                    'contenido' =>
                    "{$med->medicamento} - {$med->dosis} ({$med->frecuencia})"
                ]);
            }

            foreach ($consulta->procedimientos as $proc) {

                $eventos->push([
                    'tipo' => 'Procedimiento',
                    'fecha' => $proc->created_at,
                    'contenido' => $proc->descripcion
                ]);
            }

            foreach ($consulta->estudios as $est) {

                $eventos->push([
                    'tipo' => 'Estudio',
                    'fecha' => $est->created_at,
                    'contenido' => $est->nombre
                ]);
            }

            foreach ($consulta->evoluciones as $evo) {

                $eventos->push([
                    'tipo' => 'Evolución',
                    'fecha' => $evo->created_at,
                    'contenido' => $evo->descripcion
                ]);
            }
        }

        $eventos = $eventos
            ->sortByDesc('fecha')
            ->values();

        return view(
            'pacientes.show',
            compact('paciente', 'eventos')
        );
    }

    public function edit($id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);

        return view(
            'editar_paciente',
            compact('paciente')
        );
    }

    public function update(Request $request, $id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);

        $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'cedula'            => 'nullable|min:13|max:13|unique:pacientes,cedula,' . $id,
            'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|max:20',
            'nss'               => 'nullable|max:9'
        ]);

        $paciente->update($request->all());

        return redirect()
            ->route('pacientes.lista')
            ->with(
                'success',
                'Paciente actualizado correctamente'
            );
    }

    public function destroy($id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);

        $paciente->delete();

        return redirect()
            ->route('pacientes.lista')
            ->with(
                'success',
                'Paciente archivado correctamente'
            );
    }

    public function archivados()
    {
        $user = Auth::user();

        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $query = Paciente::onlyTrashed()
            ->where('consultorio_id', $consultorioId);

        if ($user->roles->contains('name', 'doctor')) {
            $query->where('doctor_id', $doctorId);
        }

        $pacientes = $query
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view(
            'pacientes.archivados',
            compact('pacientes')
        );
    }

    public function restaurar($id)
    {
        $paciente = Paciente::onlyTrashed()
            ->where('id', $id)
            ->where(
                'consultorio_id',
                Auth::user()->consultorio_id
            )
            ->firstOrFail();

        $paciente->restore();

        return redirect()
            ->route('pacientes.lista')
            ->with(
                'success',
                'Paciente restaurado correctamente'
            );
    }

    private function obtenerPacienteSeguro($id)
    {
        $user = Auth::user();

        $query = Paciente::where('id', $id)
            ->where(
                'consultorio_id',
                $user->consultorio_id
            );

        if ($user->roles->contains('name', 'doctor')) {

            $query->where(
                'doctor_id',
                $user->doctor_principal
            );
        }

        return $query->firstOrFail();
    }
}
