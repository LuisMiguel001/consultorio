@extends('layouts.app')

@section('content')

<h4>Usuarios</h4>

<a href="{{ route('usuarios.create') }}" class="btn btn-dark mb-3">
    Nuevo Usuario
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @foreach($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('usuarios.edit',$user) }}"
                   class="btn btn-sm btn-warning">
                   Editar
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
