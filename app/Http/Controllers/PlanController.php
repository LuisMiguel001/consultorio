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
            'max_citas' => $request->max_citas,
            'max_consultas' => $request->max_consultas,
            'max_mensajes_whatsapp' => $request->max_mensajes_whatsapp,
            'modulos_habilitados' => $request->modulos_habilitados ?? [],
            'permite_archivar' => $request->has('permite_archivar'),
            'permite_recordatorios' => $request->has('permite_recordatorios'),
            'permite_whatsapp' => $request->has('permite_whatsapp'),
            'permite_reportes_avanzados' => $request->has('permite_reportes_avanzados'),
            'permite_multiple_consultorios' => $request->has('permite_multiple_consultorios'),
            'caracteristicas' => $request->caracteristicas
                ? explode(',', $request->caracteristicas)
                : [],
            'activo' => true,
        ]);

        return redirect()->route('planes.index')->with('success', 'Plan creado correctamente');
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
        $planes = Plan::whereIn('nombre', ['Estándar', 'Plus'])
            ->where('activo', true)
            ->get();

        return view('planes.publicos', compact('planes'));
    }
}
