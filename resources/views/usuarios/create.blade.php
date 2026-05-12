@extends('layouts.app')

@section('content')

<style>
    .card-form {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        overflow: hidden;
        background: #fff;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #0d47a1, #002171);
        color: white;
        padding: 18px 22px;
        font-weight: 600;
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0d47a1;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 0.15rem rgba(13,71,161,.15);
    }

    .role-box, .perm-box {
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 12px;
        background: #fafafa;
        max-height: 220px;
        overflow-y: auto;
    }

    .perm-item, .role-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
    }

    .btn-main {
        background: #0d47a1;
        color: white;
        border-radius: 10px;
        padding: 10px 20px;
        border: none;
    }

    .btn-main:hover {
        background: #002171;
        color: white;
    }

    .btn-outline {
        border-radius: 10px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media(max-width: 768px){
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container py-4">

    <div class="card card-form">

        <div class="card-header-custom">
            <i class="bi bi-person-plus me-2"></i>
            Crear Usuario
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('usuarios.store') }}" autocomplete="off">
                @csrf

                {{-- INFORMACIÓN GENERAL --}}
                <div class="section-title">Información General</div>

                <div class="grid-2 mb-3">

                    <div>
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="name" class="form-control" autocomplete="off">
                    </div>

                    <div>
                        <label class="form-label">Usuario / Email</label>
                        <input type="text" name="email" class="form-control" autocomplete="off">
                    </div>

                </div>

                <div class="grid-2 mb-3">

                    <div>
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>

                    <div>
                        <label class="form-label">Doctor asignado</label>
                        <select name="doctor_id" class="form-select">
                            <option value="">Sin asignar</option>
                            @foreach ($doctores as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- CONFIGURACIÓN MÉDICA --}}
                <div class="section-title mt-3">Configuración Médica</div>

                <div class="grid-2 mb-3">

                    <div id="especialidadDiv">
                        <label class="form-label">Especialidad</label>
                        <select name="especialidad_id" class="form-select">
                            <option value="">--Seleccione--</option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp->id }}">{{ $esp->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="consultorioDiv">
                        <label class="form-label">Consultorio</label>
                        <select name="consultorio_id" class="form-select">
                            <option value="">--Seleccione--</option>
                            @foreach ($consultorios as $consultorio)
                                <option value="{{ $consultorio->id }}">
                                    {{ $consultorio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- ROLES --}}
                <div class="section-title mt-3">Roles del usuario</div>

                <div class="role-box mb-3">
                    @foreach ($roles as $role)
                        <div class="role-item">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}">
                            <span>{{ $role->name }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- PERMISOS --}}
                <div class="section-title">Permisos individuales</div>

                <div class="d-flex gap-2 mb-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="togglePermisos(true)">
                        Marcar todos
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="togglePermisos(false)">
                        Desmarcar
                    </button>
                </div>

                <div class="perm-box mb-3">
                    @foreach ($permissions as $permission)
                        <div class="perm-item">
                            <input type="checkbox" class="perm-checkbox" name="permissions[]" value="{{ $permission->name }}">
                            <span>{{ $permission->name }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- BOTÓN --}}
                <div class="text-center">
                    <button class="btn-main">
                        <i class="bi bi-save me-1"></i>
                        Guardar Usuario
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<script>
    function togglePermisos(marcar) {
        document.querySelectorAll('.perm-checkbox')
            .forEach(cb => cb.checked = marcar);
    }

    document.addEventListener('DOMContentLoaded', function () {

        const rolesCheckbox = document.querySelectorAll('input[name="roles[]"]');

        const especialidadDiv = document.getElementById('especialidadDiv');
        const consultorioDiv = document.getElementById('consultorioDiv');

        function toggleFields() {
            let esDoctor = false;

            rolesCheckbox.forEach(cb => {
                if (cb.value === 'doctor' && cb.checked) {
                    esDoctor = true;
                }
            });

            especialidadDiv.style.display = esDoctor ? 'block' : 'none';
            consultorioDiv.style.display = esDoctor ? 'block' : 'none';
        }

        rolesCheckbox.forEach(cb => cb.addEventListener('change', toggleFields));

        toggleFields();
    });
</script>

@endsection
