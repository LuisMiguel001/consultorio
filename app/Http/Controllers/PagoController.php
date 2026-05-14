<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with([
            'consultorio',
            'plan',
            'suscripcion'
        ]);

        // FILTRO POR ESTADO
        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        // FILTRO POR CONSULTORIO
        if ($request->consultorio) {
            $query->whereHas('consultorio', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->consultorio . '%');
            });
        }

        // FILTRO POR FECHA
        if ($request->desde) {
            $query->whereDate('fecha_pago', '>=', $request->desde);
        }

        if ($request->hasta) {
            $query->whereDate('fecha_pago', '<=', $request->hasta);
        }

        $pagos = $query
            ->latest()
            ->paginate(20);

        // ESTADÍSTICAS
        $estadisticas = [
            'total' => Pago::count(),

            'pendientes' => Pago::where(
                'estado',
                'pendiente'
            )->count(),

            'aprobados' => Pago::where(
                'estado',
                'aprobado'
            )->count(),

            'rechazados' => Pago::where(
                'estado',
                'rechazado'
            )->count(),

            'ingresosMes' => Pago::where(
                'estado',
                'aprobado'
            )
                ->whereMonth('fecha_pago', now()->month)
                ->sum('monto'),
        ];

        return view(
            'pagos.index',
            compact(
                'pagos',
                'estadisticas'
            )
        );
    }

    public function create()
    {
        $suscripciones = Suscripcion::with(
            'consultorio',
            'plan'
        )->get();

        return view(
            'pagos.create',
            compact('suscripciones')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'suscripcion_id' => 'required',
            'metodo_pago' => 'required',
            'monto' => 'required|numeric',
            'comprobante' => 'nullable|image|max:5000',
        ]);

        $comprobante = null;

        if ($request->hasFile('comprobante')) {

            $comprobante = $request
                ->file('comprobante')
                ->store('comprobantes', 'public');
        }

        $suscripcion = Suscripcion::with(
            'consultorio',
            'plan'
        )->findOrFail($request->suscripcion_id);

        Pago::create([
            'suscripcion_id' => $suscripcion->id,
            'consultorio_id' => $suscripcion->consultorio_id,
            'plan_id' => $suscripcion->plan_id,
            'monto' => $request->monto,
            'estado' => 'pendiente',
            'metodo_pago' => $request->metodo_pago,
            'referencia' => $request->referencia,
            'notas' => $request->notas,
            'comprobante' => $comprobante,
            'fecha_pago' => now(),
        ]);

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago registrado');
    }

    public function aprobar(Pago $pago)
    {
        if ($pago->estado === 'aprobado') {

            return back()->with(
                'error',
                'Este pago ya fue aprobado'
            );
        }

        $pago->update([
            'estado' => 'aprobado',
            'aprobado_por' => Auth::id(),
        ]);

        $suscripcion = $pago->suscripcion;

        $base = $suscripcion->fecha_fin > now()
            ? $suscripcion->fecha_fin
            : now();

        if ($suscripcion->periodo === 'mensual') {

            $nuevaFecha = $base->copy()->addMonth();
        } else {

            $nuevaFecha = $base->copy()->addYear();
        }
        $suscripcion->update([
            'estado' => 'activa',
            'fecha_inicio' => now(),
            'fecha_fin' => $nuevaFecha,
            'proximo_pago' => $nuevaFecha,
        ]);

        return back()->with(
            'success',
            'Pago aprobado correctamente'
        );
    }

    public function rechazar(Pago $pago)
    {
        $pago->update([
            'estado' => 'rechazado',
            'aprobado_por' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Pago rechazado'
        );
    }

    public function reporteMensual()
    {
        $pagos = Pago::whereMonth(
            'fecha_pago',
            now()->month
        )->whereYear(
            'fecha_pago',
            now()->year
        )->get();


        $total = $pagos->sum('monto');

        return view(
            'pagos.reporte',
            compact('pagos', 'total')
        );
    }
}
