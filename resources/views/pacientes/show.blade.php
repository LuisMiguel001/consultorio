@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now()->format('Y-m-d\TH:i');
    @endphp
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
                    Historial Clínico
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#antecedentes">
                    Antecedentes
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historial">
                    Historial Clínico - Resumen General
                </button>
            </li>
        </ul>

        <div class="tab-content p-4 border border-top-0">
            <!-- CONSULTAS -->
            <div class="tab-pane fade show active" id="consultas">

                <a href="{{ route('consultas.create', $paciente->id) }}" class="btn btn-light btn-sm mb-3">
                    ➕ Nuevo Historial Clínico
                </a>

                @foreach ($paciente->consultas as $consulta)
                    <div class="card mb-4 shadow-sm">

                        <div class="card-header bg-light d-flex justify-content-between">
                            <div>
                                <strong>{{ $consulta->created_at->format('d/m/Y h:i A') }}</strong>
                                | Dr. {{ $consulta->doctor->name }}
                            </div>

                            <a href="{{ route('consultas.show', $consulta) }}" class="btn btn-sm btn-outline-primary">
                                Ver Historial
                            </a>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-bordered table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:25%">Tipo de Consulta</th>
                                        <td>{{ $consulta->tipo_consulta ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th style="width:25%">Motivo de Consulta</th>
                                        <td>{{ $consulta->motivo_consulta ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Enfermedad Actual</th>
                                        <td>{{ $consulta->enfermedad_actual ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Plan</th>
                                        <td>{{ $consulta->plan ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Observaciones</th>
                                        <td>{{ $consulta->observaciones ?? 'No registrado' }}</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>

                    </div>
                @endforeach
            </div>

            <!-- ANTECEDENTES (IGUAL) -->
            <div class="tab-pane fade" id="antecedentes">

                <button class="btn btn-light btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formAntecedente">
                    ➕ Registrar Antecedentes
                </button>

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

                            <button class="btn btn-success w-100">Guardar Antecedentes</button>

                        </form>
                    </div>
                </div>

                @forelse ($paciente->antecedentes->sortByDesc('created_at') as $ant)
                    <div class="card mb-4 shadow-sm">

                        <div class="card-header bg-light d-flex justify-content-between">
                            <strong>{{ $ant->created_at->format('d/m/Y H:i') }}</strong>
                            <small>Dr. {{ $ant->usuario->name }}</small>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-bordered table-sm mb-0">
                                <tbody>

                                    <tr>
                                        <th style="width:25%">Antecedentes Personales</th>
                                        <td>{{ $ant->antecedentes_personales ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Antecedentes Familiares</th>
                                        <td>{{ $ant->antecedentes_familiares ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Antecedentes Quirúrgicos</th>
                                        <td>{{ $ant->antecedentes_quirurgicos ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Alergias</th>
                                        <td>{{ $ant->alergias ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Medicamentos Actuales</th>
                                        <td>{{ $ant->medicamentos_actuales ?? 'No registrado' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Hábitos</th>
                                        <td>{{ $ant->habitos ?? 'No registrado' }}</td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>

                    </div>

                @empty
                    <div class="alert alert-secondary">
                        No hay antecedentes registrados.
                    </div>
                @endforelse

            </div>

            <!-- HISTORIAL CLÍNICO (NUEVO DISEÑO) -->
            <div class="tab-pane fade" id="historial">
                <div class="container-fluid py-2">

                    <!-- HEADER DEL REPORTE -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                        <div class="card-header text-white d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 18px 18px 0 0;">
                            <div>
                                <h5 class="mb-1">HISTORIAL CLÍNICO COMPLETO</h5>
                                <p class="mb-0 opacity-75 small">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 1: DATOS DEL PACIENTE -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background: #f8f9fa;">
                            <h6 class="mb-0">DATOS DEL PACIENTE</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Nombre completo:</strong></p>
                                    <p>{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Fecha de nacimiento:</strong></p>
                                    <p>{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Cédula:</strong></p>
                                    <p>{{ $paciente->cedula }}</p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Género:</strong></p>
                                    <p>{{ $paciente->sexo }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Dirección:</strong></p>
                                    <p>{{ $paciente->direccion ?? 'No especificada' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Contacto:</strong></p>
                                    <p>{{ $paciente->telefono ?? 'No especificado' }}<br>{{ $paciente->email ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: ANTECEDENTES -->
                    @if ($paciente->antecedentes->isNotEmpty())
                        @php $ultimoAntecedente = $paciente->antecedentes->sortByDesc('created_at')->first(); @endphp
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header" style="background: #f8f9fa;">
                                <h6 class="mb-0">ANTECEDENTES</h6>
                            </div>
                            <div class="card-body">
                                @if ($ultimoAntecedente->antecedentes_personales)
                                    <div class="mb-3">
                                        <h6 class="text-primary small">Antecedentes Personales</h6>
                                        <p class="border p-2 rounded bg-light small">
                                            {{ $ultimoAntecedente->antecedentes_personales }}</p>
                                    </div>
                                @endif

                                @if ($ultimoAntecedente->antecedentes_familiares)
                                    <div class="mb-3">
                                        <h6 class="text-primary small">Antecedentes Familiares</h6>
                                        <p class="border p-2 rounded bg-light small">
                                            {{ $ultimoAntecedente->antecedentes_familiares }}</p>
                                    </div>
                                @endif

                                @if ($ultimoAntecedente->alergias || $ultimoAntecedente->medicamentos_actuales)
                                    <div class="row">
                                        @if ($ultimoAntecedente->alergias)
                                            <div class="col-md-6">
                                                <h6 class="text-primary small">Alergias</h6>
                                                <p class="small">{{ $ultimoAntecedente->alergias }}</p>
                                            </div>
                                        @endif
                                        @if ($ultimoAntecedente->medicamentos_actuales)
                                            <div class="col-md-6">
                                                <h6 class="text-primary small">Medicación Actual</h6>
                                                <p class="small">{{ $ultimoAntecedente->medicamentos_actuales }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="text-muted small mt-2">
                                    <i>Última actualización: {{ $ultimoAntecedente->created_at->format('d/m/Y H:i') }}</i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- SECCIÓN 3: CONSULTAS Y TRATAMIENTOS -->
                    @if ($paciente->consultas->isNotEmpty())
                        @foreach ($paciente->consultas->sortByDesc('fecha_consulta') as $consulta)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center"
                                    style="background: linear-gradient(135deg, #6f42c1 0%, #8a5aa0 100%); color: white;">
                                    <h6 class="mb-0">
                                        CONSULTA - {{ \Carbon\Carbon::parse($consulta->fecha_consulta)->format('d/m/Y') }}
                                    </h6>
                                    <span class="badge bg-light text-dark small">
                                        Dr. {{ $consulta->doctor->name ?? 'No especificado' }}
                                    </span>
                                </div>

                                <div class="card-body p-3">
                                    <!-- Diagnóstico -->
                                    @if ($consulta->diagnosticos->isNotEmpty())
                                        <table class="table table-bordered table-sm mb-3">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th colspan="2" class="small">Diagnóstico</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($consulta->diagnosticos as $diagnostico)
                                                    <tr>
                                                        <td class="small">{{ $diagnostico->descripcion }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                    <!-- Tratamientos/Medicamentos -->
                                    @if ($consulta->tratamientos->isNotEmpty())
                                        <table class="table table-bordered table-sm mb-3">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th colspan="4" class="small">Medicamentos Recetados</th>
                                                </tr>
                                                <tr>
                                                    <th class="small">Medicamento</th>
                                                    <th class="small">Dosis</th>
                                                    <th class="small">Frecuencia</th>
                                                    <th class="small">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($consulta->tratamientos as $tratamiento)
                                                    <tr>
                                                        <td class="small">{{ $tratamiento->medicamento }}</td>
                                                        <td class="small">{{ $tratamiento->dosis ?? 'N/A' }}</td>
                                                        <td class="small">{{ $tratamiento->frecuencia ?? 'N/A' }}</td>
                                                        <td class="small">{{ $tratamiento->duracion ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                    <!-- Evoluciones -->
                                    @if ($consulta->evoluciones->isNotEmpty())
                                        @foreach ($consulta->evoluciones as $evolucion)
                                            <div class="mb-2">
                                                <h6 class="text-primary small">Evolución -
                                                    {{ $evolucion->created_at->format('d/m/Y H:i') }}</h6>
                                                <p class="border p-2 rounded bg-light small">{{ $evolucion->descripcion }}
                                                </p>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="text-muted small text-end">
                                        <i>Registrado: {{ $consulta->created_at->format('d/m/Y H:i') }}</i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- HISTORIAL CRONOLÓGICO -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header" style="background: #a97bc9; color: white;">
                            <h6 class="mb-0">LÍNEA DE TIEMPO - HISTORIAL COMPLETO</h6>
                        </div>
                        <div class="card-body p-3">
                            @forelse($eventos as $evento)
                                <div
                                    class="card mb-2 border-start border-3 @if ($evento['tipo'] == 'Consulta') border-primary @elseif($evento['tipo'] == 'Diagnóstico') border-success @elseif($evento['tipo'] == 'Medicamento') border-warning @else border-info @endif">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="card-subtitle mb-1 text-muted small">
                                                {{ $evento['tipo'] }}
                                            </h6>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($evento['fecha'])->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <p class="card-text small mb-0">{{ $evento['contenido'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-secondary small mb-0">
                                    No hay historial clínico registrado.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-body text-center text-muted p-2">
                            <p class="mb-0 small">
                                Reporte generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Gestión de Pacientes
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .timeline .card {
                transition: transform 0.2s;
            }

            .timeline .card:hover {
                transform: translateX(5px);
            }

            .border-primary {
                border-left-color: #667eea !important;
            }

            .border-success {
                border-left-color: #28a745 !important;
            }

            .border-warning {
                border-left-color: #ffc107 !important;
            }

            .border-info {
                border-left-color: #17a2b8 !important;
            }

            .table th {
                background-color: #f8f9fa;
            }
        </style>
    @endpush
@endsection
