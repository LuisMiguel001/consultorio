{{-- resources/views/agenda/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-alt me-2" style="color: #a97bc9;"></i>
            Agenda de Procedimientos
        </h2>
        <a href="{{ route('citas.create') }}" class="btn" style="background: #a97bc9; color: white;">
            <i class="fas fa-plus me-1"></i>
            Nueva Cita
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8f0ff;">
                        <tr>
                            <th class="px-4 py-3">Fecha/Hora</th>
                            <th class="py-3">Paciente</th>
                            <th class="py-3">Procedimiento</th>
                            <th class="py-3">Prioridad</th>
                            <th class="py-3">Estado</th>
                            <th class="py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas as $cita)
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold">{{ $cita->fecha->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $cita->hora }} ({{ $cita->duracion_minutos }} min)</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</div>
                                    <small class="text-muted">Cédula: {{ $cita->paciente->cedula }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background: #e9d5ff; color: #4a2c6d;">
                                        {{ $cita->servicio_especifico }}
                                    </span>
                                </td>
                                <td>
                                    @if($cita->prioridad == 'Urgente')
                                        <span class="badge bg-danger">Urgente</span>
                                    @elseif($cita->prioridad == 'Preferente')
                                        <span class="badge bg-warning text-dark">Preferente</span>
                                    @else
                                        <span class="badge bg-secondary">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cita->estado_cita == 'Programada')
                                        <span class="badge bg-primary">Programada</span>
                                    @elseif($cita->estado_cita == 'Completada')
                                        <span class="badge bg-success">Completada</span>
                                    @elseif($cita->estado_cita == 'Cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No hay citas programadas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $citas->links() }}
    </div>
</div>
@endsection
