<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class TratamientoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $request->validate([
            'medicamento' => 'required|string',
            'dosis' => 'required|string',
            'via_administracion' => 'required|string'
        ]);

        $consulta->tratamientos()->create($request->all());

        return back()->with('success', 'Tratamiento agregado correctamente');
    }
}
