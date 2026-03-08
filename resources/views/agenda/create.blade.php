{{-- resources/views/agenda/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Agendar Procedimiento Cardiovascular</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>

    <body style="background:#f6f1fa;">

        <div class="container my-4">
            <div class="card shadow-sm border-0" style="border-radius: 18px; background: white;">

                <!-- HEADER CON GRADIENTE -->
                <div class="card-header border-0"
                    style="background: #0d47a1;
                    color: white;
                    border-radius: 18px 18px 0 0;
                    padding: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-heartbeat me-2"></i>
                                Agendar Citas
                            </h4>
                            <small class="opacity-75">
                                <i class="fas fa-user-md me-1"></i>
                                {{ Auth::user()->name }}
                            </small>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark p-2">
                                <i class="far fa-calendar me-1"></i>
                                {{ now()->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('citas.store') }}" id="citaForm">
                        @csrf

                        <!-- SECCIÓN 1: DATOS BÁSICOS -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-uppercase fw-bold mb-3"
                                    style="color: #4a2c6d; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px;">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Información del Procedimiento
                                </h5>
                            </div>

                            <!-- Paciente -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color:#4a2c6d;">
                                    <i class="fas fa-user me-1" style="color:#a97bc9;"></i>
                                    Paciente <span class="text-danger">*</span>
                                </label>

                                <input type="text" id="buscar_paciente" class="form-control form-control-lg"
                                    placeholder="Buscar por nombre, apellido o cédula..." autocomplete="off">

                                <input type="hidden" name="paciente_id" id="paciente_id">

                                <div id="lista_pacientes" class="list-group shadow-sm"></div>

                                <div class="form-text" id="paciente-info"></div>
                            </div>

                            <!-- Tipo de Procedimiento (SELECT) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-procedures me-1" style="color: #a97bc9;"></i>
                                    Tipo de Procedimiento <small class="text-muted">(Opcional)</small>
                                </label>
                                <select name="servicio_especifico" class="form-select form-select-lg">
                                    <option value="">-- Seleccione el procedimiento --</option>
                                    <option value="Cirugía de Corazón Abierto"
                                        {{ old('servicio_especifico') == 'Cirugía de Corazón Abierto' ? 'selected' : '' }}>
                                        Cirugía de Corazón Abierto
                                    </option>
                                    <option value="Cirugía Venosa con Láser"
                                        {{ old('servicio_especifico') == 'Cirugía Venosa con Láser' ? 'selected' : '' }}>
                                        Cirugía Venosa con Láser
                                    </option>
                                    <option value="Insuficiencia Renal"
                                        {{ old('servicio_especifico') == 'Insuficiencia Renal' ? 'selected' : '' }}>
                                        Insuficiencia Renal (Acceso vascular)
                                    </option>
                                    <option value="Tratamiento de Várices"
                                        {{ old('servicio_especifico') == 'Tratamiento de Várices' ? 'selected' : '' }}>
                                        Tratamiento de Várices
                                    </option>
                                    <option value="Termodiálisis"
                                        {{ old('servicio_especifico') == 'Termodiálisis' ? 'selected' : '' }}>
                                        Termodiálisis
                                    </option>
                                </select>
                                <div class="alert alert-info mt-2 py-2" id="procedimiento-descripcion"
                                    style="display: none; font-size: 0.9rem;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="descripcion-texto"></span>
                                </div>
                                @error('servicio_especifico')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN 2: FECHA Y HORA -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-uppercase fw-bold mb-3"
                                    style="color: #4a2c6d; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px;">
                                    <i class="fas fa-clock me-2"></i>
                                    Programación
                                </h5>
                            </div>

                            <!-- Fecha -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-calendar-alt me-1" style="color: #a97bc9;"></i>
                                    Fecha <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha" class="form-control"
                                    value="{{ old('fecha', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <!-- Hora -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-clock me-1" style="color: #a97bc9;"></i>
                                    Hora <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="hora" class="form-control"
                                    value="{{ old('hora', '09:00') }}" required>
                            </div>

                            <!-- Duración -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-hourglass-half me-1" style="color: #a97bc9;"></i>
                                    Duración Estimada
                                </label>
                                <select name="duracion_minutos" class="form-select" id="duracion_select">
                                    <option value="30" {{ old('duracion_minutos') == '30' ? 'selected' : '' }}>30 min
                                        (Consulta rápida)</option>
                                    <option value="45" {{ old('duracion_minutos') == '45' ? 'selected' : '' }}>45 min
                                        (Evaluación)</option>
                                    <option value="60" {{ old('duracion_minutos') == '60' ? 'selected' : '' }}>1 hora
                                        (Procedimiento menor)</option>
                                    <option value="90" {{ old('duracion_minutos') == '90' ? 'selected' : '' }}>1.5
                                        horas</option>
                                    <option value="120" {{ old('duracion_minutos') == '120' ? 'selected' : '' }}>2
                                        horas (Cirugía)</option>
                                    <option value="180" {{ old('duracion_minutos') == '180' ? 'selected' : '' }}>3
                                        horas (Cirugía compleja)</option>
                                    <option value="240" {{ old('duracion_minutos') == '240' ? 'selected' : '' }}>4
                                        horas (Cirugía mayor)</option>
                                </select>
                            </div>

                            <!-- Prioridad -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-exclamation-triangle me-1" style="color: #a97bc9;"></i>
                                    Prioridad
                                </label>
                                <select name="prioridad" class="form-select">
                                    <option value="Normal" {{ old('prioridad') == 'Normal' ? 'selected' : '' }}>🟢 Normal
                                    </option>
                                    <option value="Preferente" {{ old('prioridad') == 'Preferente' ? 'selected' : '' }}>🟡
                                        Preferente</option>
                                    <option value="Urgente" {{ old('prioridad') == 'Urgente' ? 'selected' : '' }}>🔴
                                        Urgente</option>
                                </select>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: INFORMACIÓN MÉDICA -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-uppercase fw-bold mb-3"
                                    style="color: #4a2c6d; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px;">
                                    <i class="fas fa-notes-medical me-2"></i>
                                    Información Médica <small class="text-muted">(Opcional)</small>
                                </h5>
                            </div>

                            <!-- Motivo de Consulta -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-question-circle me-1" style="color: #a97bc9;"></i>
                                    Motivo de Consulta
                                </label>
                                <select name="motivo_consulta" class="form-select">
                                    <option value="">-- Seleccione motivo --</option>
                                    <option value="Dolor torácico"
                                        {{ old('motivo_consulta') == 'Dolor torácico' ? 'selected' : '' }}>Dolor torácico
                                    </option>
                                    <option value="Disnea" {{ old('motivo_consulta') == 'Disnea' ? 'selected' : '' }}>
                                        Falta de aire (Disnea)</option>
                                    <option value="Palpitaciones"
                                        {{ old('motivo_consulta') == 'Palpitaciones' ? 'selected' : '' }}>Palpitaciones
                                    </option>
                                    <option value="Hipertensión"
                                        {{ old('motivo_consulta') == 'Hipertensión' ? 'selected' : '' }}>Hipertensión
                                        arterial</option>
                                    <option value="Edema" {{ old('motivo_consulta') == 'Edema' ? 'selected' : '' }}>Edema
                                        en miembros inferiores</option>
                                    <option value="Control post-operatorio"
                                        {{ old('motivo_consulta') == 'Control post-operatorio' ? 'selected' : '' }}>Control
                                        post-operatorio</option>
                                    <option value="Primera evaluación"
                                        {{ old('motivo_consulta') == 'Primera evaluación' ? 'selected' : '' }}>Primera
                                        evaluación</option>
                                </select>
                            </div>

                            <!-- Síntomas Asociados -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-thermometer-half me-1" style="color: #a97bc9;"></i>
                                    Síntomas Asociados
                                </label>
                                <select name="sintomas" class="form-select">
                                    <option value="">-- Seleccione síntoma --</option>
                                    <option value="Asintomático"
                                        {{ old('sintomas') == 'Asintomático' ? 'selected' : '' }}>Asintomático</option>
                                    <option value="Dolor en pecho"
                                        {{ old('sintomas') == 'Dolor en pecho' ? 'selected' : '' }}>Dolor en pecho</option>
                                    <option value="Mareos" {{ old('sintomas') == 'Mareos' ? 'selected' : '' }}>Mareos
                                    </option>
                                    <option value="Fatiga" {{ old('sintomas') == 'Fatiga' ? 'selected' : '' }}>Fatiga
                                    </option>
                                    <option value="Cianosis" {{ old('sintomas') == 'Cianosis' ? 'selected' : '' }}>
                                        Cianosis</option>
                                    <option value="Síncope" {{ old('sintomas') == 'Síncope' ? 'selected' : '' }}>Síncope
                                    </option>
                                </select>
                            </div>

                            <!-- Notas/Indicaciones -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                    <i class="fas fa-notes-medical me-1" style="color: #a97bc9;"></i>
                                    Notas / Indicaciones Especiales
                                </label>
                                <textarea name="notas_previas" class="form-control" rows="3"
                                    placeholder="Describa aquí el motivo detallado, indicaciones pre-operatorias, medicamentos que debe suspender, etc.">{{ old('notas_previas') }}</textarea>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: REQUERIMIENTOS ESPECIALES -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-uppercase fw-bold mb-3"
                                    style="color: #4a2c6d; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px;">
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    Requerimientos Especiales <small class="text-muted">(Opcional)</small>
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 bg-light p-3">
                                    <h6 class="fw-bold mb-3" style="color: #4a2c6d;">Indicaciones para el paciente</h6>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="requiere_ayuno"
                                            id="requiere_ayuno" value="1"
                                            {{ old('requiere_ayuno') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requiere_ayuno">
                                            <i class="fas fa-utensils me-2 text-danger"></i>
                                            Requiere ayuno (8-12 horas)
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="estudios_previos"
                                            id="estudios_previos" value="1"
                                            {{ old('estudios_previos') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="estudios_previos">
                                            <i class="fas fa-file-medical me-2 text-info"></i>
                                            Traer estudios previos
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="requiere_acompanante"
                                            id="requiere_acompanante" value="1"
                                            {{ old('requiere_acompanante') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requiere_acompanante">
                                            <i class="fas fa-users me-2 text-success"></i>
                                            Requiere acompañante
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="suspender_anticoagulantes"
                                            id="suspender_anticoagulantes" value="1"
                                            {{ old('suspender_anticoagulantes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="suspender_anticoagulantes">
                                            <i class="fas fa-pills me-2 text-warning"></i>
                                            Suspender anticoagulantes
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 bg-light p-3">
                                    <h6 class="fw-bold mb-3" style="color: #4a2c6d;">Recordatorios</h6>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="enviar_recordatorio"
                                            id="enviar_recordatorio" value="1"
                                            {{ old('enviar_recordatorio', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enviar_recordatorio">
                                            <i class="fas fa-bell me-2 text-primary"></i>
                                            Enviar recordatorio 24h antes
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="confirmar_asistencia"
                                            id="confirmar_asistencia" value="1"
                                            {{ old('confirmar_asistencia') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="confirmar_asistencia">
                                            <i class="fas fa-phone-alt me-2 text-success"></i>
                                            Requiere confirmación de asistencia
                                        </label>
                                    </div>

                                    <hr class="my-3">

                                    <div class="alert alert-warning py-2 mb-0" style="font-size: 0.9rem;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Nota:</strong> Los procedimientos quirúrgicos requieren evaluación
                                        pre-anestésica.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="submit" class="btn btn-lg px-5"
                                style="background: #0d47a1; color: white; border: none;">
                                <i class="fas fa-calendar-check me-2"></i>
                                Agendar
                            </button>
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .form-control:focus,
            .form-select:focus {
                border-color: #a97bc9;
                box-shadow: 0 0 0 0.2rem rgba(169, 123, 201, 0.25);
            }

            .btn-outline-secondary:hover {
                background: #f0e6fa;
                border-color: #a97bc9;
                color: #4a2c6d;
            }

            .form-check-input:checked {
                background-color: #a97bc9;
                border-color: #a97bc9;
            }

            .card {
                border: none;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Mostrar información del paciente seleccionado
                const pacienteSelect = document.querySelector('select[name="paciente_id"]');
                const pacienteInfo = document.getElementById('paciente-info');

                pacienteSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const cedula = selectedOption.getAttribute('data-cedula');
                    const telefono = selectedOption.getAttribute('data-telefono');

                    if (cedula) {
                        pacienteInfo.innerHTML =
                            `<i class="fas fa-id-card me-1"></i> Cédula: ${cedula} | <i class="fas fa-phone me-1"></i> Tel: ${telefono || 'No registrado'}`;
                    } else {
                        pacienteInfo.innerHTML = '';
                    }
                });

                // Trigger inicial si hay valor seleccionado
                if (procedimientoSelect.value) {
                    procedimientoSelect.dispatchEvent(new Event('change'));
                }
                if (pacienteSelect.value) {
                    pacienteSelect.dispatchEvent(new Event('change'));
                }
            });

            const inputPaciente = document.getElementById("buscar_paciente");
            const lista = document.getElementById("lista_pacientes");
            const pacienteId = document.getElementById("paciente_id");
            const pacienteInfo = document.getElementById("paciente-info");

            let debounceTimer;

            inputPaciente.addEventListener("keyup", function() {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {

                    let texto = inputPaciente.value.trim();

                    if (texto.length < 2) {
                        lista.innerHTML = "";
                        return;
                    }

                    fetch(`/buscar-pacientes?buscar=${encodeURIComponent(texto)}`)
                        .then(res => res.json())
                        .then(data => {

                            lista.innerHTML = "";

                            data.forEach(paciente => {

                                const item = document.createElement("a");
                                item.classList.add("list-group-item", "list-group-item-action");

                                item.innerHTML = `
                        <strong>${paciente.nombre} ${paciente.apellido}</strong><br>
                        <small>Cédula: ${paciente.cedula}</small>
                    `;

                                item.onclick = () => {

                                    inputPaciente.value = paciente.nombre + " " + paciente
                                        .apellido;
                                    pacienteId.value = paciente.id;

                                    pacienteInfo.innerHTML =
                                        `<i class="fas fa-id-card"></i> ${paciente.cedula}
                         <i class="fas fa-phone ms-2"></i> ${paciente.telefono ?? 'No registrado'}`;

                                    lista.innerHTML = "";
                                };

                                lista.appendChild(item);

                            });

                        });

                }, 300); // espera 300ms antes de buscar
            });
        </script>

    </body>

    </html>
@endsection
