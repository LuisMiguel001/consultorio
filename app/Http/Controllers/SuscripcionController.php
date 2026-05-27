<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index()
    {
        $suscripciones = Suscripcion::with(
            'consultorio',
            'plan'
        )->latest()->get();

        return view(
            'suscripciones.index',
            compact('suscripciones')
        );
    }

    public function create()
    {
        $consultorios = Consultorio::all();
        $planes = Plan::where('activo', true)->get();

        return view(
            'suscripciones.create',
            compact('consultorios', 'planes')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'consultorio_id' => 'required',
            'plan_id' => 'required',
            'periodo' => 'required',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        $fechaInicio = now();

        $fechaFin = $request->periodo === 'mensual'
            ? now()->addMonth()
            : now()->addYear();

        Suscripcion::create([
            'consultorio_id' => $request->consultorio_id,
            'plan_id' => $plan->id,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'proximo_pago' => $fechaFin,
            'estado' => 'pendiente',
            'periodo' => $request->periodo,
        ]);

        return redirect()
            ->route('suscripciones.index')
            ->with('success', 'Suscripción creada');
    }

    public function edit(Suscripcion $suscripcion)
    {
        $consultorios = Consultorio::all();
        $planes = Plan::all();

        return view(
            'suscripciones.edit',
            compact(
                'suscripcion',
                'consultorios',
                'planes'
            )
        );
    }

    public function update(
        Request $request,
        Suscripcion $suscripcion
    ) {
        $suscripcion->update($request->all());

        return redirect()
            ->route('suscripciones.index')
            ->with('success', 'Suscripción actualizada');
    }

    public function destroy(Suscripcion $suscripcion)
    {
        $suscripcion->delete();

        return back()->with('success', 'Suscripción eliminada');
    }

    public function renovar(Suscripcion $suscripcion)
    {
        $suscripcion->renovar();

        return back()->with(
            'success',
            'Suscripción renovada'
        );
    }

    public function cancelar(Suscripcion $suscripcion)
    {
        $suscripcion->update([
            'estado' => 'cancelada'
        ]);

        return back()->with(
            'success',
            'Suscripción cancelada'
        );
    }

    public function cambiarPlan(
        Request $request,
        Suscripcion $suscripcion
    ) {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $suscripcion->update([
            'plan_id' => $request->plan_id
        ]);

        return back()->with(
            'success',
            'Plan actualizado'
        );
    }
}
