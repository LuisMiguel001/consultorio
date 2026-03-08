<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'nss' => 'nullable|max:11'
        ]);

        Paciente::create($request->all());

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
        $query = Paciente::query();

        if ($request->filled('buscar')) {

            $buscar = strtolower(str_replace('-', '', $request->buscar));

            $query->where(function ($q) use ($buscar) {

                $q->whereRaw("LOWER(nombre) LIKE ?", ["%{$buscar}%"])
                    ->orWhereRaw("LOWER(apellido) LIKE ?", ["%{$buscar}%"])
                    ->orWhereRaw("LOWER(email) LIKE ?", ["%{$buscar}%"])

                    // quitar guiones de cédula
                    ->orWhereRaw("REPLACE(LOWER(cedula), '-', '') LIKE ?", ["%{$buscar}%"])

                    // quitar guiones de teléfono
                    ->orWhereRaw("REPLACE(LOWER(telefono), '-', '') LIKE ?", ["%{$buscar}%"])

                    // quitar guiones de NSS
                    ->orWhereRaw("REPLACE(LOWER(nss), '-', '') LIKE ?", ["%{$buscar}%"])

                    ->orWhereRaw("CAST(fecha_nacimiento AS TEXT) LIKE ?", ["%{$buscar}%"]);
            });
        }

        $pacientes = $query->orderBy('created_at', 'desc')->get();

        return view('lista_pacientes', compact('pacientes'));
    }

    public function inicio()
    {
        // TOTAL PACIENTES
        $totalPacientes = Paciente::count();

        // CITAS HOY
        $citasHoy = Cita::whereDate('fecha', Carbon::today())->count();

        // NUEVOS PACIENTES ESTE MES
        $nuevosPacientes = Paciente::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // CITAS POR MES (gráfico doughnut)
        $citasPorMes = Cita::select(
            DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('fecha', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // PROMEDIO SEMANAL (citas de las últimas 4 semanas)
        $citasUltimas4Semanas = Cita::whereBetween('fecha', [
            Carbon::now()->subWeeks(4)->startOfDay(),
            Carbon::now()->endOfDay()
        ])->count();
        $promedioSemanal = round($citasUltimas4Semanas / 4, 1);

        // PROMEDIO MENSUAL (citas de los últimos 6 meses)
        $citasUltimos6Meses = Cita::whereBetween('fecha', [
            Carbon::now()->subMonths(6)->startOfDay(),
            Carbon::now()->endOfDay()
        ])->count();
        $promedioMensual = round($citasUltimos6Meses / 6, 1);

        $ahora = Carbon::now();

        $proximasCitas = Cita::with('paciente')
            ->where('estado_cita', 'Programada') // solo pendientes
            ->where(function ($q) use ($ahora) {

                $q->whereDate('fecha', '>', $ahora->toDateString())

                    ->orWhere(function ($q2) use ($ahora) {
                        $q2->whereDate('fecha', $ahora->toDateString())
                            ->whereTime('hora', '>=', $ahora->format('H:i:s'));
                    });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        return view('inicio', compact(
            'totalPacientes',
            'citasHoy',
            'nuevosPacientes',
            'citasPorMes',
            'promedioSemanal',
            'promedioMensual',
            'proximasCitas'
        ));
    }

    public function show(Paciente $paciente)
    {
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

    // NUEVOS MÉTODOS

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('editar_paciente', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'nullable|min:13|max:13|unique:pacientes,cedula,' . $id,
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:11'
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.lista', $id)
            ->with('success', 'Paciente actualizado correctamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()->route('pacientes.lista')
            ->with('success', 'Paciente eliminado correctamente');
    }
}
