<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Barryvdh\DomPDF\Facade\Pdf;

class RecetaController extends Controller
{
    public function generar(Consulta $consulta)
    {
        $consulta->load('paciente', 'tratamientos');

        if ($consulta->tratamientos->isEmpty()) {
            return back()->with('error', 'No hay medicamentos para generar receta.');
        }

        $pdf = Pdf::loadView('recetas.pdf', compact('consulta'));

        return $pdf->stream('receta-' . $consulta->id . '.pdf');
    }
}
