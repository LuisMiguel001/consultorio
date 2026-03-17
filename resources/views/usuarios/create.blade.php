@extends('layouts.app')

@section('content')
    <h4>Crear Usuario</h4>

    <form method="POST" action="{{ route('usuarios.store') }}" autocomplete="off">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" autocomplete="off">
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="email" class="form-control" autocomplete="off">
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" autocomplete="new-password">
        </div>

        <div class="mb-3">
            <label>Doctor asignado</label>
            <select name="doctor_id" class="form-control">
                <option value="">Sin asignar</option>
                @foreach ($doctores as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                @endforeach
            </select>
        </div>

        <hr>

        <h5>Roles</h5>
        @foreach ($roles as $role)
            <div>
                <input type="checkbox" name="roles[]" value="{{ $role->name }}">
                {{ $role->name }}
            </div>
        @endforeach

        <hr>

        <h5>Permisos Individuales</h5>

        <div class="mb-2 d-flex gap-2">
            <button type="button" class="btn btn-sm btn-success" onclick="togglePermisos(true)">
                <i class="fas fa-check-square me-1"></i> Marcar todos
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePermisos(false)">
                <i class="fas fa-square me-1"></i> Desmarcar todos
            </button>
        </div>

        @foreach ($permissions as $permission)
            <div>
                <input type="checkbox" class="perm-checkbox" name="permissions[]" value="{{ $permission->name }}">
                {{ $permission->name }}
            </div>
        @endforeach

        <button class="btn btn-dark mt-3">Guardar</button>
    </form>

    <script>
        function togglePermisos(marcar) {
            document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = marcar);
        }
    </script>
@endsection
