<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EstudioController extends Controller
{
    public function index(Consulta $consulta)
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

        $estudios = $consulta->estudios()
            ->orderByDesc('fecha_estudio')
            ->get();

        return view(
            'estudios.index',
            compact('consulta', 'estudios')
        );
    }

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

        // =========================
        // VALIDACIÓN
        // =========================

        $request->validate([
            'tipo_estudio' => 'required|string|max:255',
            'estado' => 'required|in:indicado,realizado',
            'fecha_estudio' => 'nullable|date',
            'resultado' => 'nullable|string',
            'archivo' => 'nullable|file|mimes:pdf,jpg,png|max:10240'
        ]);

        $data = $request->only(
            'tipo_estudio',
            'estado',
            'fecha_estudio',
            'resultado'
        );

        $data['consulta_id'] = $consulta->id;

        if (
            $request->hasFile('archivo') &&
            $request->file('archivo')->isValid()
        ) {

            $data['archivo'] = $request
                ->file('archivo')
                ->store('estudios', 'public');
        }

        Estudio::create($data);

        return redirect()
            ->back()
            ->with(
                'success',
                'Estudio registrado correctamente'
            );
    }

    public function descargarArchivo(Estudio $estudio)
    {
        $user = Auth::user();

        if (
            $estudio->consulta->paciente->consultorio_id !=
            $user->consultorio_id
        ) {
            abort(404);
        }

        if (
            $user->roles->contains('name', 'doctor') &&
            $estudio->consulta->doctor_id !=
                $user->doctor_principal
        ) {
            abort(404);
        }

        if (
            !$estudio->archivo ||
            !Storage::disk('public')->exists(
                $estudio->archivo
            )
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Archivo no disponible'
                );
        }

        $rutaArchivo = Storage::disk('public')
            ->path($estudio->archivo);

        return response()->download(
            $rutaArchivo
        );
    }
}
