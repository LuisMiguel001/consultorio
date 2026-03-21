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
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'nullable|min:13|max:13|unique:pacientes,cedula',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|string|max:10',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:9'
        ]);

        $data = $request->all();
        $data['doctor_id'] = Auth::user()->doctor_principal;

        Paciente::create($data);

        if ($request->accion == 'nuevo') {
            return redirect()->route('pacientes.create')
                ->with('success', 'Paciente creado correctamente');
        }

        return redirect()->route('pacientes.lista')
            ->with('success', 'Paciente creado correctamente');
    }

    public function create()
    {
        return view('pacientes');
    }

    public function lista(Request $request)
    {
        $doctorId = Auth::user()->doctor_principal;
        $query = Paciente::where('doctor_id', $doctorId);

        if ($request->filled('buscar')) {

            $buscar = strtolower($request->buscar);
            $buscar = preg_replace('/[^a-z0-9\s]/i', ' ', $buscar);
            $palabras = array_filter(explode(' ', $buscar));

            $query->where(function ($q) use ($palabras) {

                foreach ($palabras as $palabra) {

                    $q->where(function ($sub) use ($palabra) {

                        $sub->whereRaw("LOWER(nombre) LIKE ?", ["%{$palabra}%"])
                            ->orWhereRaw("LOWER(apellido) LIKE ?", ["%{$palabra}%"])
                            ->orWhereRaw("LOWER(CONCAT(nombre,' ',apellido)) LIKE ?", ["%{$palabra}%"])
                            ->orWhereRaw("REPLACE(cedula,'-','') LIKE ?", ["%" . str_replace('-', '', $palabra) . "%"])
                            ->orWhereRaw("REPLACE(telefono,'-','') LIKE ?", ["%" . str_replace('-', '', $palabra) . "%"])
                            ->orWhereRaw("LOWER(nss) LIKE ?", ["%{$palabra}%"]);
                    });
                }
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $pacientes = $query
            ->orderByRaw("COALESCE(updated_at, created_at, '1970-01-01') DESC")
            ->paginate(20)
            ->withQueryString();

        return view('lista_pacientes', compact('pacientes'));
    }

    public function inicio()
    {
        $doctorId = Auth::user()->doctor_principal;

        $totalPacientes = Paciente::where('doctor_id', $doctorId)->count();

        // Citas programadas para hoy
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Programada')
            ->count();

        // 1️⃣ PACIENTES ATENDIDOS HOY (Citas con estado 'Realizada')
        $atendidosHoy = Cita::whereDate('fecha', Carbon::today())
            ->where('estado_cita', 'Realizada')
            ->where('doctor_id', $doctorId)
            ->count();

        // NUEVA: 2️⃣ PACIENTES ATENDIDOS ESTA SEMANA (Lunes a Domingo)
        $inicioSemana = Carbon::now()->startOfWeek(); // Lunes
        $finSemana = Carbon::now()->endOfWeek();      // Domingo
        $atendidosSemana = Cita::whereBetween('fecha', [$inicioSemana, $finSemana])
            ->where('estado_cita', 'Realizada')
            ->where('doctor_id', $doctorId)
            ->count();
        // NUEVA: 3️⃣ PACIENTES ATENDIDOS ESTE MES
        $atendidosMes = Cita::whereMonth('fecha', Carbon::now()->month)
            ->whereYear('fecha', Carbon::now()->year)
            ->where('estado_cita', 'Realizada')
            ->where('doctor_id', $doctorId)
            ->count();

        // 4️⃣ NUEVO PROMEDIO SEMANAL REAL (Promedio de las últimas 4 semanas completas)
        $promedioSemanaReal = 0;
        $fechasSemanas = [];
        for ($i = 4; $i > 0; $i--) {
            $inicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fin = Carbon::now()->subWeeks($i)->endOfWeek();
            $fechasSemanas[] = [
                'inicio' => $inicio->copy(),
                'fin' => $fin->copy(),
            ];
        }
        $totalesSemanas = [];
        foreach ($fechasSemanas as $semana) {
            $totalesSemanas[] = Cita::whereBetween('fecha', [$semana['inicio'], $semana['fin']])
                ->where('doctor_id', $doctorId)
                ->where('estado_cita', 'Realizada')
                ->count();
        }
        if (count($totalesSemanas) > 0) {
            $promedioSemanaReal = round(array_sum($totalesSemanas) / count($totalesSemanas), 1);
        }

        // 5️⃣ GRÁFICO COMPARATIVO MENSUAL (Citas Realizadas vs Totales)
        $citasPorMesComparativo = Cita::select(
            DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
            DB::raw("COUNT(CASE WHEN estado_cita = 'Realizada' THEN 1 END) as realizadas"),
            DB::raw('COUNT(*) as totales')
        )
            ->where('doctor_id', $doctorId)
            ->whereYear('fecha', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // 6️⃣ TASA DE ASISTENCIA (%)
        $citasProgramadas = Cita::where('estado_cita', 'Programada')
            ->where('doctor_id', $doctorId)
            ->count();
        $citasRealizadas = Cita::where('estado_cita', 'Realizada')
            ->where('doctor_id', $doctorId)
            ->count();
        $totalCitasConsideradas = $citasProgramadas + $citasRealizadas; // Evitar dividir por 0
        $tasaAsistencia = $totalCitasConsideradas > 0
            ? round(($citasRealizadas / $totalCitasConsideradas) * 100, 1)
            : 0;

        // --- MÉTRICAS PARA GRÁFICOS (simplificadas) ---
        $citasPorMes = Cita::select(
            DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->where('doctor_id', $doctorId)
            ->whereYear('fecha', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // --- PRÓXIMAS CITAS (igual) ---
        $ahora = Carbon::now();
        $proximasCitas = Cita::with('paciente')
            ->where('doctor_id', $doctorId)
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($ahora) {
                $q->whereDate('fecha', '>', $ahora->toDateString())
                    ->orWhere(function ($q2) use ($ahora) {
                        $q2->whereDate('fecha', $ahora->toDateString())
                            ->whereTime('hora', '>=', $ahora->format('H:i:s'));
                    });
            })
            ->whereHas('paciente', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        $citasUltimos6Meses = $citasUltimos6Meses = Cita::where('estado_cita', 'Realizada')
            ->where('doctor_id', $doctorId)
            ->whereBetween('fecha', [
                Carbon::now()->subMonths(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ])->count();
        $promedioMensual = round($citasUltimos6Meses / 6, 1);

        return view('inicio', compact(
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
        ));
    }

    public function show(Paciente $paciente)
    {
        if ($paciente->doctor_id != Auth::user()->doctor_principal) {
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
                'contenido' => "Consulta {$consulta->tipo_consulta} - Dr. {$consulta->doctor->name}"
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
                    'contenido' => "{$med->medicamento} - {$med->dosis} ({$med->frecuencia})"
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

        $eventos = $eventos->sortByDesc('fecha')->values();

        return view('pacientes.show', compact('paciente', 'eventos'));
    }

    public function edit($id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);
        return view('editar_paciente', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'nullable|min:13|max:13|unique:pacientes,cedula,' . $id,
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:9'
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.lista', $id)
            ->with('success', 'Paciente actualizado correctamente');
    }

    public function destroy($id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);

        $paciente->delete();

        return redirect()->route('pacientes.lista')
            ->with('success', 'Paciente archivado correctamente');
    }

    public function archivados()
    {
        $doctorId = Auth::user()->doctor_principal;

        $pacientes = Paciente::onlyTrashed()
            ->where('doctor_id', $doctorId)
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('pacientes.archivados', compact('pacientes'));
    }

    public function restaurar($id)
    {
        $doctorId = Auth::user()->doctor_principal;

        $paciente = Paciente::onlyTrashed()
            ->where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        $paciente->restore();

        return redirect()->route('pacientes.lista')
            ->with('success', 'Paciente restaurado correctamente');
    }

    private function obtenerPacienteSeguro($id)
    {
        $doctorId = Auth::user()->doctor_principal;

        return Paciente::where('id', $id)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();
    }
}
