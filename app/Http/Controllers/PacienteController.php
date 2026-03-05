<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'cedula' => 'nullable|max:20|unique:pacientes,cedula',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:10',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:15'
        ]);

        Paciente::create($request->all());

        return redirect()->back()->with('success', 'Paciente registrado correctamente');
    }

    public function create()
    {
        return view('pacientes');
    }

    public function lista(Request $request)
    {
        $query = Paciente::query();

        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellido', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%");
        }

        $pacientes = $query->get();
        return view('lista_pacientes', compact('pacientes'));
    }

    public function inicio()
    {
        return view('inicio');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load([
            'consultas.doctor',
            'consultas.diagnosticos',
            'consultas.tratamientos',
            'consultas.procedimientos',
            'consultas.estudios',
            'consultas.evoluciones',
            'antecedentes.usuario'
        ]);

        $eventos = collect();

        foreach ($paciente->consultas as $consulta) {
            // Consulta
            $eventos->push([
                'tipo' => 'Consulta',
                'fecha' => $consulta->created_at,
                'contenido' => "Consulta {$consulta->tipo_consulta} - Dr. {$consulta->doctor->name}"
            ]);

            foreach ($consulta->diagnosticos as $diag) {
                $eventos->push([
                    'tipo' => 'Diagnóstico',
                    'fecha' => $diag->created_at,
                    'contenido' => $diag->descripcion
                ]);
            }

            foreach ($consulta->tratamientos as $med) {
                $eventos->push([
                    'tipo' => 'Medicamento',
                    'fecha' => $med->created_at,
                    'contenido' => "{$med->medicamento} - {$med->dosis} ({$med->frecuencia})"
                ]);
            }

            foreach ($consulta->procedimientos as $proc) {
                $eventos->push([
                    'tipo' => 'Procedimiento',
                    'fecha' => $proc->created_at,
                    'contenido' => $proc->descripcion
                ]);
            }

            foreach ($consulta->estudios as $est) {
                $eventos->push([
                    'tipo' => 'Estudio',
                    'fecha' => $est->created_at,
                    'contenido' => $est->nombre
                ]);
            }

            foreach ($consulta->evoluciones as $evo) {
                $eventos->push([
                    'tipo' => 'Evolución',
                    'fecha' => $evo->created_at,
                    'contenido' => $evo->descripcion
                ]);
            }
        }

        // Ordenar eventos por fecha (más reciente primero)
        $eventos = $eventos->sortByDesc('fecha')->values();

        return view('pacientes.show', compact('paciente', 'eventos'));
    }

    // NUEVOS MÉTODOS

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('editar_paciente', compact('paciente'));
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'nullable|max:20|unique:pacientes,cedula,' . $id,
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required',
            'email' => 'nullable|email',
            'telefono' => 'nullable|max:20',
            'nss' => 'nullable|max:15'
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.edit', $id)
            ->with('success', 'Paciente actualizado correctamente');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();

        return redirect()->route('pacientes.lista')
            ->with('success', 'Paciente eliminado correctamente');
    }
}
