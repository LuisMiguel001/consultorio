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

        /* Modal personalizado */
        .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--primary-border);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .15);
            background: var(--primary-light);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-bottom: 2px solid var(--primary-border);
            font-weight: 600;
        }

        .modal-body p {
            border-bottom: 1px dashed var(--primary-border);
            padding-bottom: 6px;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .modal-body .badge {
            margin-right: 5px;
            font-weight: 500;
        }

        .modal-footer {
            border-top: 2px solid var(--primary-border);
            background: var(--primary-soft);
        }

        .modal-footer .btn {
            border-radius: 8px;
        }
    </style>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Agenda</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

            .card-header {
                background: var(--primary-color);
                color: white;
            }

            .table thead {
                background: var(--primary-light);
                color: var(--primary-dark);
            }

            .table-hover tbody tr:hover {
                background-color: var(--primary-light);
            }

            .btn-view {
                background: var(--primary-border);
                color: white;
                border-radius: 6px;
            }

            .btn-edit {
                background: var(--primary-color);
                color: white;
                border-radius: 6px;
            }

            .btn-cancel {
                background: var(--primary-dark);
                color: white;
                border-radius: 6px;
            }

            .badge {
                font-weight: 500;
            }

            .pagination .page-link {
                color: var(--primary-color);
                border-radius: 8px;
                border: 1px solid var(--primary-border);
            }

            .pagination .page-item.active .page-link {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            .page-link:hover {
                background: var(--primary-light);
                border-color: var(--primary-color);
                color: var(--primary-dark);
            }

            .btn-outline-secondary:hover {
                background: var(--primary-light);
                border-color: var(--primary-color);
                color: var(--primary-dark);
            }

            a {
                color: var(--text-secondary);
            }

            a:hover {
                color: var(--primary-color);
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <div class="container my-4">
            <div class="card shadow-sm border-0" style="border-radius:16px; background: #f4f8fd;">
                <!-- HEADER -->
                <div class="card-header border-0 d-flex justify-content-between align-items-center"
                    style=" color: white; border-radius:18px 18px 0 0; background: #0d47a1;">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Agenda
                    </h5>
                    <a href="{{ route('citas.create') }}" class="btn btn-light" style="border-radius:8px;">
                        <i class="fas fa-plus me-1"></i>
                        Nueva Cita
                    </a>
                </div>

                <div class="card-body">
                    <!-- BUSCADOR Y FILTROS -->
                    <form method="GET" action="{{ route('citas.index') }}">
                        <div class="row gx-2 gy-2 mb-4">
                            <div class="col-md-4">
                                <input type="text" name="buscar" class="form-control"
                                    placeholder="Buscar paciente o procedimiento..." value="{{ request('buscar') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="filtro_estado" class="form-select">
                                    <option value="">Todos los estados</option>
                                    <option value="Programada"
                                        {{ request('filtro_estado') == 'Programada' ? 'selected' : '' }}>Programada</option>
                                    <option value="Realizada"
                                        {{ request('filtro_estado') == 'Realizada' ? 'selected' : '' }}>Realizada</option>
                                    <option value="Cancelada"
                                        {{ request('filtro_estado') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    <option value="Atrasada" {{ request('filtro_estado') == 'Atrasada' ? 'selected' : '' }}>
                                        Atrasada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="filtro_prioridad" class="form-select">
                                    <option value="">Todas las prioridades</option>
                                    <option value="Urgente"
                                        {{ request('filtro_prioridad') == 'Urgente' ? 'selected' : '' }}>Urgente</option>
                                    <option value="Preferente"
                                        {{ request('filtro_prioridad') == 'Preferente' ? 'selected' : '' }}>Preferente
                                    </option>
                                    <option value="Normal" {{ request('filtro_prioridad') == 'Normal' ? 'selected' : '' }}>
                                        Normal</option>
                                </select>
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-1"></i>
                                    Filtrar
                                </button>
                            </div>
                            <div class="col-md-auto">
                                <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($citas->count() == 0)
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay citas programadas para mostrar.
                        </div>
                    @else
                        <div class="text-end mb-2 text-muted">
                            Total de citas: <strong>{{ $citas->total() }}</strong>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover"
                                style="background:white;border-radius:12px;overflow:hidden;">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Fecha / Hora</th>
                                        <th class="py-3">Paciente</th>
                                        <th class="py-3">Prioridad</th>
                                        <th class="py-3">Estado</th>
                                        <th class="py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citas as $cita)
                                        <tr>
                                            <td class="px-4">
                                                <div class="fw-bold">
                                                    {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $cita->hora }} ({{ $cita->duracion_minutos }} min)
                                                </small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">
                                                    {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    {{ $cita->paciente->cedula }}
                                                </small>
                                            </td>
                                            <td>
                                                @if ($cita->prioridad == 'Urgente')
                                                    <span class="badge bg-danger"><i
                                                            class="fas fa-exclamation-circle me-1"></i>Urgente</span>
                                                @elseif($cita->prioridad == 'Preferente')
                                                    <span class="badge bg-warning text-dark"><i
                                                            class="fas fa-clock me-1"></i>Preferente</span>
                                                @else
                                                    <span class="badge bg-secondary"><i
                                                            class="fas fa-check-circle me-1"></i>Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($cita->estado_cita == 'Programada')
                                                    <span class="badge bg-primary"><i
                                                            class="fas fa-calendar-check me-1"></i>Programada</span>
                                                @elseif($cita->estado_cita == 'Realizada')
                                                    <span class="badge bg-success"><i
                                                            class="fas fa-check-circle me-1"></i>Realizada</span>
                                                @elseif($cita->estado_cita == 'Cancelada')
                                                    <span class="badge bg-danger"><i
                                                            class="fas fa-times-circle me-1"></i>Cancelada</span>
                                                @elseif($cita->estado_cita == 'Atrasada')
                                                    <span class="badge bg-warning"><i class="fas fa-clock"></i>
                                                        Atrasada</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="#" class="btn btn-sm btn-view" data-bs-toggle="modal"
                                                        title="Ver Detalle de la Cita"
                                                        data-bs-target="#modalCita{{ $cita->id }}"><i
                                                            class="fas fa-eye"></i></a>
                                                    @if ($cita->estado_cita == 'Programada')
                                                        <a href="{{ route('consultas.create', ['id' => $cita->paciente_id, 'cita' => $cita->id]) }}"
                                                            title="Atender Paciente" class="btn btn-sm btn-success"><i
                                                                class="fas fa-stethoscope"></i></a>
                                                    @endif
                                                    @if ($cita->estado_cita != 'Realizada' && $cita->estado_cita != 'Cancelada')
                                                        <a href="{{ route('citas.edit', $cita->id) }}" title="Editar cita"
                                                            class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                                                    @endif
                                                    @if ($cita->estado_cita != 'Realizada' && $cita->estado_cita != 'Cancelada')
                                                        <form action="{{ route('citas.destroy', $cita->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Seguro que desea cancelar esta cita?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-cancel" title="Cancelar cita">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalCita{{ $cita->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Detalle de la Cita
                                                        </h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><strong>Paciente:</strong> {{ $cita->paciente->nombre }}
                                                            {{ $cita->paciente->apellido }}</p>
                                                        <p><strong>Cédula:</strong> {{ $cita->paciente->cedula }}</p>
                                                        <p><strong>Procedimiento:</strong> {{ $cita->servicio_especifico }}
                                                        </p>
                                                        <p><strong>Fecha:</strong>
                                                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</p>
                                                        <p><strong>Hora:</strong> {{ $cita->hora }}
                                                            ({{ $cita->duracion_minutos }} min)
                                                        </p>
                                                        <p><strong>Prioridad:</strong> {{ $cita->prioridad }}</p>
                                                        <p><strong>Notas:</strong>
                                                            {{ $cita->notas_previas ?? 'Sin notas' }}</p>

                                                        @if ($cita->requiere_ayuno)
                                                            <span class="badge bg-warning">Ayuno requerido</span>
                                                        @endif

                                                        @if ($cita->estudios_previos)
                                                            <span class="badge bg-info">Traer estudios</span>
                                                        @endif
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i>
                                                            Cerrar
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                                <div class="text-muted small mb-3 mb-md-0">
                                    Mostrando {{ $citas->firstItem() }} - {{ $citas->lastItem() }} de
                                    {{ $citas->total() }} citas
                                </div>

                                <div class="pagination-wrapper">
                                    {{ $citas->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
