@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
        $now = Carbon::now()->format('Y-m-d\TH:i');
        $tab = request('tab', 'consultas');
    @endphp

    <div class="container my-4">

        <!-- INFORMACIÓN GENERAL PACIENTE -->
        <div class="card mb-4 shadow-sm border-0" style="border-radius:8px;">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="background: #0d47a1; color:white; border-radius:8px 8px 0 0;">
                <h5 class="mb-0">Datos del Paciente - {{ $paciente->nombre }} {{ $paciente->apellido }}</h5>
                <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-light"
                    style="color: #0d47a1; border-radius: 6px;">
                    Editar
                </a>
            </div>
            <div class="card-footer bg-transparent border-0">
                <div class="row text-center">
                    <div class="col-md-4">
                        <small class="fw-bold">Paciente</small>
                        <div class="text-black">{{ $paciente->nombre }} {{ $paciente->apellido }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Sexo</small>
                        <div class="text-black">{{ $paciente->sexo }} </div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Teléfono</small>
                        <div class="text-black">{{ $paciente->telefono }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Email</small>
                        <div class="text-black">{{ $paciente->email }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Cédula</small>
                        <div class="text-black">{{ $paciente->cedula }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">ARS</small>
                        <div class="text-black">{{ $paciente->seguro_medico }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">NSS</small>
                        <div class="text-black">{{ $paciente->nss }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Fecha de Nacimiento</small>
                        <div class="text-black">{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <small class="fw-bold">Dirección</small>
                        <div class="text-black">{{ $paciente->direccion }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link {{ $tab == 'consultas' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#consultas">
                    Historial Clínico
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link {{ $tab == 'antecedentes' ? 'active' : '' }}" data-bs-toggle="tab"
                    data-bs-target="#antecedentes">
                    Antecedentes
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- CONSULTAS -->
            <div class="tab-pane fade {{ $tab == 'consultas' ? 'show active' : '' }}" id="consultas">
                <!-- Botón para nueva consulta -->
                <a href="{{ route('consultas.create', $paciente->id) }}" class="btn btn-secondary btn-sm mb-3">
                    ➕ Nuevo Historial Clínico
                </a>

                <!-- FILTROS DE FECHA -->
                <div class="card mb-2 shadow-sm border-0 rounded-2 p-2">
                    <form method="GET" action="{{ route('pacientes.show', $paciente->id) }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" name="consulta_desde" class="form-control"
                                    value="{{ request('consulta_desde') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" name="consulta_hasta" class="form-control"
                                    value="{{ request('consulta_hasta') }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary">Filtrar</button>

                                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-secondary">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                @php
                    // Convertir a colección y ordenar por fecha_consulta descendente
                    $consultas = $paciente->consultas()->orderBy('fecha_consulta', 'desc')->get();

                    // Aplicar filtros de fecha
                    if (request('consulta_desde')) {
                        $desde = Carbon::parse(request('consulta_desde'))->startOfDay();
                        $consultas = $consultas->filter(function ($item) use ($desde) {
                            return Carbon::parse($item->fecha_consulta)->greaterThanOrEqualTo($desde);
                        });
                    }

                    if (request('consulta_hasta')) {
                        $hasta = Carbon::parse(request('consulta_hasta'))->endOfDay();
                        $consultas = $consultas->filter(function ($item) use ($hasta) {
                            return Carbon::parse($item->fecha_consulta)->lessThanOrEqualTo($hasta);
                        });
                    }
                @endphp

                <!-- Lista de consultas -->
                @forelse ($consultas as $consulta)
                    <div class="card mb-3 shadow-sm" style="border-radius:12px;">
                        <div class="card-header text-white d-flex justify-content-between align-items-center"
                            style="background: #90caf9">
                            <span>
                                {{ $consulta->created_at ? $consulta->created_at->format('d/m/Y h:i A') : 'Sin fecha' }} |
                                Dr.
                                {{ $consulta->doctor->name ?? 'No especificado' }}
                            </span>
                            <a href="{{ route('consultas.show', $consulta) }}" class="btn btn-sm btn-light">Ver Detalle</a>
                        </div>
                        <div class="card-body p-2">
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:25%">Tipo de Consulta</th>
                                        <td>{{ $consulta->tipo_consulta ?? 'No registrado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Motivo</th>
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
                                        <td>
                                            {!! nl2br(
                                                e(trim(str_replace(['\\n', '\\r', '\\t'], "\n", strip_tags($consulta->observaciones ?? 'No registrado')))),
                                            ) !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-secondary">No hay historiales clínicos registradas en este rango de fechas.
                    </div>
                @endforelse

            </div>


            <!-- ANTECEDENTES -->
            <div class="tab-pane fade {{ $tab == 'antecedentes' ? 'show active' : '' }}" id="antecedentes">
                <button class="btn btn-secondary btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#formAntecedente">
                    ➕ Registrar Antecedentes
                </button>

                <!-- Formulario Colapsable -->
                <div class="collapse mb-4" id="formAntecedente">
                    <div class="card card-body shadow-sm border-0 rounded-3">
                        <form method="POST" action="{{ route('antecedentes.store', $paciente->id) }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Antecedentes Personales</strong></label>
                                    <textarea name="antecedentes_personales" class="form-control" rows="3"
                                        placeholder="Describa los antecedentes personales..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Antecedentes Familiares</strong></label>
                                    <textarea name="antecedentes_familiares" class="form-control" rows="3"
                                        placeholder="Describa antecedentes familiares..."></textarea>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Antecedentes Quirúrgicos</strong></label>
                                    <textarea name="antecedentes_quirurgicos" class="form-control" rows="2"
                                        placeholder="Detalle cirugías previas..."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Alergias</strong></label>
                                    <textarea name="alergias" class="form-control" rows="2" placeholder="Alergias conocidas..."></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><strong>Medicamentos Actuales</strong></label>
                                    <textarea name="medicamentos_actuales" class="form-control" rows="2"
                                        placeholder="Medicamentos que toma actualmente..."></textarea>
                                </div>
                            </div>
                            <div class="mb-3 mt-2">
                                <label class="form-label"><strong>Hábitos</strong></label>
                                <textarea name="habitos" class="form-control" rows="2" placeholder="Ej: fumar, alcohol, ejercicio..."></textarea>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-gradient"
                                    style="background: #0d47a1; color:white; border:none; border-radius:8px;">
                                    Guardar Antecedente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- FILTROS DE FECHA -->
                <div class="card mb-3 shadow-sm border-0 rounded-2 p-2">
                    <form method="GET" action="{{ route('pacientes.show', $paciente->id) }}">
                        <input type="hidden" name="tab" value="antecedentes">

                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" name="fecha_desde" class="form-control"
                                    value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" name="fecha_hasta" class="form-control"
                                    value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-secondary">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lista de Antecedentes en tabla -->
                @php
                    $antecedentes = $paciente->antecedentes->sortByDesc('created_at');

                    if (request('fecha_desde')) {
                        $antecedentes = $antecedentes->filter(function ($item) {
                            return $item->created_at->format('Y-m-d') >= request('fecha_desde');
                        });
                    }
                    if (request('fecha_hasta')) {
                        $antecedentes = $antecedentes->filter(function ($item) {
                            return $item->created_at->format('Y-m-d') <= request('fecha_hasta');
                        });
                    }
                @endphp

                @forelse ($antecedentes as $ant)
                    <div class="card mb-3 shadow-sm border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center"
                            style="background: #90caf9; color: white; border-radius: 12px 12px 0 0;">
                            <span>{{ $ant->created_at->format('d/m/Y H:i') }}</span>
                            <small>{{ $ant->usuario->name ?? 'No especificado' }}</small>
                        </div>
                        <div class="card-body p-2">
                            <table class="table table-bordered table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width:25%">Personales</th>
                                        <td>{{ $ant->antecedentes_personales ?? 'No registrado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Familiares</th>
                                        <td>{{ $ant->antecedentes_familiares ?? 'No registrado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Quirúrgicos</th>
                                        <td>{{ $ant->antecedentes_quirurgicos ?? 'No registrado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alergias</th>
                                        <td>{{ $ant->alergias ?? 'No registrado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Medicamentos</th>
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
                    <div class="alert alert-secondary">No hay antecedentes registrados en este rango de fechas.</div>
                @endforelse
            </div>

            <!-- HISTORIAL RESUMEN GENERAL -->
            <div class="tab-pane fade" id="historial">
                <div class="timeline">
                    @forelse($eventos as $evento)
                        <div class="card mb-2 border-start border-3
                    @if ($evento['tipo'] == 'Consulta') border-primary
                    @elseif($evento['tipo'] == 'Diagnóstico') border-success
                    @elseif($evento['tipo'] == 'Medicamento') border-warning
                    @else border-info @endif
                    shadow-sm"
                            style="border-radius:8px;">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between">
                                    <h6 class="small text-muted">{{ $evento['tipo'] }}</h6>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($evento['fecha'])->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="small mb-0">{{ $evento['contenido'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-secondary">No hay historial clínico registrado.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .timeline .card:hover {
        transform: translateX(5px);
        transition: transform 0.2s;
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

    .btn-gradient:hover {
        opacity: 0.9;
    }

    .form-label {
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
    }

    .card-body p {
        margin-bottom: 0.5rem;
    }
</style>
