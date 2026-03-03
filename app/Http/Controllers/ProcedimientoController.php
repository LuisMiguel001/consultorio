<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class ProcedimientoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $request->validate([
            'nombre' => 'required|string',
            'fecha' => 'required|date',
            'estado' => 'required|string'
        ]);

        $consulta->procedimientos()->create($request->all());

        return back()->with('success', 'Procedimiento registrado correctamente');
    }
}
