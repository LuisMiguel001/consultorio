<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstudioController extends Controller
{
    public function index(Paciente $paciente)
    {
        $estudios = $paciente->estudios()->orderByDesc('fecha')->get();
        return view('estudios.index', compact('paciente', 'estudios'));
    }

    public function store(Request $request, Paciente $paciente)
    {
        // Validaciones
        $request->validate([
            'tipo_estudio' => 'required|string|max:255',
            'fecha' => 'required|date',
            'resultado' => 'nullable|string',
            'medico' => 'required|string|max:255',
            'archivo' => 'nullable|file|mimes:pdf,jpg,png|max:10240' // máximo 10 MB
        ]);

        // Datos básicos
        $data = $request->only('tipo_estudio', 'fecha', 'resultado', 'medico');

        // Asignar paciente
        $data['paciente_id'] = $paciente->id;

        // Adjuntar archivo si existe y es válido
        if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
            $data['archivo'] = $request->file('archivo')->store('estudios', 'public');
        }

        // Crear registro
        $estudio = Estudio::create($data);

        // Retornar con éxito
        return redirect()->back()->with('success', 'Estudio registrado correctamente');
    }

    public function descargarArchivo(Estudio $estudio)
    {
        if (!$estudio->archivo || !Storage::disk('public')->exists($estudio->archivo)) {
            return redirect()->back()->with('error', 'Archivo no disponible');
        }
        $rutaArchivo = Storage::disk('public')->path($estudio->archivo);

        return response()->download($rutaArchivo);
    }
}
