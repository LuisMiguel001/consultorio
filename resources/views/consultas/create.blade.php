@extends('layouts.app')

@section('content')

<h3>Nueva Consulta - {{ $paciente->nombre }} {{ $paciente->apellido }}</h3>

<form method="POST" action="{{ route('consultas.store') }}">
    @csrf

    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

    <div class="mb-3">
        <label>Fecha</label>
        <input type="date" name="fecha_consulta"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tipo de Consulta</label>
        <select name="tipo_consulta" class="form-control" required>
            <option value="Consulta">Consulta</option>
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

    <button class="btn btn-primary">Guardar Consulta</button>

</form>

@endsection
