<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    /**
     * Aplica el filtro correcto según el rol:
     * - Doctor: solo sus propios pacientes (doctor_id)
     * - Admin/otros: todos los pacientes del consultorio (consultorio_id directo)
     */
    private function filtrarPorAlcance($query, $user)
    {
        $query->where('consultorio_id', $user->consultorio_id);

        if ($user->roles->contains('name', 'doctor')) {
            $query->where('doctor_id', $user->doctor_principal);
        }

        return $query;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $consultorio = $user->consultorio;

        $validacion = $consultorio->puedeRealizarAccion('crear_paciente');

        if (!$validacion['puede']) {
            return back()->withErrors(['error' => $validacion['mensaje']]);
        }

        // Si es doctor, el paciente es suyo. Si es admin, debe venir del form (dropdown de doctores del consultorio)
        if ($user->roles->contains('name', 'doctor')) {
            $doctorId = $user->doctor_principal;
        } else {
            $doctorId = $request->doctor_id;
        }

        $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'cedula'            => 'nullable|min:13|max:13|unique:pacientes,cedula',
            'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required|string|max:10',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|max:20',
            'nss'               => 'nullable|max:9',
            'doctor_id'         => $user->roles->contains('name', 'doctor')
                                        ? 'nullable'
                                        : 'required|exists:users,id',
        ]);

        // Seguridad: el doctor elegido (o el propio) debe pertenecer al consultorio del usuario
        $doctorValido = User::where('id', $doctorId)
            ->where('consultorio_id', $user->consultorio_id)
            ->exists();

        if (!$doctorValido) {
            return back()->withErrors(['error' => 'Doctor inválido para este consultorio.']);
        }

        // Nunca confiar en lo que venga del form para estos dos campos:
        // el consultorio SIEMPRE es el del usuario autenticado, no algo que el cliente pueda mandar.
        $data = $request->except(['doctor_id', 'consultorio_id']);
        $data['doctor_id'] = $doctorId;
        $data['consultorio_id'] = $user->consultorio_id;

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

        $query = Paciente::query();
        $this->filtrarPorAlcance($query, $user);

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
        $user = Auth::user();
        $esDoctor = $user->roles->contains('name', 'doctor');
        $doctorId = $user->doctor_principal;
        $consultorioId = $user->consultorio_id;

        $scopePaciente = function ($query) use ($esDoctor, $doctorId, $consultorioId) {
            $query->where('consultorio_id', $consultorioId);
            if ($esDoctor) {
                $query->where('doctor_id', $doctorId);
            }
            return $query;
        };

        $scopeCita = function ($query) use ($esDoctor, $doctorId, $consultorioId) {
            $query->where('consultorio_id', $consultorioId);
            if ($esDoctor) {
                $query->where('doctor_id', $doctorId);
            }
            return $query;
        };

        $totalPacientes = $scopePaciente(Paciente::query())->count();

        $citasHoy = $scopeCita(Cita::query())
            ->whereDate('fecha', Carbon::today())
            ->where('estado_cita', 'Programada')
            ->count();

        $atendidosHoy = $scopeCita(Cita::query())
            ->whereDate('fecha', Carbon::today())
            ->where('estado_cita', 'Realizada')
            ->count();

        $inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = Carbon::now()->endOfWeek();

        $atendidosSemana = $scopeCita(Cita::query())
            ->whereBetween('fecha', [$inicioSemana, $finSemana])
            ->where('estado_cita', 'Realizada')
            ->count();

        $atendidosMes = $scopeCita(Cita::query())
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->where('estado_cita', 'Realizada')
            ->count();

        $promedioSemanaReal = 0;
        $fechasSemanas = [];

        for ($i = 4; $i > 0; $i--) {
            $inicio = Carbon::now()->subWeeks($i)->startOfWeek();
            $fin = Carbon::now()->subWeeks($i)->endOfWeek();
            $fechasSemanas[] = ['inicio' => $inicio, 'fin' => $fin];
        }

        $totalesSemanas = [];

        foreach ($fechasSemanas as $semana) {
            $totalesSemanas[] = $scopeCita(Cita::query())
                ->whereBetween('fecha', [$semana['inicio'], $semana['fin']])
                ->where('estado_cita', 'Realizada')
                ->count();
        }

        if (count($totalesSemanas) > 0) {
            $promedioSemanaReal = round(array_sum($totalesSemanas) / count($totalesSemanas), 1);
        }

        $citasPorMesComparativo = $scopeCita(
            Cita::select(
                DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
                DB::raw("COUNT(CASE WHEN estado_cita = 'Realizada' THEN 1 END) as realizadas"),
                DB::raw('COUNT(*) as totales')
            )
        )
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $citasProgramadas = $scopeCita(Cita::query())
            ->where('estado_cita', 'Programada')
            ->count();

        $citasRealizadas = $scopeCita(Cita::query())
            ->where('estado_cita', 'Realizada')
            ->count();

        $totalCitasConsideradas = $citasProgramadas + $citasRealizadas;

        $tasaAsistencia = $totalCitasConsideradas > 0
            ? round(($citasRealizadas / $totalCitasConsideradas) * 100, 1)
            : 0;

        $citasPorMes = $scopeCita(
            Cita::select(
                DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
                DB::raw('COUNT(*) as total')
            )
        )
            ->whereYear('fecha', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $ahora = Carbon::now();

        $proximasCitas = $scopeCita(Cita::with('paciente'))
            ->where('estado_cita', 'Programada')
            ->where(function ($q) use ($ahora) {
                $q->whereDate('fecha', '>', $ahora->toDateString())
                    ->orWhere(function ($q2) use ($ahora) {
                        $q2->whereDate('fecha', $ahora->toDateString())
                            ->whereTime('hora', '>=', $ahora->format('H:i:s'));
                    });
            })
            ->whereHas('paciente', fn ($q) => $q->whereNull('deleted_at'))
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(6)
            ->get();

        $citasUltimos6Meses = $scopeCita(Cita::query())
            ->where('estado_cita', 'Realizada')
            ->whereBetween('fecha', [
                now()->subMonths(6)->startOfDay(),
                now()->endOfDay()
            ])
            ->count();

        $promedioMensual = round($citasUltimos6Meses / 6, 1);

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

        // SEGURIDAD SAAS: filtro directo, ya no vía doctor
        if ($paciente->consultorio_id != $user->consultorio_id) {
            abort(404);
        }

        // SEGURIDAD MÉDICA
        if (
            $user->roles->contains('name', 'doctor') &&
            $paciente->doctor_id != $user->doctor_principal
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
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'cedula'            => 'nullable|min:13|max:13|unique:pacientes,cedula,' . $id,
            'fecha_nacimiento'  => 'required|date',
            'sexo'              => 'required',
            'email'             => 'nullable|email',
            'telefono'          => 'nullable|max:20',
            'nss'               => 'nullable|max:9'
        ]);

        // No dejar que el form pise doctor_id/consultorio_id sin pasar por validación
        $paciente->update($request->except(['doctor_id', 'consultorio_id']));

        return redirect()->route('pacientes.lista')->with('success', 'Paciente actualizado correctamente');
    }

    public function destroy($id)
    {
        $paciente = $this->obtenerPacienteSeguro($id);
        $paciente->delete();

        return redirect()->route('pacientes.lista')->with('success', 'Paciente archivado correctamente');
    }

    public function archivados()
    {
        $user = Auth::user();

        $query = Paciente::onlyTrashed();
        $this->filtrarPorAlcance($query, $user);

        $pacientes = $query
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('pacientes.archivados', compact('pacientes'));
    }

    public function restaurar($id)
    {
        $user = Auth::user();

        $paciente = Paciente::onlyTrashed()
            ->where('id', $id)
            ->where('consultorio_id', $user->consultorio_id)
            ->firstOrFail();

        $paciente->restore();

        return redirect()->route('pacientes.lista')->with('success', 'Paciente restaurado correctamente');
    }

    private function obtenerPacienteSeguro($id)
    {
        $user = Auth::user();

        $query = Paciente::where('id', $id);
        $this->filtrarPorAlcance($query, $user);

        return $query->firstOrFail();
    }
}
