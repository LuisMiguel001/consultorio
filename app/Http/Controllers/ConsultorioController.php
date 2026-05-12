<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use Illuminate\Http\Request;

class ConsultorioController extends Controller
{
    public function index()
    {
        $consultorios = Consultorio::withCount([
            'usuarios',
            'doctores'
        ])
            ->latest()
            ->paginate(15);

        return view(
            'consultorios.index',
            compact('consultorios')
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
