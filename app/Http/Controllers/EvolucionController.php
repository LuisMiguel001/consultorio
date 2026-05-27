<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;

class EvolucionController extends Controller
{
    public function store(Request $request, Consulta $consulta)
    {
        $user = Auth::user();

        if (
            $consulta->paciente->consultorio_id !=
            $user->consultorio_id
        ) {
            abort(404);
        }

        if (
            $user->roles->contains('name', 'doctor') &&
            $consulta->doctor_id !=
                $user->doctor_principal
        ) {
            abort(404);
        }

        $data = $request->validate([
            'nota' => 'required|string',
            'plan' => 'nullable|string',
        ]);

        $consulta->evoluciones()->create($data);

        return back()->with(
            'success',
            'Evolución registrada correctamente'
        );
    }
}
