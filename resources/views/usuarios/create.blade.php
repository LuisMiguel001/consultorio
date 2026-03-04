@extends('layouts.app')

@section('content')

<h4>Crear Usuario</h4>

<form method="POST" action="{{ route('usuarios.store') }}">
    @csrf

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control">
    </div>

    <hr>

    <h5>Roles</h5>

    @foreach($roles as $role)
        <div>
            <input type="checkbox" name="roles[]"
                   value="{{ $role->name }}">
            {{ $role->name }}
        </div>
    @endforeach

    <hr>

    <h5>Permisos Individuales</h5>

    @foreach($permissions as $permission)
        <div>
            <input type="checkbox" name="permissions[]"
                   value="{{ $permission->name }}">
            {{ $permission->name }}
        </div>
    @endforeach

    <button class="btn btn-dark mt-3">
        Guardar
    </button>
</form>

@endsection
