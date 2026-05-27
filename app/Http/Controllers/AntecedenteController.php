<?php

namespace App\Http\Controllers;

use App\Models\Antecedente;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AntecedenteController extends Controller
{
    public function index($paciente_id)
    {
        $user = Auth::user();

        $paciente = Paciente::with('antecedentes')
            ->where('id', $paciente_id)
            ->where(
                'consultorio_id',
                $user->consultorio_id
            )
            ->firstOrFail();

        if (
            $paciente->doctor_id !=
            $user->doctor_principal
        ) {
            abort(403);
        }

        return view(
            'antecedentes.index',
            compact('paciente')
        );
    }

    public function store(Request $request, $paciente_id)
    {
        $user = Auth::user();

        $paciente = Paciente::where(
            'id',
            $paciente_id
        )
            ->where(
                'consultorio_id',
                $user->consultorio_id
            )
            ->firstOrFail();

        if (
            $paciente->doctor_id !=
            $user->doctor_principal
        ) {
            abort(403);
        }

        $request->validate([
            'antecedentes_personales' => 'nullable|string',
        ]);

        Antecedente::create([
            'paciente_id' => $paciente_id,
            'user_id' => Auth::id(),
            'antecedentes_personales' =>
                $request->antecedentes_personales,
            'antecedentes_familiares' =>
                $request->antecedentes_familiares,
            'antecedentes_quirurgicos' =>
                $request->antecedentes_quirurgicos,
            'alergias' => $request->alergias,
            'medicamentos_actuales' =>
                $request->medicamentos_actuales,
            'habitos' => $request->habitos,
        ]);

        return back()->with(
            'success',
            'Antecedentes guardados correctamente.'
        );
    }
}
