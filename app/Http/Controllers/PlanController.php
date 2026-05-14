<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $planes = Plan::latest()->get();

        return view('planes.index', compact('planes'));
    }

    public function create()
    {
        return view('planes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'precio_mensual' => 'required|numeric',
            'precio_anual' => 'required|numeric',
        ]);

        Plan::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_mensual' => $request->precio_mensual,
            'precio_anual' => $request->precio_anual,
            'max_doctores' => $request->max_doctores,
            'max_secretarias' => $request->max_secretarias,
            'max_enfermeras' => $request->max_enfermeras,
            'max_pacientes' => $request->max_pacientes,
            'caracteristicas' => $request->caracteristicas
                ? explode(',', $request->caracteristicas)
                : [],
            'activo' => true,
        ]);

        return redirect()
            ->route('planes.index')
            ->with('success', 'Plan creado correctamente');
    }

    public function edit(Plan $plane)
    {
        return view('planes.edit', compact('plane'));
    }

    public function update(Request $request, Plan $plane)
    {
        $plane->update($request->all());

        return redirect()
            ->route('planes.index')
            ->with('success', 'Plan actualizado');
    }

    public function destroy(Plan $plane)
    {
        $plane->delete();

        return back()->with('success', 'Plan eliminado');
    }

    public function planesPublicos()
    {
        $planes = Plan::where('activo', true)->get();

        return view('planes.publicos', compact('planes'));
    }
}
