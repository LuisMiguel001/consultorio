<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use Illuminate\Http\Request;
use App\Models\Pago;

class ConsultorioController extends Controller
{
    public function index()
    {
        $consultorios = Consultorio::with([
            'suscripcionActiva.plan',
            'suscripcionActiva.pagos'
        ])
            ->withCount([
                'usuarios',
                'doctores',
                'secretarias',
                'enfermeras'
            ])
            ->latest()
            ->paginate(15);

        // =========================
        // ESTADÍSTICAS
        // =========================

        $consultoriosActivos = Consultorio::where(
            'activo',
            true
        )->count();

        $suscripcionesVencidas = \App\Models\Suscripcion::whereDate(
            'fecha_fin',
            '<',
            now()
        )->count();

        $ingresosMes = Pago::where(
            'estado',
            'aprobado'
        )
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $proximosVencer = \App\Models\Suscripcion::where(
            'estado',
            'activa'
        )
            ->whereBetween(
                'fecha_fin',
                [
                    now(),
                    now()->addDays(7)
                ]
            )
            ->count();

        return view(
            'consultorios.index',
            compact(
                'consultorios',
                'consultoriosActivos',
                'suscripcionesVencidas',
                'ingresosMes',
                'proximosVencer'
            )
        );
    }

    public function show(Consultorio $consultorio)
    {
        $consultorio->load([
            'usuarios.roles',
            'suscripciones.plan',
            'suscripciones.pagos',
        ]);

        $suscripcion = $consultorio
            ->suscripcionActiva;

        $plan = optional($suscripcion)->plan;

        $ultimoPago = Pago::where(
            'consultorio_id',
            $consultorio->id
        )
            ->latest('fecha_pago')
            ->first();

        $estadisticas = [
            'usuarios' => $consultorio->usuarios()->count(),
            'doctores' => $consultorio->doctores()->count(),
            'secretarias' => $consultorio->secretarias()->count(),
            'enfermeras' => $consultorio->enfermeras()->count(),
        ];

        $pagos = Pago::where(
            'consultorio_id',
            $consultorio->id
        )
            ->latest()
            ->paginate(10);

        return view(
            'consultorios.show',
            compact(
                'consultorio',
                'suscripcion',
                'plan',
                'ultimoPago',
                'estadisticas',
                'pagos'
            )
        );
    }

    public function create()
    {
        return view('consultorios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'direccion'   => 'nullable|string|max:255',
            'telefono'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:150',
            'ruc'         => 'nullable|string|max:30',
            'descripcion' => 'nullable|string',
        ]);

        $data['activo'] = true;

        Consultorio::create($data);

        return redirect()
            ->route('consultorios.index')
            ->with(
                'success',
                'Consultorio creado correctamente'
            );
    }

    public function edit(Consultorio $consultorio)
    {
        return view('consultorios.create', compact('consultorio'));
    }

    public function update(
        Request $request,
        Consultorio $consultorio
    ) {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'direccion'   => 'nullable|string|max:255',
            'telefono'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:150',
            'ruc'         => 'nullable|string|max:30',
            'descripcion' => 'nullable|string',
        ]);

        $consultorio->update($data);

        return redirect()
            ->route('consultorios.index')
            ->with(
                'success',
                'Consultorio actualizado correctamente'
            );
    }

    public function toggleActivo(
        Consultorio $consultorio
    ) {
        $consultorio->update([
            'activo' => !$consultorio->activo
        ]);

        return back()->with(
            'success',
            'Estado actualizado correctamente'
        );
    }

    public function destroy(
        Consultorio $consultorio
    ) {
        if ($consultorio->usuarios()->count() > 0) {

            return back()->with(
                'error',
                'No puedes eliminar un consultorio con usuarios asignados'
            );
        }

        $consultorio->delete();

        return redirect()
            ->route('consultorios.index')
            ->with(
                'success',
                'Consultorio eliminado correctamente'
            );
    }
}
