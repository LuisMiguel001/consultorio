@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #0d47a1;
            --primary-dark: #002171;
            --primary-light: #e8f1fb;
            --primary-soft: #f4f8fd;
            --primary-border: #90caf9;
        }

        body {
            background: var(--primary-soft);
        }

        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
        }

        .table thead {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .table-hover tbody tr:hover {
            background: var(--primary-light);
        }

        .badge-role {
            background: var(--primary-color);
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-edit {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
        }

        .btn-edit:hover {
            background: var(--primary-dark);
            color: white;
        }

        .btn-create {
            border-radius: 8px;
        }
    </style>

    <div class="container my-4">
        <div class="card shadow-sm border-0" style="border-radius:16px; background: #f4f8fd;">
            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Usuarios del sistema
                </h5>

                <a href="{{ route('usuarios.create') }}" class="btn btn-light btn-create">
                    <i class="fas fa-user-plus me-1"></i>
                    Nuevo Usuario
                </a>
            </div>

            <div class="card-body">
                <div class="text-end mb-3 text-muted">
                    Total usuarios: <strong>{{ $users->count() }}</strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="px-4">Nombre</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 fw-semibold">
                                        <i class="fas fa-user-circle text-secondary me-1"></i>
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        {{ $user->email }}
                                    </td>

                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge badge-role" style="background: gray">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-edit">
                                            <i class="fas fa-edit"></i>
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
