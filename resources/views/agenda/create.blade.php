@extends('layouts.app')

@section('content')

<h3>Agendar Cita</h3>

<form method="POST" action="{{ route('citas.store') }}">
    @csrf

    <div class="mb-3">
        <label>Paciente</label>
        <select name="paciente_id" class="form-control" required>
            <option value="">Seleccione...</option>
            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}">
                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Fecha</label>
        <input type="date" name="fecha" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Hora</label>
        <input type="time" name="hora" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Duración (min)</label>
        <input type="number" name="duracion_minutos" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
        <label>Tipo Consulta</label>
        <select name="tipo_consulta" class="form-control" required>
            <option>Inicial</option>
            <option>Seguimiento</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Notas Previas</label>
        <textarea name="notas_previas" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>

@endsection
