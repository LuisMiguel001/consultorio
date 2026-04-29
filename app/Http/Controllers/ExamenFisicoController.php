<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class ExamenFisicoController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $data = $request->validate([
            'estado_general' => 'nullable|string',
            'cabeza_cuello' => 'nullable|string',
            'cardiovascular' => 'nullable|string',
            'respiratorio' => 'nullable|string',
            'abdomen' => 'nullable|string',
            'extremidades' => 'nullable|string',
            'neurologico' => 'nullable|string',
            'otros' => 'nullable|string',

            //GINECOLOGÍA
            'genitales_externos' => 'nullable|string',
            'especuloscopia' => 'nullable|string',
            'tacto_vaginal' => 'nullable|string',
            'flujo_vaginal' => 'nullable|string',
            'dolor_pelvico' => 'nullable|string',
            'hallazgos_gineco' => 'nullable|string',
        ]);

        $consulta->examenFisico()->updateOrCreate(
            ['consulta_id' => $consulta->id],
            $data
        );

        return back()->with('success', 'Examen físico guardado correctamente');
    }
}
