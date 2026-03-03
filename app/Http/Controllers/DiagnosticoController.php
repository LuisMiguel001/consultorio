<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class DiagnosticoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $request->validate([
            'diagnostico' => 'required|string',
            'tipo' => 'required|string',
            'codigo_cie10' => 'nullable|string'
        ]);

        $consulta->diagnosticos()->create($request->all());

        return back()->with('success', 'Diagnóstico agregado correctamente');
    }
}
