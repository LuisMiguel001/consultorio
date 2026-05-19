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
        $modulosDisponibles = Plan::modulosDisponibles();
        return view('planes.create', compact('modulosDisponibles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:planes',
            'descripcion' => 'nullable|string',
            'precio_mensual' => 'required|numeric|min:0',
            'precio_anual' => 'required|numeric|min:0',
            'max_doctores' => 'nullable|integer|min:0',
            'max_secretarias' => 'nullable|integer|min:0',
            'max_enfermeras' => 'nullable|integer|min:0',
            'max_pacientes' => 'nullable|integer|min:0',
            'max_citas' => 'nullable|integer|min:0',
            'max_consultas' => 'nullable|integer|min:0',
            'max_mensajes_whatsapp' => 'nullable|integer|min:0',
            'caracteristicas' => 'nullable|string',
            'modulos_habilitados' => 'nullable|array',
        ]);

        Plan::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_mensual' => $request->precio_mensual,
            'precio_anual' => $request->precio_anual,
            'max_doctores' => $request->max_doctores ?: null,
            'max_secretarias' => $request->max_secretarias ?: null,
            'max_enfermeras' => $request->max_enfermeras ?: null,
            'max_pacientes' => $request->max_pacientes ?: null,
            'max_citas' => $request->max_citas ?: null,
            'max_consultas' => $request->max_consultas ?: null,
            'max_mensajes_whatsapp' => $request->max_mensajes_whatsapp ?: 0,
            'modulos_habilitados' => $request->modulos_habilitados ?? [],
            'permite_archivar' => $request->has('permite_archivar'),
            'permite_recordatorios' => $request->has('permite_recordatorios'),
            'permite_whatsapp' => $request->has('permite_whatsapp'),
            'permite_reportes_avanzados' => $request->has('permite_reportes_avanzados'),
            'permite_multiple_consultorios' => $request->has('permite_multiple_consultorios'),
            'caracteristicas' => $request->caracteristicas
                ? array_map('trim', explode(',', $request->caracteristicas))
                : [],
            'activo' => true,
        ]);

        return redirect()->route('planes.index')->with('success', 'Plan creado correctamente');
    }

    public function edit(Plan $plane)
    {
        $modulosDisponibles = Plan::modulosDisponibles();
        return view('planes.edit', compact('plane', 'modulosDisponibles'));
    }

    public function update(Request $request, Plan $plane)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:planes,nombre,' . $plane->id,
            'descripcion' => 'nullable|string',
            'precio_mensual' => 'required|numeric|min:0',
            'precio_anual' => 'required|numeric|min:0',
            'max_doctores' => 'nullable|integer|min:0',
            'max_secretarias' => 'nullable|integer|min:0',
            'max_enfermeras' => 'nullable|integer|min:0',
            'max_pacientes' => 'nullable|integer|min:0',
            'max_citas' => 'nullable|integer|min:0',
            'max_consultas' => 'nullable|integer|min:0',
            'max_mensajes_whatsapp' => 'nullable|integer|min:0',
            'caracteristicas' => 'nullable|string',
            'modulos_habilitados' => 'nullable|array',
        ]);

        $plane->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_mensual' => $request->precio_mensual,
            'precio_anual' => $request->precio_anual,
            'max_doctores' => $request->max_doctores ?: null,
            'max_secretarias' => $request->max_secretarias ?: null,
            'max_enfermeras' => $request->max_enfermeras ?: null,
            'max_pacientes' => $request->max_pacientes ?: null,
            'max_citas' => $request->max_citas ?: null,
            'max_consultas' => $request->max_consultas ?: null,
            'max_mensajes_whatsapp' => $request->max_mensajes_whatsapp ?: 0,
            'modulos_habilitados' => $request->modulos_habilitados ?? [],
            'permite_archivar' => $request->has('permite_archivar'),
            'permite_recordatorios' => $request->has('permite_recordatorios'),
            'permite_whatsapp' => $request->has('permite_whatsapp'),
            'permite_reportes_avanzados' => $request->has('permite_reportes_avanzados'),
            'permite_multiple_consultorios' => $request->has('permite_multiple_consultorios'),
            'caracteristicas' => $request->caracteristicas
                ? array_map('trim', explode(',', $request->caracteristicas))
                : [],
        ]);

        return redirect()->route('planes.index')->with('success', 'Plan actualizado correctamente');
    }

    public function destroy(Plan $plane)
    {
        // Verificar si el plan tiene suscripciones activas
        if ($plane->suscripciones()->where('activa', true)->exists()) {
            return back()->with('error', 'No se puede eliminar un plan con suscripciones activas');
        }

        $plane->delete();
        return back()->with('success', 'Plan eliminado correctamente');
    }

    public function toggleStatus(Plan $plane)
    {
        $plane->update(['activo' => !$plane->activo]);
        $estado = $plane->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Plan {$estado} correctamente");
    }

    public function planesPublicos()
    {
        $planes = Plan::where('activo', true)->get();
        return view('planes.publicos', compact('planes'));
    }
}
