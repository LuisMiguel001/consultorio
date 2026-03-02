@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- INFORMACIÓN GENERAL -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h5 class="mb-0">
                    Ficha Clínica - {{ $paciente->nombre }} {{ $paciente->apellido }}
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <strong>Cédula:</strong> {{ $paciente->cedula }}
                    </div>

                    <div class="col-md-4">
                        <strong>Teléfono:</strong> {{ $paciente->telefono }}
                    </div>

                    <div class="col-md-4">
                        <strong>Dirección:</strong> {{ $paciente->direccion }}
                    </div>

                </div>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs" id="tabsPaciente" role="tablist">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#consultas">
                    Consultas
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#antecedentes">
                    Antecedentes
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#estudios">
                    Estudios
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

            <!-- CONSULTAS -->
            <div class="tab-pane fade show active" id="consultas">
                <a href="{{ route('consultas.create', $paciente->id) }}" class="btn btn-light btn-sm">
                    ➕ Nueva Consulta
                </a>
                @foreach ($paciente->consultas as $consulta)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            {{ $consulta->fecha_consulta }}
                            | {{ $consulta->tipo_consulta }}
                            | Dr. {{ $consulta->doctor->name }}
                        </div>

                        <div class="card-body">
                            <strong>Motivo:</strong>
                            <p>{{ $consulta->motivo_consulta }}</p>

                            <strong>Enfermedad actual:</strong>
                            <p>{{ $consulta->enfermedad_actual }}</p>

                            <strong>Plan:</strong>
                            <p>{{ $consulta->plan }}</p>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- ANTECEDENTES -->
            <div class="tab-pane fade" id="antecedentes">

                <!-- BOTÓN NUEVO -->
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formAntecedente">
                    ➕ Registrar Antecedentes
                </button>

                <!-- FORMULARIO -->
                <div class="collapse mb-4" id="formAntecedente">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('antecedentes.store', $paciente->id) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Antecedentes Personales</label>
                                <textarea name="antecedentes_personales" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Antecedentes Familiares</label>
                                <textarea name="antecedentes_familiares" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Antecedentes Quirúrgicos</label>
                                <textarea name="antecedentes_quirurgicos" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alergias</label>
                                <textarea name="alergias" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Medicamentos Actuales</label>
                                <textarea name="medicamentos_actuales" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hábitos</label>
                                <textarea name="habitos" class="form-control"></textarea>
                            </div>

                            <button class="btn btn-success w-100">
                                Guardar Antecedentes
                            </button>

                        </form>
                    </div>
                </div>

                <!-- HISTORIAL -->
                @forelse ($paciente->antecedentes->sortByDesc('created_at') as $ant)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between">
                            <span>
                                {{ $ant->created_at->format('d/m/Y H:i') }}
                            </span>

                            <small>
                                Dr. {{ $ant->usuario->name }}
                            </small>
                        </div>

                        <div class="card-body">

                            @if ($ant->antecedentes_personales)
                                <p><strong>Personales:</strong><br>
                                    {{ $ant->antecedentes_personales }}
                                </p>
                            @endif

                            @if ($ant->antecedentes_familiares)
                                <p><strong>Familiares:</strong><br>
                                    {{ $ant->antecedentes_familiares }}
                                </p>
                            @endif

                            @if ($ant->antecedentes_quirurgicos)
                                <p><strong>Quirúrgicos:</strong><br>
                                    {{ $ant->antecedentes_quirurgicos }}
                                </p>
                            @endif

                            @if ($ant->alergias)
                                <p><strong>Alergias:</strong><br>
                                    {{ $ant->alergias }}
                                </p>
                            @endif

                            @if ($ant->medicamentos_actuales)
                                <p><strong>Medicamentos:</strong><br>
                                    {{ $ant->medicamentos_actuales }}
                                </p>
                            @endif

                            @if ($ant->habitos)
                                <p><strong>Hábitos:</strong><br>
                                    {{ $ant->habitos }}
                                </p>
                            @endif

                        </div>
                    </div>

                @empty
                    <div class="alert alert-secondary">
                        No hay antecedentes registrados.
                    </div>
                @endforelse

            </div>

            <!-- ESTUDIOS -->
            <div class="tab-pane fade" id="estudios">

                <!-- BOTÓN NUEVO -->
                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formEstudio">
                    ➕ Registrar Estudio
                </button>

                <!-- FORMULARIO -->
                <div class="collapse mb-4" id="formEstudio">
                    <div class="card card-body">

                        <form method="POST" action="{{ route('estudios.store', $paciente) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tipo de Estudio</label>
                                <select name="tipo_estudio" class="form-control" required>
                                    <option value="">-- Seleccione --</option>
                                    <option value="Laboratorio">Laboratorio</option>
                                    <option value="Rayos X">Rayos X</option>
                                    <option value="Ecografía">Ecografía</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Resultado / Observaciones</label>
                                <textarea name="resultado" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Médico que solicita</label>
                                <input type="text" name="medico" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Archivo adjunto (opcional)</label>
                                <input type="file" name="archivo" class="form-control">
                            </div>

                            <button class="btn btn-success w-100">Guardar Estudio</button>
                        </form>

                    </div>
                </div>

                <!-- LISTADO DE ESTUDIOS -->
                @forelse ($paciente->estudios->sortByDesc('fecha') as $estudio)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between">
                            <span>{{ \Carbon\Carbon::parse($estudio->fecha)->format('d/m/Y') }} -
                                {{ $estudio->tipo_estudio }}</span>
                            <small>Dr. {{ $estudio->medico }}</small>
                        </div>
                        <div class="card-body">
                            @if ($estudio->resultado)
                                <p><strong>Resultado:</strong><br>{{ $estudio->resultado }}</p>
                            @endif

                            @if ($estudio->archivo)
                                <a href="{{ route('estudios.descargar', $estudio->id) }}" class="btn btn-sm btn-primary">
                                    📄 Descargar archivo
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

            <!-- TRATAMIENTOS -->
            <div class="tab-pane fade" id="tratamientos">
                <p>Módulo en construcción</p>
            </div>

            <!-- PROCEDIMIENTOS -->
            <div class="tab-pane fade" id="procedimientos">
                <p>Módulo en construcción</p>
            </div>

        </div>

    </div>
@endsection
