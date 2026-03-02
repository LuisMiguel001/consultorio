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

            <!-- Aquí luego agregamos diagnósticos, tratamientos, procedimientos -->

        </div>

    </div>
@endsection
