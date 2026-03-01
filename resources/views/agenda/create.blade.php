{{-- resources/views/agenda/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0" style="border-radius: 18px;">
        <div class="card-header"
             style="background: linear-gradient(135deg, #a97bc9, #8e44ad);
                    color: white;
                    border-radius: 18px 18px 0 0;">
            <h4 class="mb-0">
                <i class="fas fa-heartbeat me-2"></i>
                Agendar Procedimiento Cardiovascular
            </h4>
            <small>Dr. {{ Auth::user()->name }} - Cirugía Cardiovascular</small>
        </div>

        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('citas.store') }}">
                @csrf

                <!-- SELECCIÓN DE PACIENTE -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-user me-1"></i>
                            Paciente <span class="text-danger">*</span>
                        </label>
                        <select name="paciente_id" class="form-select" required>
                            <option value="">Buscar paciente...</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id }}"
                                    {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                                    (Cédula: {{ $paciente->cedula }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- SERVICIOS ESPECÍFICOS (basado en tu imagen) -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-procedures me-1"></i>
                            Tipo de Procedimiento <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card service-card {{ old('servicio_especifico') == 'Cirugía de Corazón Abierto' ? 'selected' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="servicio_especifico"
                                                   id="cirugia_corazon"
                                                   value="Cirugía de Corazón Abierto"
                                                   {{ old('servicio_especifico') == 'Cirugía de Corazón Abierto' ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label fw-bold" for="cirugia_corazon">
                                                Cirugía de Corazón Abierto
                                            </label>
                                            <p class="text-muted small mt-2 mb-0">
                                                Procedimientos avanzados con tecnología moderna para tratamiento cardiovascular complejo.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card service-card {{ old('servicio_especifico') == 'Cirugía Venosa con Láser' ? 'selected' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="servicio_especifico"
                                                   id="cirugia_venosa"
                                                   value="Cirugía Venosa con Láser"
                                                   {{ old('servicio_especifico') == 'Cirugía Venosa con Láser' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="cirugia_venosa">
                                                Cirugía Venosa con Láser
                                            </label>
                                            <p class="text-muted small mt-2 mb-0">
                                                Manejo avanzado de patología venosa mediante técnicas mínimamente invasivas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card service-card {{ old('servicio_especifico') == 'Insuficiencia Renal' ? 'selected' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="servicio_especifico"
                                                   id="insuficiencia_renal"
                                                   value="Insuficiencia Renal"
                                                   {{ old('servicio_especifico') == 'Insuficiencia Renal' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="insuficiencia_renal">
                                                Insuficiencia Renal
                                            </label>
                                            <p class="text-muted small mt-2 mb-0">
                                                Creación de fístula arteriovenosa, colocación de catéter permanente y manejo de acceso para hemodiálisis.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card service-card {{ old('servicio_especifico') == 'Tratamiento de Várices' ? 'selected' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="servicio_especifico"
                                                   id="tratamiento_varices"
                                                   value="Tratamiento de Várices"
                                                   {{ old('servicio_especifico') == 'Tratamiento de Várices' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="tratamiento_varices">
                                                Tratamiento de Várices
                                            </label>
                                            <p class="text-muted small mt-2 mb-0">
                                                Procedimientos modernos y efectivos para la corrección de várices.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card service-card {{ old('servicio_especifico') == 'Termodiálisis' ? 'selected' : '' }}">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="radio"
                                                   name="servicio_especifico"
                                                   id="termodialisis"
                                                   value="Termodiálisis"
                                                   {{ old('servicio_especifico') == 'Termodiálisis' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="termodialisis">
                                                Termodiálisis
                                            </label>
                                            <p class="text-muted small mt-2 mb-0">
                                                Técnicas avanzadas para optimizar el tratamiento vascular.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('servicio_especifico')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- FECHA Y HORA -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               name="fecha"
                               class="form-control"
                               value="{{ old('fecha', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-clock me-1"></i>
                            Hora <span class="text-danger">*</span>
                        </label>
                        <input type="time"
                               name="hora"
                               class="form-control"
                               value="{{ old('hora', '09:00') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-hourglass-half me-1"></i>
                            Duración Estimada
                        </label>
                        <select name="duracion_minutos" class="form-select">
                            <option value="30" {{ old('duracion_minutos') == '30' ? 'selected' : '' }}>30 min (Consulta)</option>
                            <option value="60" {{ old('duracion_minutos') == '60' ? 'selected' : '' }}>1 hora (Evaluación)</option>
                            <option value="90" {{ old('duracion_minutos') == '90' ? 'selected' : '' }}>1.5 horas (Procedimiento menor)</option>
                            <option value="120" {{ old('duracion_minutos') == '120' ? 'selected' : '' }}>2 horas (Cirugía)</option>
                            <option value="180" {{ old('duracion_minutos') == '180' ? 'selected' : '' }}>3 horas (Cirugía compleja)</option>
                        </select>
                    </div>
                </div>

                <!-- PRIORIDAD Y NOTAS -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Prioridad
                        </label>
                        <select name="prioridad" class="form-select">
                            <option value="Normal" {{ old('prioridad') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Preferente" {{ old('prioridad') == 'Preferente' ? 'selected' : '' }}>🟡 Preferente</option>
                            <option value="Urgente" {{ old('prioridad') == 'Urgente' ? 'selected' : '' }}>🔴 Urgente</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-bold" style="color: #4a2c6d;">
                            <i class="fas fa-notes-medical me-1"></i>
                            Notas / Indicaciones
                        </label>
                        <textarea name="notas_previas"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Motivo de la consulta, indicaciones especiales...">{{ old('notas_previas') }}</textarea>
                    </div>
                </div>

                <!-- CHECKBOXES -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="requiere_ayuno"
                                   id="requiere_ayuno"
                                   value="1"
                                   {{ old('requiere_ayuno') ? 'checked' : '' }}>
                            <label class="form-check-label" for="requiere_ayuno">
                                Requiere ayuno
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="estudios_previos"
                                   id="estudios_previos"
                                   value="1"
                                   {{ old('estudios_previos') ? 'checked' : '' }}>
                            <label class="form-check-label" for="estudios_previos">
                                Traer estudios previos
                            </label>
                        </div>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn" style="background: #a97bc9; color: white;">
                        <i class="fas fa-calendar-check me-1"></i>
                        Agendar Procedimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .service-card {
        cursor: pointer;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        border-radius: 10px;
    }
    .service-card:hover {
        border-color: #a97bc9;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(169, 123, 201, 0.2);
    }
    .service-card.selected {
        border-color: #a97bc9;
        background-color: #f8f0ff;
    }
    .form-check-input:checked {
        background-color: #a97bc9;
        border-color: #a97bc9;
    }
    .btn {
        border-radius: 8px;
        padding: 8px 20px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            document.querySelectorAll('.service-card').forEach(c => {
                c.classList.remove('selected');
            });
            this.classList.add('selected');
        });
    });
</script>
@endpush
@endsection
