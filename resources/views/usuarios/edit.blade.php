@extends('layouts.app')

@section('content')

<h4>Editar Usuario</h4>

<form method="POST" action="{{ route('usuarios.update',$user) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nombre</label>
        <input type="text"
               name="name"
               value="{{ $user->name }}"
               class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email"
               name="email"
               value="{{ $user->email }}"
               class="form-control">
    </div>

    <hr>

    <h5>Roles</h5>

    @foreach($roles as $role)
        <div>
            <input type="checkbox"
                   name="roles[]"
                   value="{{ $role->name }}"
                   {{ $user->hasRole($role->name) ? 'checked' : '' }}>
            {{ $role->name }}
        </div>
    @endforeach

    <hr>

    <h5>Permisos Individuales</h5>

    @foreach($permissions as $permission)
        <div>
            <input type="checkbox"
                   name="permissions[]"
                   value="{{ $permission->name }}"
                   {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
            {{ $permission->name }}
        </div>
    @endforeach

    <button class="btn btn-dark mt-3">
        Actualizar
    </button>
</form>

@endsection
