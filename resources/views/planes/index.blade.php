@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">Gestión de Planes</h1>
            <p class="text-muted">Administra los planes de suscripción del sistema</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('planes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Plan
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Precio Mensual</th>
                                    <th>Precio Anual</th>
                                    <th>Límites</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($planes as $plan)
                                <tr>
                                    <td>
                                        <strong>{{ $plan->nombre }}</strong>
                                        @if($plan->descripcion)
                                        <small class="d-block text-muted">{{ Str::limit($plan->descripcion, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>${{ number_format($plan->precio_mensual, 2) }}</td>
                                    <td>${{ number_format($plan->precio_anual, 2) }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $plan->max_doctores ? "{$plan->max_doctores} doctores" : '∞ doctores' }},
                                            {{ $plan->max_pacientes ? "{$plan->max_pacientes} pacientes" : '∞ pacientes' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($plan->activo)
                                        <span class="badge bg-success">Activo</span>
                                        @else
                                        <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('planes.edit', $plan) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleStatus('{{ $plan->id }}', '{{ $plan->nombre }}')">
                                                <i class="bi bi-power"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePlan('{{ $plan->id }}', '{{ $plan->nombre }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="mb-0">No hay planes registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios ocultos para acciones -->
<form id="toggle-status-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function toggleStatus(id, nombre) {
    if (confirm(`¿Estás seguro de cambiar el estado del plan "${nombre}"?`)) {
        let form = document.getElementById('toggle-status-form');
        form.action = `/planes/${id}/toggle-status`;
        form.submit();
    }
}

function deletePlan(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar el plan "${nombre}"? Esta acción no se puede deshacer.`)) {
        let form = document.getElementById('delete-form');
        form.action = `/planes/${id}`;
        form.submit();
    }
}
</script>
@endsection
