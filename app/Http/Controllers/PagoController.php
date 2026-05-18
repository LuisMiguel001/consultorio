<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Consultorio;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with(['consultorio', 'plan', 'suscripcion']);

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

        $pagos = $query->latest()->paginate(20);

        // ESTADÍSTICAS
        $estadisticas = [
            'total' => Pago::count(),
            'pendientes' => Pago::where('estado', 'pendiente')->count(),
            'aprobados' => Pago::where('estado', 'aprobado')->count(),
            'rechazados' => Pago::where('estado', 'rechazado')->count(),
            'ingresosMes' => Pago::where('estado', 'aprobado')
                ->whereMonth('fecha_pago', now()->month)
                ->sum('monto'),
        ];

        return view('pagos.index', compact('pagos', 'estadisticas'));
    }

    public function create(Request $request)
    {
        // Obtener todos los planes activos (incluyendo el estándar)
        $planes = Plan::where('activo', true)->get();

        // Obtener consultorios (para cuando se selecciona uno nuevo)
        $consultorios = Consultorio::all();

        // Obtener suscripciones existentes para renovación
        $suscripciones = Suscripcion::with(['consultorio', 'plan'])
            ->whereIn('estado', ['activa', 'pendiente'])
            ->where(function ($q) {
                $q->where('fecha_fin', '>', now())
                    ->orWhere('estado', 'pendiente');
            })
            ->get();

        // Pre-seleccionar consultorio si viene por parámetro
        $consultorioId = $request->consultorio;

        return view('pagos.create', compact('suscripciones', 'consultorios', 'planes', 'consultorioId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_registro' => 'required|in:existente,nueva',
            'metodo_pago' => 'required',
            'comprobante' => 'nullable|image|max:5000',
        ]);

        DB::beginTransaction();

        try {
            // Guardar comprobante si existe
            $comprobante = null;
            if ($request->hasFile('comprobante')) {
                $comprobante = $request->file('comprobante')->store('comprobantes', 'public');
            }

            $suscripcion = null;

            // CASO 1: Suscripción existente (RENOVACIÓN)
            if ($request->tipo_registro === 'existente') {
                $request->validate([
                    'suscripcion_id' => 'required|exists:suscripciones,id'
                ]);

                $suscripcion = Suscripcion::findOrFail($request->suscripcion_id);

                // Validar que no tenga un pago pendiente reciente
                $pagoPendiente = $suscripcion->pagos()
                    ->where('estado', 'pendiente')
                    ->where('created_at', '>', now()->subDays(7))
                    ->exists();

                if ($pagoPendiente) {
                    throw new \Exception('Ya existe un pago pendiente para esta suscripción. Espera a que sea procesado.');
                }

                // Calcular monto según el período de la suscripción
                $monto = $suscripcion->periodo === 'mensual'
                    ? $suscripcion->plan->precio_mensual
                    : $suscripcion->plan->precio_anual;

                // Registrar el pago para renovación
                $pago = Pago::create([
                    'suscripcion_id' => $suscripcion->id,
                    'consultorio_id' => $suscripcion->consultorio_id,
                    'plan_id' => $suscripcion->plan_id,
                    'monto' => $request->monto ?? $monto,
                    'estado' => 'pendiente',
                    'metodo_pago' => $request->metodo_pago,
                    'referencia' => $request->referencia,
                    'notas' => $request->notas,
                    'comprobante' => $comprobante,
                    'fecha_pago' => now(),
                ]);

                $mensaje = "Pago registrado para renovación de {$suscripcion->consultorio->nombre}. Esperando aprobación.";
            }

            // CASO 2: Nueva suscripción (PRIMERA VEZ)
            else {
                $request->validate([
                    'nuevo_consultorio_id' => 'required|exists:consultorios,id',
                    'nuevo_plan_id' => 'required|exists:plans,id',
                    'periodo' => 'required|in:mensual,anual',
                ]);

                $consultorio = Consultorio::findOrFail($request->nuevo_consultorio_id);
                $plan = Plan::findOrFail($request->nuevo_plan_id);

                // Verificar si ya tiene suscripción activa
                $suscripcionActiva = Suscripcion::where('consultorio_id', $consultorio->id)
                    ->where('estado', 'activa')
                    ->where('fecha_fin', '>', now())
                    ->exists();

                if ($suscripcionActiva) {
                    throw new \Exception("{$consultorio->nombre} ya tiene una suscripción activa. Mejor registra un pago para renovación.");
                }

                // Calcular fechas
                $fechaInicio = now();
                $fechaFin = $request->periodo === 'mensual'
                    ? now()->addMonth()
                    : now()->addYear();

                // Calcular monto
                $monto = $request->periodo === 'mensual'
                    ? $plan->precio_mensual
                    : $plan->precio_anual;

                // Crear la suscripción INCLUYENDO monto_pagado
                $suscripcion = Suscripcion::create([
                    'consultorio_id' => $consultorio->id,
                    'plan_id' => $plan->id,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'proximo_pago' => $fechaFin,
                    'estado' => 'pendiente',
                    'periodo' => $request->periodo,
                    'monto_pagado' => $monto, // ← AGREGAR ESTO
                ]);

                // Registrar el primer pago
                $pago = Pago::create([
                    'suscripcion_id' => $suscripcion->id,
                    'consultorio_id' => $consultorio->id,
                    'plan_id' => $plan->id,
                    'monto' => $request->monto ?? $monto,
                    'estado' => 'pendiente',
                    'metodo_pago' => $request->metodo_pago,
                    'referencia' => $request->referencia,
                    'notas' => $request->notas,
                    'comprobante' => $comprobante,
                    'fecha_pago' => now(),
                ]);

                $mensaje = "¡Excelente! Se ha creado la suscripción al plan {$plan->nombre} para {$consultorio->nombre} y registrado el pago inicial. Esperando aprobación.";
            }

            DB::commit();
            $user = Auth::user();

            if ($user->roles->contains('name', 'admin')) {
                return redirect()->route('pagos.index')->with('success', $mensaje);
            }

            return redirect()->route('consultorios.show', $suscripcion->consultorio_id)
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function aprobar(Pago $pago)
    {
        DB::beginTransaction();

        try {
            if ($pago->estado === 'aprobado') {
                throw new \Exception('Este pago ya fue aprobado');
            }

            $pago->update([
                'estado' => 'aprobado',
                'aprobado_por' => Auth::id(),
            ]);

            $suscripcion = $pago->suscripcion;

            // Si la suscripción está pendiente, activarla
            if ($suscripcion->estado === 'pendiente') {
                $suscripcion->update([
                    'estado' => 'activa'
                ]);
            }
            // Si ya está activa, renovar
            elseif ($suscripcion->estado === 'activa') {
                // Calcular nueva fecha según período
                $base = $suscripcion->fecha_fin > now()
                    ? $suscripcion->fecha_fin
                    : now();

                if ($suscripcion->periodo === 'mensual') {
                    $nuevaFecha = $base->copy()->addMonth();
                } else {
                    $nuevaFecha = $base->copy()->addYear();
                }

                $suscripcion->update([
                    'fecha_inicio' => now(),
                    'fecha_fin' => $nuevaFecha,
                    'proximo_pago' => $nuevaFecha,
                    'estado' => 'activa',
                ]);
            }

            DB::commit();

            return back()->with('success', 'Pago aprobado y suscripción actualizada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function rechazar(Pago $pago)
    {
        DB::beginTransaction();

        try {
            $pago->update([
                'estado' => 'rechazado',
                'aprobado_por' => Auth::id(),
            ]);

            // Si la suscripción está pendiente y es el único pago, cancelarla
            $suscripcion = $pago->suscripcion;

            if ($suscripcion->estado === 'pendiente') {
                $pagosPendientes = $suscripcion->pagos()
                    ->where('estado', 'pendiente')
                    ->where('id', '!=', $pago->id)
                    ->count();

                if ($pagosPendientes === 0) {
                    $suscripcion->update(['estado' => 'cancelada']);
                }
            }

            DB::commit();

            return back()->with('success', 'Pago rechazado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reporteMensual()
    {
        $pagos = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->where('estado', 'aprobado')
            ->get();

        $total = $pagos->sum('monto');

        return view('pagos.reporte', compact('pagos', 'total'));
    }
}
