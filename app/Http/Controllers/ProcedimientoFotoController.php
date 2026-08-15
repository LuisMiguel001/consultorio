<?php

namespace App\Http\Controllers;

use App\Models\ConsultaDermatologica;
use App\Models\ProcedimientoFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcedimientoFotoController extends Controller
{
    public function store(Request $request, ConsultaDermatologica $consultaDermatologica)
    {
        $user = Auth::user();

        // Seguridad: la consulta debe pertenecer a un paciente del consultorio del usuario
        $consultaDermatologica->load('consulta.paciente');

        if ($consultaDermatologica->consulta->paciente->consultorio_id != $user->consultorio_id) {
            abort(404);
        }

        $request->validate([
            'etapa'    => 'required|in:antes,durante,despues',
            'fotos'    => 'required|array|min:1',
            'fotos.*'  => 'image|mimes:jpg,jpeg,png,webp|max:8192', // 8MB c/u
            'descripcion' => 'nullable|string|max:150',
        ]);

        $pacienteId = $consultaDermatologica->consulta->paciente_id;

        $ordenActual = $consultaDermatologica->fotos()
            ->where('etapa', $request->etapa)
            ->max('orden') ?? 0;

        foreach ($request->file('fotos') as $archivo) {

            $nombre = Str::uuid() . '.' . $archivo->getClientOriginalExtension();

            $ruta = $archivo->storeAs(
                "pacientes/{$pacienteId}/dermatologia/{$consultaDermatologica->id}/{$request->etapa}",
                $nombre,
                'public'
            );

            ProcedimientoFoto::create([
                'consulta_dermatologica_id' => $consultaDermatologica->id,
                'etapa'       => $request->etapa,
                'ruta'        => $ruta,
                'descripcion' => $request->descripcion,
                'orden'       => ++$ordenActual,
                'subido_por'  => $user->id,
            ]);
        }

        return back()->with('success', 'Fotos subidas correctamente');
    }

    public function destroy(ProcedimientoFoto $foto)
    {
        $user = Auth::user();

        $foto->load('consultaDermatologica.consulta.paciente');

        if ($foto->consultaDermatologica->consulta->paciente->consultorio_id != $user->consultorio_id) {
            abort(404);
        }

        Storage::disk('public')->delete($foto->ruta);
        $foto->delete();

        return back()->with('success', 'Foto eliminada');
    }

    public function comparar(ConsultaDermatologica $consultaDermatologica)
    {
        $user = Auth::user();

        $consultaDermatologica->load('consulta.paciente', 'fotos');

        if ($consultaDermatologica->consulta->paciente->consultorio_id != $user->consultorio_id) {
            abort(404);
        }

        return view('dermatologia.comparar', [
            'dermatologia' => $consultaDermatologica,
            'fotosAntes' => $consultaDermatologica->fotos->where('etapa', 'antes')->sortBy('orden'),
            'fotosDespues' => $consultaDermatologica->fotos->where('etapa', 'despues')->sortBy('orden'),
        ]);
    }
}
