@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white">
                Consulta del {{ $consulta->fecha_consulta }}
                | {{ $consulta->tipo_consulta }}
                | Dr. {{ $consulta->doctor->name }}
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
                    Tratamientos
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#procedimientos">
                    Procedimientos
                </button>
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
        </div>
    </div>
@endsection
