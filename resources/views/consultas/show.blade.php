@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">
                Consulta del {{ $consulta->fecha_consulta }}
                | {{ $consulta->tipo_consulta }}
                | Dr. {{ $consulta->doctor->name }}

                <a href="{{ route('receta.pdf', $consulta) }}" target="_blank" class="btn btn-danger mb-3">
                    🧾 Generar Receta
                </a>
            </div>

            <div class="card-body">

                <p><strong>Motivo:</strong><br>
                    {{ $consulta->motivo_consulta }}
                </p>

                <p><strong>Enfermedad Actual:</strong><br>
                    {{ $consulta->enfermedad_actual }}
                </p>

                <p><strong>Plan:</strong><br>
                    {{ $consulta->plan }}
                </p>

                <p><strong>Observaciones:</strong><br>
                    {{ $consulta->observaciones }}
                </p>

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

                @forelse ($consulta->tratamientos as $tratamiento)
                    <div class="card mb-3">
                        <div class="card-header">
                            {{ $tratamiento->medicamento }}
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

                <div class="card card-body">

                    <form method="POST" action="{{ route('examen-fisico.store', $consulta) }}">
                        @csrf

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
                            Guardar Examen Físico
                        </button>

                    </form>
                    @if ($consulta->examenFisico)
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                🩺 Examen Físico Registrado
                            </div>
                            <div class="card-body">

                                <p><strong>Estado General:</strong><br>
                                    {{ $consulta->examenFisico->estado_general }}
                                </p>

                                <p><strong>Cabeza y Cuello:</strong><br>
                                    {{ $consulta->examenFisico->cabeza_cuello }}
                                </p>

                                <p><strong>Cardiovascular:</strong><br>
                                    {{ $consulta->examenFisico->cardiovascular }}
                                </p>

                                <p><strong>Respiratorio:</strong><br>
                                    {{ $consulta->examenFisico->respiratorio }}
                                </p>

                                <p><strong>Abdomen:</strong><br>
                                    {{ $consulta->examenFisico->abdomen }}
                                </p>

                                <p><strong>Extremidades:</strong><br>
                                    {{ $consulta->examenFisico->extremidades }}
                                </p>

                                <p><strong>Neurológico:</strong><br>
                                    {{ $consulta->examenFisico->neurologico }}
                                </p>

                                <p><strong>Otros:</strong><br>
                                    {{ $consulta->examenFisico->otros }}
                                </p>

                            </div>
                        </div>
                    @endif
                </div>
            </div>

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
