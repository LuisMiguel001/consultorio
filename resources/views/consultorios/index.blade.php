@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #0d47a1;
            --primary-dark: #002171;
            --primary-light: #e8f1fb;
            --primary-soft: #f4f8fd;
        }

        body {
            background: var(--primary-soft);
        }

        .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .card-header {
            background: linear-gradient(135deg,
                    var(--primary-color),
                    var(--primary-dark));
            color: white;
        }

        .table thead {
            background: var(--primary-light);
        }

        .table-hover tbody tr:hover {
            background: var(--primary-light);
        }

        .btn-main {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
        }

        .btn-main:hover {
            background: var(--primary-dark);
            color: white;
        }
    </style>

    <div class="container my-4">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="fas fa-hospital me-2"></i>
                    Consultorios
                </h5>

                <a href="{{ route('consultorios.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-1"></i>
                    Nuevo
                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Consultorio</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Usuarios</th>
                                <th>Doctores</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($consultorios as $consultorio)
                                <tr>

                                    <td>
                                        <strong>
                                            {{ $consultorio->nombre }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $consultorio->telefono }}
                                    </td>

                                    <td>
                                        {{ $consultorio->email }}
                                    </td>

                                    <td>
                                        {{ $consultorio->usuarios_count }}
                                    </td>

                                    <td>
                                        {{ $consultorio->doctores_count }}
                                    </td>

                                    <td>

                                        @if ($consultorio->activo)
                                            <span class="badge bg-success">
                                                Activo
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactivo
                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <div class="d-flex gap-1 justify-content-center">

                                            <!-- Editar - usa bi-pencil en lugar de fa-edit -->
                                            <a href="{{ route('consultorios.edit', $consultorio) }}"
                                                class="btn btn-sm btn-main" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('consultorios.toggle', $consultorio) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="btn btn-sm {{ $consultorio->activo ? 'btn-warning' : 'btn-success' }}"
                                                    title="Activar/Desactivar">
                                                    <i class="bi bi-power"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('consultorios.destroy', $consultorio) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar consultorio?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">

                    {{ $consultorios->links() }}

                </div>

            </div>

        </div>

    </div>
@endsection
