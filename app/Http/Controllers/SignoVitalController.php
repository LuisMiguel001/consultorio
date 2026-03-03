<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class SignoVitalController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $data = $request->validate([
            'presion_sistolica' => 'nullable|integer',
            'presion_diastolica' => 'nullable|integer',
            'frecuencia_cardiaca' => 'nullable|integer',
            'frecuencia_respiratoria' => 'nullable|integer',
            'temperatura' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|numeric',
            'saturacion_oxigeno' => 'nullable|integer',
        ]);

        // Calcular IMC automáticamente
        if (!empty($data['peso']) && !empty($data['talla'])) {
            $data['imc'] = $data['peso'] / ($data['talla'] * $data['talla']);
        }

        $consulta->signoVital()->updateOrCreate(
            ['consulta_id' => $consulta->id],
            $data
        );

        return back()->with('success', 'Signos vitales guardados correctamente');
    }
}
