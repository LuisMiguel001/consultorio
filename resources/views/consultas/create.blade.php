@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now()->format('Y-m-d\TH:i');
    @endphp

    <div class="container my-4">
        <div class="card shadow-sm border-0" style="border-radius:15px;">
            <div class="card-header text-white"
                style="background: #0d47a1; border-radius:15px 15px 0 0;">
                <h4 class="mb-0">Nuevo Historial Clínico</h4>
                <small>Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}</small>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('consultas.store') }}">
                    @csrf
                    <input type="hidden" name="cita_id" value="{{ $cita_id }}">
                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                    <!-- Fecha y Hora -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Fecha y Hora</strong></label>
                        <input type="datetime-local" name="fecha_consulta" class="form-control" value="{{ $now }}"
                            readonly required>
                        <small class="text-muted">La fecha y hora se registran automáticamente</small>
                    </div>

                    <!-- Tipo de Consulta -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Tipo de Consulta</strong></label>
                        <select name="tipo_consulta" class="form-select" required>
                            <option value="">--Seleccione--</option>
                            <option value="Consulta General">Consulta General</option>
                            <option value="Control">Control</option>
                            <option value="Postquirurgico">Postquirúrgico</option>
                            <option value="Emergencia">Emergencia</option>
                        </select>
                    </div>

                    <!-- Motivo de Consulta -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Motivo de Consulta</strong></label>
                        <textarea name="motivo_consulta" class="form-control" rows="3" placeholder="Describa el motivo de la consulta..."></textarea>
                    </div>

                    <!-- Enfermedad Actual -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Enfermedad Actual</strong></label>
                        <textarea name="enfermedad_actual" class="form-control" rows="3" placeholder="Describa la enfermedad actual..."></textarea>
                    </div>

                    <!-- Plan -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Plan</strong></label>
                        <textarea name="plan" class="form-control" rows="3" placeholder="Indique el plan de tratamiento..."></textarea>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Observaciones</strong></label>
                        <textarea name="observaciones" class="form-control" rows="2"
                            placeholder="Agregue cualquier observación adicional..."></textarea>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="text-center">
                        <button class="btn btn-gradient"
                            style="background: #0d47a1; color:white; border:none; border-radius:8px;">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .form-label {
        font-weight: 600;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
    }

    .btn-gradient:hover {
        opacity: 0.9;
    }
</style>
