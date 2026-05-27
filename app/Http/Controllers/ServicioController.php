<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where(
            'consultorio_id',
            Auth::user()->consultorio_id
        )
        ->latest()
        ->get();

        return view(
            'servicios.index',
            compact('servicios')
        );
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric|min:0'
        ]);

        Servicio::create([
            'consultorio_id' => Auth::user()->consultorio_id,
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'activo' => $request->activo ? 1 : 0
        ]);

        return redirect()
            ->route('servicios.index')
            ->with(
                'success',
                'Servicio registrado correctamente.'
            );
    }

    public function edit(Servicio $servicio)
    {
        if (
            $servicio->consultorio_id !=
            Auth::user()->consultorio_id
        ) {
            abort(403);
        }

        return view(
            'servicios.edit',
            compact('servicio')
        );
    }

    public function update(
        Request $request,
        Servicio $servicio
    ) {

        if (
            $servicio->consultorio_id !=
            Auth::user()->consultorio_id
        ) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric|min:0'
        ]);

        $servicio->update([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'activo' => $request->activo ? 1 : 0
        ]);

        return redirect()
            ->route('servicios.index')
            ->with(
                'success',
                'Servicio actualizado.'
            );
    }

    public function destroy(Servicio $servicio)
    {
        if (
            $servicio->consultorio_id !=
            Auth::user()->consultorio_id
        ) {
            abort(403);
        }

        $servicio->delete();

        return back()->with(
            'success',
            'Servicio eliminado.'
        );
    }
}
