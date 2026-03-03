<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class EvolucionController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $data = $request->validate([
            'nota' => 'required|string',
            'plan' => 'nullable|string',
        ]);

        $consulta->evoluciones()->create($data);

        return back()->with('success', 'Evolución registrada correctamente');
    }
}
