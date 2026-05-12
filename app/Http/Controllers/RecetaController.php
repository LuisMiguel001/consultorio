<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RecetaController extends Controller
{
    public function generar(Consulta $consulta)
    {
        $user = Auth::user();

        if (
            $consulta->paciente->consultorio_id != $user->consultorio_id
        ) {
            abort(403);
        }

        if (
            $consulta->doctor_id != $user->doctor_principal
        ) {
            abort(403);
        }

        $consulta->load(
            'paciente',
            'tratamientos'
        );

        if ($consulta->tratamientos->isEmpty()) {

            return back()->with(
                'error',
                'No hay medicamentos para generar receta.'
            );
        }

        $pdf = Pdf::loadView(
            'recetas.pdf',
            compact('consulta')
        );

        return $pdf->stream(
            'receta-' . $consulta->id . '.pdf'
        );
    }
}
