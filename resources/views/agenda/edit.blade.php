@extends('layouts.app')

@section('content')

    <style>
        :root {
            --primary-color: #0d47a1;
            --primary-dark: #002171;
            --primary-light: #e8f1fb;
            --primary-soft: #f4f8fd;
            --primary-border: #90caf9;
            --text-primary: #0a1a2f;
            --text-secondary: #1565c0;
        }

        body {
            background: var(--primary-soft);
        }

        .procedimiento-card {
            border-radius: 18px;
            background: var(--primary-light);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }

        .procedimiento-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 18px 18px 0 0;
            padding: 1.5rem;
        }

        .procedimiento-card h5 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-border);
            padding-bottom: 10px;
        }

        .procedimiento-card .form-label {
            font-weight: 500;
        }

        .procedimiento-card .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
        }

        .procedimiento-card .btn-outline-secondary {
            border-radius: 8px;
        }

        .procedimiento-card select.form-select,
        .procedimiento-card input.form-control,
        .procedimiento-card textarea.form-control {
            border-radius: 8px;
            border: 1px solid var(--primary-border);
        }

        .procedimiento-card .badge {
            background: var(--primary-light);
            color: var(--primary-dark);
        }
    </style>

    <div class="container my-4">
        <div class="card shadow-sm border-0 procedimiento-card">
            <!-- HEADER -->
            <div class="card-header border-0">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-edit me-2"></i>
                            Editar Cita
                        </h4>

                        <small class="text-white">
                            <i class="fas fa-user-md me-1"></i>
                            Dr. {{ Auth::user()->name }}
                        </small>
                    </div>

                    <div>
                        <span class="badge p-2">
                            <i class="far fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                        </span>
                    </div>

                </div>
            </div>

            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('citas.update', $cita->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- INFORMACIÓN PROCEDIMIENTO -->
                    <div class="row mb-4">

                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Información del Procedimiento
                            </h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Paciente <span class="text-danger">*</span></label>
                            <select name="paciente_id" class="form-select form-select-lg" required>
                                @foreach ($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}"
                                        {{ $paciente->id == $cita->paciente_id ? 'selected' : '' }}
                                        data-cedula="{{ $paciente->cedula }}" data-telefono="{{ $paciente->telefono }}">
                                        {{ $paciente->nombre }} {{ $paciente->apellido }} ({{ $paciente->cedula }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text" id="paciente-info"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                <i class="fas fa-procedures me-1" style="color: #a97bc9;"></i>
                                Tipo de Procedimiento <small class="text-muted">(Opcional)</small>
                            </label>
                            <select name="servicio_especifico" class="form-select form-select-lg">
                                <option value="">-- Seleccione --</option>
                                <option value="Cirugía de las Válvulas Cardiacas"
                                    {{ old('servicio_especifico') == 'Cirugía de las Válvulas Cardiacas' ? 'selected' : '' }}>
                                    Cirugía de las Válvulas Cardiacas
                                </option>
                                <option value="Cirugía Bypass Coronario"
                                    {{ old('servicio_especifico') == 'Cirugía Bypass Coronario' ? 'selected' : '' }}>
                                    Cirugía Bypass Coronario
                                </option>
                                <option value="Endarterectomía Carotídea"
                                    {{ old('servicio_especifico') == 'Endarterectomía Carotídea' ? 'selected' : '' }}>
                                    Endarterectomía Carotídea
                                </option>
                                <option value="Cirugía Venosa con Láser"
                                    {{ old('servicio_especifico') == 'Cirugía Venosa con Láser' ? 'selected' : '' }}>
                                    Cirugía Venosa con Láser
                                </option>
                                <option value="Insuficiencia Renal"
                                    {{ old('servicio_especifico') == 'Insuficiencia Renal' ? 'selected' : '' }}>
                                    Insuficiencia Renal
                                </option>
                                <option value="Aneurisma de Aorta Abdominal"
                                    {{ old('servicio_especifico') == 'Aneurisma de Aorta Abdominal' ? 'selected' : '' }}>
                                    Aneurisma de Aorta Abdominal
                                </option>
                                <option value="Angioplastia de Miembros Inferiores"
                                    {{ old('servicio_especifico') == 'Angioplastia de Miembros Inferiores' ? 'selected' : '' }}>
                                    Angioplastia de Miembros Inferiores
                                </option>
                                <option value="Tratamiento de Várices"
                                    {{ old('servicio_especifico') == 'Tratamiento de Várices' ? 'selected' : '' }}>
                                    Tratamiento de Várices
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

                    <!-- PROGRAMACIÓN -->
                    <div class="row mb-4">

                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3">
                                <i class="fas fa-clock me-2"></i>
                                Programación
                            </h5>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" class="form-control"
                                value="{{ \Carbon\Carbon::parse($cita->fecha)->format('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Hora <span class="text-danger">*</span></label>
                            <input type="time" name="hora" class="form-control" value="{{ $cita->hora }}" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Duración</label>
                            <select name="duracion_minutos" class="form-select">
                                <option value="30" {{ $cita->duracion_minutos == 30 ? 'selected' : '' }}>30</option>
                                <option value="45" {{ $cita->duracion_minutos == 45 ? 'selected' : '' }}>45</option>
                                <option value="60" {{ $cita->duracion_minutos == 60 ? 'selected' : '' }}>60</option>
                                <option value="90" {{ $cita->duracion_minutos == 90 ? 'selected' : '' }}>90</option>
                                <option value="120" {{ $cita->duracion_minutos == 120 ? 'selected' : '' }}>120</option>
                                <option value="180" {{ $cita->duracion_minutos == 180 ? 'selected' : '' }}>180</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Prioridad</label>
                            <select name="prioridad" class="form-select">
                                <option value="Normal" {{ $cita->prioridad == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="Preferente" {{ $cita->prioridad == 'Preferente' ? 'selected' : '' }}>
                                    Preferente</option>
                                <option value="Urgente" {{ $cita->prioridad == 'Urgente' ? 'selected' : '' }}>Urgente
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- INFORMACIÓN MÉDICA -->
                    <div class="row mb-4">

                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3">
                                <i class="fas fa-notes-medical me-2"></i>
                                Información Médica
                            </h5>
                        </div>

                        <!-- Motivo de Consulta -->
                        <!-- Motivo de Consulta -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                <i class="fas fa-question-circle me-1" style="color: #a97bc9;"></i>
                                Motivo de Consulta
                            </label>
                            <select name="motivo_consulta" class="form-select">
                                <option value="">-- Seleccione motivo --</option>
                                <option value="Primera evaluación"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Primera evaluación' ? 'selected' : '' }}>
                                    Primera evaluación</option>
                                <option value="Angina estable"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Angina estable' ? 'selected' : '' }}>
                                    Angina estable</option>
                                <option value="Angina inestable"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Angina inestable' ? 'selected' : '' }}>
                                    Angina inestable</option>
                                <option value="Estenosis valvular"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Estenosis valvular' ? 'selected' : '' }}>
                                    Estenosis valvular</option>
                                <option value="Insuficiencia valvular"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Insuficiencia valvular' ? 'selected' : '' }}>
                                    Insuficiencia valvular</option>
                                <option value="Estenosis carotídea"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Estenosis carotídea' ? 'selected' : '' }}>
                                    Estenosis carotídea</option>
                                <option value="AIT previo"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'AIT previo' ? 'selected' : '' }}>
                                    AIT previo</option>
                                <option value="ACV previo"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'ACV previo' ? 'selected' : '' }}>
                                    ACV previo</option>
                                <option value="Insuficiencia venosa crónica"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Insuficiencia venosa crónica' ? 'selected' : '' }}>
                                    Insuficiencia venosa crónica</option>
                                <option value="Várices sintomáticas"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Várices sintomáticas' ? 'selected' : '' }}>
                                    Várices sintomáticas</option>
                                <option value="Tromboflebitis superficial"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Tromboflebitis superficial' ? 'selected' : '' }}>
                                    Tromboflebitis superficial</option>
                                <option value="Control de función renal"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Control de función renal' ? 'selected' : '' }}>
                                    Control de función renal</option>
                                <option value="Hipertensión refractaria"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Hipertensión refractaria' ? 'selected' : '' }}>
                                    Hipertensión refractaria</option>
                                <option value="Diabetes con nefropatía"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Diabetes con nefropatía' ? 'selected' : '' }}>
                                    Diabetes con nefropatía</option>
                                <option value="Aneurisma en seguimiento"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Aneurisma en seguimiento' ? 'selected' : '' }}>
                                    Aneurisma en seguimiento</option>
                                <option value="Claudicación intermitente"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Claudicación intermitente' ? 'selected' : '' }}>
                                    Claudicación intermitente</option>
                                <option value="Isquemia crítica de miembro"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Isquemia crítica de miembro' ? 'selected' : '' }}>
                                    Isquemia crítica de miembro</option>
                                <option value="Úlcera varicosa o isquémica"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Úlcera varicosa o isquémica' ? 'selected' : '' }}>
                                    Úlcera varicosa o isquémica</option>
                                <option value="Post-infarto"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Post-infarto' ? 'selected' : '' }}>
                                    Post-infarto</option>
                                <option value="Control post-operatorio"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Control post-operatorio' ? 'selected' : '' }}>
                                    Control post-operatorio</option>
                                <option value="Preparación para cirugía"
                                    {{ old('motivo_consulta', $cita->motivo_consulta) == 'Preparación para cirugía' ? 'selected' : '' }}>
                                    Preparación para cirugía</option>
                            </select>
                        </div>

                        <!-- Síntomas Asociados -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="color: #4a2c6d;">
                                <i class="fas fa-thermometer-half me-1" style="color: #a97bc9;"></i>
                                Síntomas Asociados
                            </label>
                            <select name="tipo_consulta" class="form-select">
                                <option value="">-- Seleccione síntoma --</option>
                                <option value="Asintomático"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Asintomático' ? 'selected' : '' }}>Asintomático
                                </option>
                                <option value="Dolor torácico"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Dolor torácico' ? 'selected' : '' }}>Dolor
                                    torácico</option>
                                <option value="Disnea de esfuerzo"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Disnea de esfuerzo' ? 'selected' : '' }}>Disnea
                                    de esfuerzo</option>
                                <option value="Disnea en reposo"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Disnea en reposo' ? 'selected' : '' }}>Disnea
                                    en reposo</option>
                                <option value="Palpitaciones"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Palpitaciones' ? 'selected' : '' }}>
                                    Palpitaciones</option>
                                <option value="Fatiga"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Fatiga' ? 'selected' : '' }}>Fatiga</option>
                                <option value="Síncope"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Síncope' ? 'selected' : '' }}>Síncope</option>
                                <option value="Mareos"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Mareos' ? 'selected' : '' }}>Mareos</option>
                                <option value="Edema en miembros inferiores"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Edema en miembros inferiores' ? 'selected' : '' }}>
                                    Edema en miembros inferiores</option>
                                <option value="Cianosis"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Cianosis' ? 'selected' : '' }}>Cianosis
                                </option>
                                <option value="Soplo cardíaco"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Soplo cardíaco' ? 'selected' : '' }}>Soplo
                                    cardíaco</option>
                                <option value="Claudicación al caminar"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Claudicación al caminar' ? 'selected' : '' }}>
                                    Claudicación al caminar</option>
                                <option value="Dolor en reposo en extremidades"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Dolor en reposo en extremidades' ? 'selected' : '' }}>
                                    Dolor en reposo en extremidades</option>
                                <option value="Frialdad en extremidades"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Frialdad en extremidades' ? 'selected' : '' }}>
                                    Frialdad en extremidades</option>
                                <option value="Úlceras en pies o piernas"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Úlceras en pies o piernas' ? 'selected' : '' }}>
                                    Úlceras en pies o piernas</option>
                                <option value="Pesadez en piernas"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Pesadez en piernas' ? 'selected' : '' }}>
                                    Pesadez en piernas</option>
                                <option value="Calambres nocturnos"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Calambres nocturnos' ? 'selected' : '' }}>
                                    Calambres nocturnos</option>
                                <option value="Várices visibles"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Várices visibles' ? 'selected' : '' }}>Várices
                                    visibles</option>
                                <option value="Debilidad en extremidades"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Debilidad en extremidades' ? 'selected' : '' }}>
                                    Debilidad en extremidades</option>
                                <option value="Pérdida transitoria de visión"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Pérdida transitoria de visión' ? 'selected' : '' }}>
                                    Pérdida transitoria de visión</option>
                                <option value="Dificultad para hablar"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Dificultad para hablar' ? 'selected' : '' }}>
                                    Dificultad para hablar</option>
                                <option value="Náuseas y vómitos"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Náuseas y vómitos' ? 'selected' : '' }}>Náuseas
                                    y vómitos</option>
                                <option value="Prurito generalizado"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Prurito generalizado' ? 'selected' : '' }}>
                                    Prurito generalizado</option>
                                <option value="Disminución de orina"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Disminución de orina' ? 'selected' : '' }}>
                                    Disminución de orina</option>
                                <option value="Dolor abdominal pulsátil"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Dolor abdominal pulsátil' ? 'selected' : '' }}>
                                    Dolor abdominal pulsátil</option>
                                <option value="Masa abdominal palpable"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Masa abdominal palpable' ? 'selected' : '' }}>
                                    Masa abdominal palpable</option>
                                <option value="Hiperpigmentación en piernas"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Hiperpigmentación en piernas' ? 'selected' : '' }}>
                                    Hiperpigmentación en piernas</option>
                                <option value="Sudoración fría"
                                    {{ old('tipo_consulta', $cita->tipo_consulta) == 'Sudoración fría' ? 'selected' : '' }}>
                                    Sudoración fría</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Notas</label>
                            <textarea name="notas_previas" class="form-control" rows="3">{{ $cita->notas_previas }}</textarea>
                        </div>
                    </div>

                    <!-- REQUERIMIENTOS ESPECIALES -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3"
                                style="color: #4a2c6d; border-bottom: 2px solid #e9d5ff; padding-bottom: 10px;">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Requerimientos Especiales
                            </h5>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-3">
                                <h6 class="fw-bold mb-3" style="color: #4a2c6d;">Indicaciones para el paciente</h6>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="requiere_ayuno"
                                        id="requiere_ayuno" value="1" {{ $cita->requiere_ayuno ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requiere_ayuno">
                                        <i class="fas fa-utensils me-2 text-danger"></i>
                                        Requiere ayuno (8-12 horas)
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="estudios_previos"
                                        id="estudios_previos" value="1"
                                        {{ $cita->estudios_previos ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estudios_previos">
                                        <i class="fas fa-file-medical me-2 text-info"></i>
                                        Traer estudios previos
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 bg-light p-3">
                                <h6 class="fw-bold mb-3" style="color: #4a2c6d;">Recordatorios</h6>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="enviar_recordatorio"
                                        id="enviar_recordatorio" value="1" checked
                                        onchange="toggleRecordatorio(this)">
                                    <label class="form-check-label" for="enviar_recordatorio">
                                        <i class="fas fa-bell me-2 text-primary"></i>
                                        Enviar recordatorio por WhatsApp
                                    </label>
                                </div>

                                <div id="recordatorio_opciones" class="mt-2">
                                    <label class="form-label small fw-semibold" style="color:#4a2c6d;">
                                        <i class="fab fa-whatsapp me-1 text-success"></i>
                                        ¿Con cuánta anticipación?
                                    </label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach ([24 => '24 horas', 48 => '48 horas', 72 => '72 horas'] as $horas => $label)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="horas_recordatorio"
                                                    id="horas_{{ $horas }}" value="{{ $horas }}"
                                                    {{ $horas == 24 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="horas_{{ $horas }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="confirmar_asistencia"
                                        id="confirmar_asistencia" value="1"
                                        {{ $cita->confirmar_asistencia ?? false ? 'checked' : '' }}>
                                    <label class="form-check-label" for="confirmar_asistencia">
                                        <i class="fas fa-phone-alt me-2 text-success"></i>
                                        Requiere confirmación de asistencia
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Actualizar Cita
                        </button>
                        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-lg px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
    function toggleRecordatorio(checkbox) {
        document.getElementById('recordatorio_opciones').style.display = checkbox.checked ? 'block' : 'none';
    }
</script>
