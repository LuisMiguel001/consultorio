@extends('layouts.app')

@section('content')
    @php
        $especialidad = auth()->user()->especialidad->slug ?? null;
    @endphp
    @php
        use Carbon\Carbon;
        $now = Carbon::now()->format('Y-m-d\TH:i');
    @endphp

    <div class="container my-4">
        <div class="card shadow-sm border-0" style="border-radius:15px;">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background: #0d47a1; color:white; border-radius:15px 15px 0 0;">
                <div>
                    <h5 class="mb-0">Nuevo Historial Clínico</h5>
                    <small>Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}</small>
                </div>
                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-light"
                    style="color: #0d47a1; border-radius: 6px;">
                    Cancelar
                </a>
            </div>

            <div class="card-body p-4">
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

                    @if ($especialidad === 'ginecologia')
                        <div class="card mb-3" style="background-color: #f8f9fa; border-radius: 12px;">
                            <div class="card-header" style="background-color: #e3f2fd; color: #0d47a1; border-radius: 12px 12px 0 0;">
                                <strong>Datos Ginecológicos</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">FUM (Fecha Última Menstruación)</label>
                                        <input type="date" name="fum" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Ciclo Menstrual</label>
                                        <input type="text" name="ciclo" class="form-control" placeholder="Ej: 28 días">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Gestas</label>
                                        <input type="number" name="gestas" class="form-control" min="0">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Partos</label>
                                        <input type="number" name="partos" class="form-control" min="0">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Abortos</label>
                                        <input type="number" name="abortos" class="form-control" min="0">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cesáreas</label>
                                        <input type="number" name="cesareas" class="form-control" min="0">
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Embarazo Actual</label>
                                        <select name="embarazo" class="form-select">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Semanas de Gestación</label>
                                        <input type="number" name="semanas" class="form-control" min="0" max="42" step="1">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Método Anticonceptivo</label>
                                        <select name="metodo" class="form-select">
                                            <option value="">Ninguno</option>
                                            <option value="Orales">Orales</option>
                                            <option value="Inyectable">Inyectable</option>
                                            <option value="Implante">Implante</option>
                                            <option value="DIU">DIU</option>
                                            <option value="Parche">Parche</option>
                                            <option value="Preservativo">Preservativo</option>
                                            <option value="Ligadura de trompas">Ligadura de trompas</option>
                                            <option value="Vasectomía">Vasectomía</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Vida Sexual Activa</label>
                                        <select name="vida_sexual" class="form-select">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Examen Pélvico</label>
                                    <textarea name="examen_pelvico" class="form-control" rows="2" placeholder="Describa los hallazgos del examen pélvico..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Examen de Mamas</label>
                                    <textarea name="mamas" class="form-control" rows="2" placeholder="Describa los hallazgos del examen de mamas..."></textarea>
                                </div>
                            </div>
                        </div>
                    @endif

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

                    <!-- Botones -->
                    <div class="text-center">
                        <button type="submit" class="btn" style="background: #0d47a1; color:white; border:none; border-radius:8px; padding: 8px 30px;">
                            Guardar
                        </button>
                        <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-secondary" style="border-radius:8px;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .form-label {
        font-weight: 600;
        color: #1a2b3c;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.25);
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s;
    }

    .card-header {
        font-weight: 500;
    }

    small.text-muted {
        font-size: 0.875rem;
    }
</style>
