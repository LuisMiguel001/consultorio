@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now()->format('Y-m-d\TH:i');
    @endphp

    <h3>Nuevo Historial Clínico: {{ $paciente->nombre }} {{ $paciente->apellido }}</h3>

    <form method="POST" action="{{ route('consultas.store') }}">
        @csrf

        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

        <div class="mb-3">
            <label>Fecha y Hora</label>
            <input type="datetime-local" name="fecha_consulta" class="form-control" value="{{ $now }}" readonly
                required>
            <small class="text-muted">La fecha y hora se registran automáticamente</small>
        </div>

        <div class="mb-3">
            <label>Tipo de Consulta</label>
            <select name="tipo_consulta" class="form-control" required>
                <option value="">--Seleccione--</option>
                <option value="Consulta General">Consulta General</option>
                <option value="Control">Control</option>
                <option value="Postquirurgico">Postquirúrgico</option>
                <option value="Emergencia">Emergencia</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Motivo de Consulta</label>
            <textarea name="motivo_consulta" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Enfermedad Actual</label>
            <textarea name="enfermedad_actual" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Plan</label>
            <textarea name="plan" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Guardar</button>

    </form>
@endsection
