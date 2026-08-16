@extends('layouts.app')

@section('content')
    <div class="container">
        @php
            $especialidad = auth()->user()->especialidad->slug ?? null;
        @endphp
        <style>
            .consulta-card {
                border-radius: 16px;
                border: none;
                background: #f4f8fd;
                box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
            }

            .consulta-header {
                background: #0d47a1;
                color: white;
                border-radius: 16px 16px 0 0;
                padding: 16px 20px;
            }

            .consulta-header h6 {
                margin: 0;
                font-weight: 600;
            }

            .consulta-meta {
                font-size: 14px;
                opacity: .9;
            }

            .consulta-section {
                margin-bottom: 18px;
                padding-bottom: 12px;
                border-bottom: 1px solid #e3ecf7;
            }

            .consulta-section:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }

            .consulta-label {
                font-weight: 600;
                color: #0d47a1;
                margin-bottom: 4px;
            }

            .consulta-text {
                color: #1a2b3c;
                line-height: 1.5;
            }

            .btn-receta {
                background: #e8f1fb;
                border: none;
                border-radius: 8px;
                font-size: 14px;
            }

            .btn-receta:hover {
                background: #4caf50;
            }
        </style>

        <div class="card consulta-card mb-4">

            <div class="consulta-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h6>
                        Consulta Médica
                    </h6>

                    <div class="consulta-meta">
                        {{ $consulta->fecha_consulta }}
                        | {{ $consulta->tipo_consulta }}
                        | {{ $consulta->doctor->name }}
                    </div>
                </div>

                <a href="{{ route('receta.pdf', $consulta) }}" target="_blank" class="btn btn-receta btn-sm">
                    🧾 Generar Receta
                </a>
            </div>

            <div class="card-body">

                <div class="consulta-section">
                    <div class="consulta-label">Motivo de Consulta</div>
                    <div class="consulta-text">
                        {{ $consulta->motivo_consulta }}
                    </div>
                </div>

                <div class="consulta-section">
                    <div class="consulta-label">Enfermedad Actual</div>
                    <div class="consulta-text">
                        {{ $consulta->enfermedad_actual }}
                    </div>
                </div>

                <div class="consulta-section">
                    <div class="consulta-label">Plan de Tratamiento</div>
                    <div class="consulta-text">
                        {{ $consulta->plan }}
                    </div>
                </div>

                <div class="consulta-section">
                    <div class="consulta-label">Observaciones</div>
                    <div class="consulta-text">
                        {!! nl2br(
                            e(
                                preg_replace(
                                    '/\n+/',
                                    "\n",
                                    trim(str_replace(['\\n', '\\r', '\\t'], "\n", strip_tags($consulta->observaciones ?? 'No registrado'))),
                                ),
                            ),
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS INTERNOS DE LA CONSULTA -->

        <ul class="nav nav-tabs">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#estudios">
                    Estudios
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#diagnosticos">
                    Diagnósticos
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tratamientos">
                    Medicación
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#procedimientos">
                    Procedimientos
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#signos">
                    Signos Vitales
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#examen">
                    Evaluación Física
                </button>
            </li>

            @if ($especialidad === 'dermatologia')
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dermatologia">
                        Dermatología
                    </button>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#evolucion">
                    Evolución
                </a>
            </li>
        </ul>

        <div class="tab-content p-4 border border-top-0">

            <!--ESTUDIOS-->
            <div class="tab-pane fade show active" id="estudios">
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formEstudio">
                    ➕ Registrar Estudio
                </button>

                <div class="collapse mb-4" id="formEstudio">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('estudios.store', $consulta) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label>Tipo de Estudio</label>
                                <input type="text" name="tipo_estudio" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Fecha</label>
                                <input type="date" name="fecha_estudio" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Resultado</label>
                                <textarea name="resultado" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Estado</label>
                                <select name="estado" class="form-control" required>
                                    <option value="indicado">Indicado</option>
                                    <option value="realizado">Realizado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Archivo</label>
                                <input type="file" name="archivo" class="form-control">
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar Estudio
                            </button>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>

                    </div>
                </div>

                @forelse ($consulta->estudios as $estudio)
                    <div class="card mb-3">
                        <div class="card-header">
                            {{ $estudio->fecha_estudio }}
                            - {{ $estudio->tipo_estudio }}
                        </div>
                        <div class="card-body">

                            <p>{{ $estudio->resultado }}</p>

                            @if ($estudio->archivo)
                                <a href="{{ route('estudios.descargar', $estudio) }}" class="btn btn-sm btn-primary">
                                    Descargar archivo
                                </a>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary">
                        No hay estudios registrados.
                    </div>
                @endforelse

            </div>

            <!-- SIGNOS VITALES -->
            <div class="tab-pane fade" id="signos">
                <div class="card card-body">

                    <form method="POST" action="{{ route('signos-vitales.store', $consulta) }}">
                        @csrf

                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label>Presión Arterial</label>
                                <div class="input-group">
                                    <input type="number" name="presion_sistolica" class="form-control"
                                        placeholder="Sistólica">
                                    <span class="input-group-text">/</span>
                                    <input type="number" name="presion_diastolica" class="form-control"
                                        placeholder="Diastólica">
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Frecuencia Cardíaca</label>
                                <input type="number" name="frecuencia_cardiaca" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Temperatura</label>
                                <input type="number" step="0.1" name="temperatura" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Saturación O₂</label>
                                <input type="number" name="saturacion_oxigeno" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Peso (kg)</label>
                                <input type="number" step="0.01" name="peso" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Talla (m)</label>
                                <input type="number" step="0.01" name="talla" class="form-control">
                            </div>

                        </div>

                        <button class="btn btn-success w-100">
                            Guardar Signos Vitales
                        </button>

                    </form>
                    @if ($consulta->signoVital)
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                📊 Signos Vitales Registrados
                            </div>
                            <div class="card-body">

                                <p><strong>Presión Arterial:</strong>
                                    {{ $consulta->signoVital->presion_sistolica }}
                                    /
                                    {{ $consulta->signoVital->presion_diastolica }} mmHg
                                </p>

                                <p><strong>Frecuencia Cardíaca:</strong>
                                    {{ $consulta->signoVital->frecuencia_cardiaca }} lpm
                                </p>

                                <p><strong>Frecuencia Respiratoria:</strong>
                                    {{ $consulta->signoVital->frecuencia_respiratoria }} rpm
                                </p>

                                <p><strong>Temperatura:</strong>
                                    {{ $consulta->signoVital->temperatura }} °C
                                </p>

                                <p><strong>Saturación O₂:</strong>
                                    {{ $consulta->signoVital->saturacion_oxigeno }} %
                                </p>

                                <p><strong>Peso:</strong>
                                    {{ $consulta->signoVital->peso }} kg
                                </p>

                                <p><strong>Talla:</strong>
                                    {{ $consulta->signoVital->talla }} m
                                </p>

                                <p><strong>IMC:</strong>
                                    {{ number_format($consulta->signoVital->imc, 2) }}
                                </p>

                            </div>
                        </div>
                    @endif
                </div>

                @if ($consulta->signoVital)
                    <div class="alert alert-info mt-3">
                        IMC calculado: {{ number_format($consulta->signoVital->imc, 2) }}
                    </div>
                @endif

            </div>

            <!-- DIAGNÓSTICOS -->
            <div class="tab-pane fade" id="diagnosticos">
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formDiagnostico">
                    ➕ Registrar Diagnóstico
                </button>

                <div class="collapse mb-4" id="formDiagnostico">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('diagnosticos.store', $consulta) }}">
                            @csrf

                            <div class="mb-3">
                                <label>Diagnóstico</label>
                                <textarea name="diagnostico" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Tipo</label>
                                <select name="tipo" class="form-control" required>
                                    <option value="">--Seleccione--</option>
                                    <option value="presuntivo">Presuntivo</option>
                                    <option value="definitivo">Definitivo</option>
                                    <option value="diferencial">Diferencial</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Código CIE-10</label>
                                <input type="text" name="codigo_cie10" class="form-control">
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar Diagnóstico
                            </button>
                        </form>

                    </div>
                </div>

                @forelse ($consulta->diagnosticos as $diagnostico)
                    <div class="card mb-3">
                        <div class="card-header">
                            {{ $diagnostico->tipo }}
                            @if ($diagnostico->codigo_cie10)
                                | CIE-10: {{ $diagnostico->codigo_cie10 }}
                            @endif
                        </div>
                        <div class="card-body">
                            <p>{{ $diagnostico->diagnostico }}</p>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary">
                        No hay diagnósticos registrados.
                    </div>
                @endforelse

            </div>

            <!-- TRATAMIENTOS -->
            <div class="tab-pane fade" id="tratamientos">
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formTratamiento">
                    ➕ Registrar Tratamiento
                </button>

                <div class="collapse mb-4" id="formTratamiento">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('tratamientos.store', $consulta) }}">
                            @csrf

                            <div class="mb-3">
                                <label>Medicamento</label>
                                <input type="text" name="medicamento" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Dosis</label>
                                    <input type="text" name="dosis" class="form-control" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Frecuencia</label>
                                    <input type="text" name="frecuencia" class="form-control"
                                        placeholder="Ej: Cada 8 horas">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Duración</label>
                                    <input type="text" name="duracion" class="form-control" placeholder="Ej: 7 días">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Vía de administración</label>
                                <select name="via_administracion" class="form-control" required>
                                    <option value="">--Seleccione--</option>
                                    <option value="oral">Oral</option>
                                    <option value="intravenosa">Intravenosa</option>
                                    <option value="intramuscular">Intramuscular</option>
                                    <option value="subcutanea">Subcutánea</option>
                                    <option value="topica">Tópica</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Indicaciones adicionales</label>
                                <textarea name="indicaciones" class="form-control"></textarea>
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar Tratamiento
                            </button>

                        </form>

                    </div>
                </div>

                @forelse ($consulta->tratamientos->sortByDesc('created_at') as $tratamiento)
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $tratamiento->medicamento }}</strong>
                                <small class="text-muted">
                                    {{ $tratamiento->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>Dosis:</strong> {{ $tratamiento->dosis }}</p>
                            <p><strong>Frecuencia:</strong> {{ $tratamiento->frecuencia }}</p>
                            <p><strong>Duración:</strong> {{ $tratamiento->duracion }}</p>
                            <p><strong>Vía:</strong> {{ $tratamiento->via_administracion }}</p>

                            @if ($tratamiento->indicaciones)
                                <p><strong>Indicaciones:</strong><br>
                                    {{ $tratamiento->indicaciones }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary">
                        No hay tratamientos registrados.
                    </div>
                @endforelse

            </div>

            <!-- PROCEDIMIENTOS -->
            <div class="tab-pane fade" id="procedimientos">
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formProcedimiento">
                    ➕ Registrar Procedimiento
                </button>

                <div class="collapse mb-4" id="formProcedimiento">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('procedimientos.store', $consulta) }}">
                            @csrf

                            <div class="mb-3">
                                <label>Nombre del Procedimiento</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Tipo</label>
                                <input type="text" name="tipo" class="form-control"
                                    placeholder="Ej: Quirúrgico, Ambulatorio">
                            </div>

                            <div class="mb-3">
                                <label>Fecha</label>
                                <input type="date" name="fecha" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Estado</label>
                                <select name="estado" class="form-control" required>
                                    <option value="programado">Programado</option>
                                    <option value="realizado">Realizado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Resultado</label>
                                <textarea name="resultado" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Complicaciones</label>
                                <textarea name="complicaciones" class="form-control"></textarea>
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar Procedimiento
                            </button>

                        </form>

                    </div>
                </div>

                @forelse ($consulta->procedimientos as $procedimiento)
                    <div class="card mb-3">
                        <div class="card-header">
                            {{ $procedimiento->fecha }} - {{ $procedimiento->nombre }}
                            | {{ ucfirst($procedimiento->estado) }}
                        </div>
                        <div class="card-body">

                            @if ($procedimiento->descripcion)
                                <p><strong>Descripción:</strong><br>
                                    {{ $procedimiento->descripcion }}
                                </p>
                            @endif

                            @if ($procedimiento->resultado)
                                <p><strong>Resultado:</strong><br>
                                    {{ $procedimiento->resultado }}
                                </p>
                            @endif

                            @if ($procedimiento->complicaciones)
                                <p><strong>Complicaciones:</strong><br>
                                    {{ $procedimiento->complicaciones }}
                                </p>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary">
                        No hay procedimientos registrados.
                    </div>
                @endforelse

            </div>

            <!-- EXAMEN FÍSICO -->
            <div class="tab-pane fade" id="examen">
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formExamen">
                    ➕ Registrar Examen Físico
                </button>

                <!-- FORMULARIO COLAPSABLE -->
                <div class="collapse mb-4" id="formExamen">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('examen-fisico.store', $consulta) }}">
                            @csrf

                            @if ($especialidad === 'ginecologia')
                                <hr>
                                <h6 class="fw-bold text-danger">🌸 Examen Físico Ginecológico</h6>

                                <div class="mb-3">
                                    <label>Genitales externos</label>
                                    <textarea name="genitales_externos" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Especuloscopía</label>
                                    <textarea name="especuloscopia" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Tacto vaginal (bimanual)</label>
                                    <textarea name="tacto_vaginal" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Flujo vaginal</label>
                                    <input type="text" name="flujo_vaginal" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Dolor pélvico</label>
                                    <select name="dolor_pelvico" class="form-control">
                                        <option value="">--Seleccione--</option>
                                        <option value="no">No</option>
                                        <option value="leve">Leve</option>
                                        <option value="moderado">Moderado</option>
                                        <option value="severo">Severo</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Hallazgos ginecológicos</label>
                                    <textarea name="hallazgos_gineco" class="form-control"></textarea>
                                </div>
                            @endif

                            <hr>

                            <div class="mb-3">
                                <label>Estado General</label>
                                <textarea name="estado_general" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Cabeza y Cuello</label>
                                <textarea name="cabeza_cuello" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Sistema Cardiovascular</label>
                                <textarea name="cardiovascular" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Sistema Respiratorio</label>
                                <textarea name="respiratorio" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Abdomen</label>
                                <textarea name="abdomen" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Extremidades</label>
                                <textarea name="extremidades" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Neurológico</label>
                                <textarea name="neurologico" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Otros Hallazgos</label>
                                <textarea name="otros" class="form-control"></textarea>
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar
                            </button>

                        </form>

                    </div>
                </div>

                <!-- LISTADO -->
                @if ($consulta->examenFisico)
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            🩺 Examen Físico Registrado
                        </div>

                        <div class="card-body">

                            @if ($especialidad === 'ginecologia')
                                <h6 class="fw-bold text-danger">🌸 Ginecológico</h6>

                                <p><strong>Genitales externos:</strong>
                                    {{ $consulta->examenFisico->genitales_externos ?? '-' }}</p>
                                <p><strong>Especuloscopía:</strong> {{ $consulta->examenFisico->especuloscopia ?? '-' }}
                                </p>
                                <p><strong>Tacto vaginal:</strong> {{ $consulta->examenFisico->tacto_vaginal ?? '-' }}</p>
                                <p><strong>Flujo vaginal:</strong> {{ $consulta->examenFisico->flujo_vaginal ?? '-' }}</p>
                                <p><strong>Dolor pélvico:</strong>
                                    {{ ucfirst($consulta->examenFisico->dolor_pelvico ?? '-') }}</p>
                                <p><strong>Hallazgos:</strong> {{ $consulta->examenFisico->hallazgos_gineco ?? '-' }}</p>

                                <hr>
                            @endif

                            <p><strong>Estado General:</strong> {{ $consulta->examenFisico->estado_general }}</p>
                            <p><strong>Cabeza y Cuello:</strong> {{ $consulta->examenFisico->cabeza_cuello }}</p>
                            <p><strong>Cardiovascular:</strong> {{ $consulta->examenFisico->cardiovascular }}</p>
                            <p><strong>Respiratorio:</strong> {{ $consulta->examenFisico->respiratorio }}</p>
                            <p><strong>Abdomen:</strong> {{ $consulta->examenFisico->abdomen }}</p>
                            <p><strong>Extremidades:</strong> {{ $consulta->examenFisico->extremidades }}</p>
                            <p><strong>Neurológico:</strong> {{ $consulta->examenFisico->neurologico }}</p>
                            <p><strong>Otros:</strong> {{ $consulta->examenFisico->otros }}</p>

                        </div>
                    </div>
                @endif

            </div>

            @if ($especialidad === 'dermatologia' && $consulta->dermatologia)
                <div class="tab-pane fade" id="dermatologia">
                    <div class="card card-body mb-4">
                        <h6 class="fw-bold text-danger mb-3">🩺 Evaluación Dermatológica</h6>

                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <strong>Fototipo:</strong><br>{{ $consulta->dermatologia->fototipo_fitzpatrick ?? '-' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Tipo de piel:</strong><br>{{ $consulta->dermatologia->tipo_piel ?? '-' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Diagnóstico:</strong><br>{{ $consulta->dermatologia->diagnostico_dermatologico ?? '-' }}
                            </div>

                            @if ($consulta->dermatologia->lesion_tipo)
                                <div class="col-md-12">
                                    <hr><small class="text-muted">Lesión</small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Tipo:</strong><br>{{ $consulta->dermatologia->lesion_tipo }}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Localización:</strong><br>{{ $consulta->dermatologia->lesion_localizacion ?? '-' }}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Tamaño:</strong><br>{{ $consulta->dermatologia->lesion_tamano ?? '-' }}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Color:</strong><br>{{ $consulta->dermatologia->lesion_color ?? '-' }}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Bordes:</strong><br>{{ $consulta->dermatologia->lesion_bordes ?? '-' }}
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Evolución:</strong><br>{{ $consulta->dermatologia->tiempo_evolucion ?? '-' }}
                                </div>
                                <div class="col-md-12 mb-2">
                                    <strong>Síntomas:</strong><br>
                                    {{ collect(['prurito' => 'Prurito', 'dolor' => 'Dolor', 'ardor' => 'Ardor', 'sangrado' => 'Sangrado'])->filter(fn($l, $k) => $consulta->dermatologia->$k)->implode(', ') ?:
                                        'Ninguno' }}
                                </div>
                            @endif

                            @if ($consulta->dermatologia->dermatoscopia_realizada || $consulta->dermatologia->biopsia_realizada)
                                <div class="col-md-12">
                                    <hr><small class="text-muted">Estudios</small>
                                </div>
                                @if ($consulta->dermatologia->dermatoscopia_realizada)
                                    <div class="col-md-6 mb-2">
                                        <strong>Dermatoscopia:</strong><br>{{ $consulta->dermatologia->hallazgos_dermatoscopia ?? '-' }}
                                    </div>
                                @endif
                                @if ($consulta->dermatologia->biopsia_realizada)
                                    <div class="col-md-6 mb-2">
                                        <strong>Biopsia:</strong><br>{{ $consulta->dermatologia->resultado_biopsia ?? '-' }}
                                    </div>
                                @endif
                            @endif

                            @if ($consulta->dermatologia->es_procedimiento_estetico)
                                <div class="col-md-12">
                                    <hr><small class="text-muted">Procedimiento Estético</small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <strong>Procedimiento:</strong><br>{{ $consulta->dermatologia->procedimiento_estetico ?? '-' }}
                                </div>
                                <div class="col-md-4 mb-2"><strong>Zona
                                        tratada:</strong><br>{{ $consulta->dermatologia->zona_tratada ?? '-' }}</div>
                                <div class="col-md-4 mb-2">
                                    <strong>Producto / cantidad:</strong><br>
                                    {{ $consulta->dermatologia->producto_utilizado ?? '-' }} —
                                    {{ $consulta->dermatologia->cantidad_aplicada ?? '-' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 👇 CONTROL FOTOGRÁFICO --}}
                    <div class="card card-body">
                        <h6 class="fw-bold text-danger mb-3">📸 Control Fotográfico</h6>

                        <div class="row">
                            @foreach (['antes' => 'Antes', 'durante' => 'Durante', 'despues' => 'Después'] as $key => $label)
                                <div class="col-md-4 mb-3">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $label }}</strong>
                                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#subirFoto{{ $key }}{{ $consulta->dermatologia->id }}">
                                            + Agregar
                                        </button>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2 p-2 bg-light rounded"
                                        style="min-height:100px;">
                                        @forelse ($consulta->dermatologia->fotos->where('etapa', $key)->sortBy('orden') as $foto)
                                            <div class="position-relative">
                                                <img src="{{ $foto->url }}" class="rounded border"
                                                    style="width:90px;height:90px;object-fit:cover;cursor:pointer;"
                                                    data-bs-toggle="modal" data-bs-target="#verFoto{{ $foto->id }}"
                                                    alt="{{ $foto->descripcion }}">

                                                <form action="{{ route('procedimiento-fotos.destroy', $foto) }}"
                                                    method="POST" class="position-absolute top-0 end-0 m-1"
                                                    onsubmit="return confirm('¿Eliminar esta foto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-danger p-0 d-flex align-items-center justify-content-center"
                                                        style="width:18px;height:18px;font-size:10px;border-radius:50%;line-height:1;">
                                                        ✕
                                                    </button>
                                                </form>

                                                {{-- Modal para ver la foto en grande --}}
                                                <div class="modal fade" id="verFoto{{ $foto->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <span class="badge bg-danger">{{ $label }}</span>
                                                                <small class="text-muted ms-2">
                                                                    {{ $foto->created_at->format('d/m/Y H:i') }}
                                                                </small>
                                                                <button type="button" class="btn-close ms-auto"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <img src="{{ $foto->url }}" class="img-fluid">
                                                            @if ($foto->descripcion)
                                                                <div class="modal-body">
                                                                    <p class="mb-0">{{ $foto->descripcion }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <small class="text-muted align-self-center mx-auto">Sin fotos</small>
                                        @endforelse
                                    </div>

                                    <div class="collapse"
                                        id="subirFoto{{ $key }}{{ $consulta->dermatologia->id }}">
                                        <form
                                            action="{{ route('procedimiento-fotos.store', $consulta->dermatologia->id) }}"
                                            method="POST" enctype="multipart/form-data"
                                            class="card card-body bg-white border">
                                            @csrf
                                            <input type="hidden" name="etapa" value="{{ $key }}">

                                            <div class="mb-2">
                                                <input type="file" name="fotos[]" class="form-control form-control-sm"
                                                    multiple accept="image/*" required>
                                            </div>

                                            <div class="mb-2">
                                                <input type="text" name="descripcion"
                                                    class="form-control form-control-sm"
                                                    placeholder="Descripción (opcional)">
                                            </div>

                                            <button class="btn btn-sm btn-primary w-100">Subir</button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        @if ($consulta->dermatologia->zonaCorporal)
                            <a href="{{ route('dermatologia.linea-tiempo-zona', [$consulta->paciente_id, $consulta->dermatologia->zona_corporal_id]) }}"
                                class="btn btn-outline-secondary btn-sm mt-2 w-100">
                                Línea de Tiempo de esta Zona
                            </a>
                        @endif
                        @if ($consulta->dermatologia->fotosAntes()->exists() && $consulta->dermatologia->fotosDespues()->exists())
                            <a href="{{ route('dermatologia.comparar', $consulta->dermatologia->id) }}"
                                class="btn btn-outline-primary btn-sm mt-2 w-100">
                                Ver Comparación Antes / Después
                            </a>
                        @else
                            <small class="text-muted d-block mt-2">
                                Sube al menos una foto de "Antes" y una de "Después" para habilitar la comparación.
                            </small>
                        @endif
                    </div>
                </div>
            @endif

            <div class="tab-pane fade" id="evolucion">

                <div class="card card-body">

                    <form method="POST" action="{{ route('evoluciones.store', $consulta) }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nota de Evolución</label>
                            <textarea name="nota" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Plan / Conducta</label>
                            <textarea name="plan" class="form-control" rows="3"></textarea>
                        </div>

                        <button class="btn btn-primary w-100">
                            Guardar Evolución
                        </button>

                    </form>

                </div>

                {{-- HISTORIAL --}}
                @if ($consulta->evoluciones->count())
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            Historial de Evoluciones
                        </div>
                        <div class="card-body">

                            @foreach ($consulta->evoluciones->sortByDesc('created_at') as $evolucion)
                                <div class="mb-4 border-bottom pb-3">

                                    <strong>
                                        {{ $evolucion->created_at->format('d/m/Y H:i') }}
                                    </strong>

                                    <p class="mt-2">
                                        <strong>Nota:</strong><br>
                                        {{ $evolucion->nota }}
                                    </p>

                                    @if ($evolucion->plan)
                                        <p>
                                            <strong>Plan:</strong><br>
                                            {{ $evolucion->plan }}
                                        </p>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
