@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">Crear Nuevo Plan</h1>
            <p class="text-muted">Configura un nuevo plan de suscripción</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('planes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('planes.store') }}" method="POST" id="plan-form">
                @csrf

                <div class="row">
                    <!-- Columna izquierda: Información básica -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Plan *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                           id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="precio_mensual" class="form-label">Precio Mensual ($) *</label>
                                            <input type="number" step="0.01" class="form-control @error('precio_mensual') is-invalid @enderror"
                                                   id="precio_mensual" name="precio_mensual" value="{{ old('precio_mensual') }}" required>
                                            @error('precio_mensual')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="precio_anual" class="form-label">Precio Anual ($) *</label>
                                            <input type="number" step="0.01" class="form-control @error('precio_anual') is-invalid @enderror"
                                                   id="precio_anual" name="precio_anual" value="{{ old('precio_anual') }}" required>
                                            @error('precio_anual')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="caracteristicas" class="form-label">Características (separadas por coma)</label>
                                    <input type="text" class="form-control @error('caracteristicas') is-invalid @enderror"
                                           id="caracteristicas" name="caracteristicas"
                                           value="{{ old('caracteristicas') }}"
                                           placeholder="Ej: Soporte 24/7, API Acceso, Personalización">
                                    <small class="text-muted">Ingresa las características separadas por comas</small>
                                    @error('caracteristicas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Límites de Personal</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="max_doctores" class="form-label">Máx. Doctores</label>
                                            <input type="number" class="form-control @error('max_doctores') is-invalid @enderror"
                                                   id="max_doctores" name="max_doctores" value="{{ old('max_doctores') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_doctores')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="max_secretarias" class="form-label">Máx. Secretarias</label>
                                            <input type="number" class="form-control @error('max_secretarias') is-invalid @enderror"
                                                   id="max_secretarias" name="max_secretarias" value="{{ old('max_secretarias') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_secretarias')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="max_enfermeras" class="form-label">Máx. Enfermeras</label>
                                            <input type="number" class="form-control @error('max_enfermeras') is-invalid @enderror"
                                                   id="max_enfermeras" name="max_enfermeras" value="{{ old('max_enfermeras') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_enfermeras')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Límites de Recursos</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_pacientes" class="form-label">Máx. Pacientes</label>
                                            <input type="number" class="form-control @error('max_pacientes') is-invalid @enderror"
                                                   id="max_pacientes" name="max_pacientes" value="{{ old('max_pacientes') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_pacientes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_citas" class="form-label">Máx. Citas (por mes)</label>
                                            <input type="number" class="form-control @error('max_citas') is-invalid @enderror"
                                                   id="max_citas" name="max_citas" value="{{ old('max_citas') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_citas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_consultas" class="form-label">Máx. Consultas</label>
                                            <input type="number" class="form-control @error('max_consultas') is-invalid @enderror"
                                                   id="max_consultas" name="max_consultas" value="{{ old('max_consultas') }}" placeholder="Dejar vacío = ilimitado">
                                            @error('max_consultas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_mensajes_whatsapp" class="form-label">Máx. Msgs WhatsApp (por mes)</label>
                                            <input type="number" class="form-control @error('max_mensajes_whatsapp') is-invalid @enderror"
                                                   id="max_mensajes_whatsapp" name="max_mensajes_whatsapp" value="{{ old('max_mensajes_whatsapp', 0) }}">
                                            @error('max_mensajes_whatsapp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: Módulos y características -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Características Especiales</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="permite_archivar" name="permite_archivar" value="1">
                                            <label class="form-check-label" for="permite_archivar">
                                                <i class="bi bi-archive"></i> Permitir Archivado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="permite_recordatorios" name="permite_recordatorios" value="1">
                                            <label class="form-check-label" for="permite_recordatorios">
                                                <i class="bi bi-bell"></i> Recordatorios Automáticos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="permite_whatsapp" name="permite_whatsapp" value="1">
                                            <label class="form-check-label" for="permite_whatsapp">
                                                <i class="bi bi-whatsapp"></i> Mensajería WhatsApp
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="permite_reportes_avanzados" name="permite_reportes_avanzados" value="1">
                                            <label class="form-check-label" for="permite_reportes_avanzados">
                                                <i class="bi bi-graph-up"></i> Reportes Avanzados
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="permite_multiple_consultorios" name="permite_multiple_consultorios" value="1">
                                            <label class="form-check-label" for="permite_multiple_consultorios">
                                                <i class="bi bi-building"></i> Múltiples Consultorios
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Módulos Habilitados</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="select-all-modules">
                                        <label class="form-check-label fw-bold" for="select-all-modules">
                                            Seleccionar todos los módulos
                                        </label>
                                    </div>
                                    <hr>
                                    @php
                                        $groupedModules = [];
                                        foreach($modulosDisponibles as $key => $label) {
                                            // Agrupar por categoría
                                            if(str_contains($key, 'pacientes')) $group = 'Pacientes';
                                            elseif(str_contains($key, 'citas')) $group = 'Citas';
                                            elseif(str_contains($key, 'consultas')) $group = 'Consultas Médicas';
                                            elseif(str_contains($key, 'antecedentes')) $group = 'Historial Médico';
                                            elseif(str_contains($key, 'estudios')) $group = 'Estudios y Laboratorios';
                                            elseif(str_contains($key, 'diagnosticos') || str_contains($key, 'tratamientos') || str_contains($key, 'procedimientos')) $group = 'Diagnósticos y Tratamientos';
                                            elseif(str_contains($key, 'signos') || str_contains($key, 'examen')) $group = 'Signos Vitales';
                                            elseif(str_contains($key, 'evoluciones')) $group = 'Evoluciones';
                                            elseif(str_contains($key, 'recetas')) $group = 'Recetas';
                                            elseif(str_contains($key, 'caja')) $group = 'Gestión de Caja';
                                            else $group = 'Premium';

                                            $groupedModules[$group][$key] = $label;
                                        }
                                    @endphp

                                    @foreach($groupedModules as $groupName => $modules)
                                    <div class="mb-3">
                                        <div class="fw-bold mb-2">{{ $groupName }}</div>
                                        @foreach($modules as $key => $label)
                                        <div class="form-check mb-2 ms-3">
                                            <input class="form-check-input module-checkbox" type="checkbox"
                                                   id="mod_{{ $key }}" name="modulos_habilitados[]" value="{{ $key }}">
                                            <label class="form-check-label" for="mod_{{ $key }}">
                                                {{ $label }}
                                                <small class="text-muted">({{ $key }})</small>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body text-end">
                                <button type="reset" class="btn btn-secondary">Limpiar</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Crear Plan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('select-all-modules').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('.module-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Validar que al menos se seleccione un módulo antes de enviar
document.getElementById('plan-form').addEventListener('submit', function(e) {
    let selectedModules = document.querySelectorAll('.module-checkbox:checked');
    if(selectedModules.length === 0) {
        e.preventDefault();
        alert('Debes seleccionar al menos un módulo para el plan');
    }
});
</script>
@endsection
