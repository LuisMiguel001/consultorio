@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Lista de Pacientes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body style="background:#f6f1fa;">

        <div class="container my-4">

            <div class="card shadow-sm border-0" style="border-radius:16px;background:#f6f1fa;">

                <!-- HEADER -->
                <div class="card-header border-0 d-flex justify-content-between align-items-center"
                    style="background:#a97bc9;color:white;border-radius:16px 16px 0 0;">

                    <h5 class="mb-0">Pacientes</h5>

                    <a href="{{ route('pacientes.create') }}" class="btn btn-light" style="border-radius:8px;">
                        Nuevo paciente
                    </a>
                </div>

                <div class="card-body">

                    <!-- BUSCADOR SIMPLE -->
                    <form method="GET" action="{{ route('pacientes.lista') }}">
                        <div class="row gx-2 gy-2 mb-4">
                            <div class="col-md-6">
                                <input type="text" name="buscar" class="form-control"
                                    placeholder="Nombre, apellido o email" value="{{ request('buscar') }}">
                            </div>
                            <div class="col-md-auto">
                                <button class="btn btn-outline-secondary">
                                    Buscar
                                </button>
                            </div>
                            <div class="col-md-auto">
                                <a href="{{ route('pacientes.lista') }}" class="btn btn-outline-secondary">
                                    Limpiar
                                </a>
                            </div>
                        </div>
                    </form>

                    @if ($pacientes->count() == 0)
                        <div class="alert alert-warning text-center">
                            No hay pacientes registrados.
                        </div>
                    @else
                        <div class="text-end mb-2 text-muted">
                            Total de pacientes:
                            <strong>{{ $pacientes->count() }}</strong>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover"
                                style="background:white;border-radius:12px;overflow:hidden;">

                                <thead style="background:#ede4f5;color:#4b2e83;">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($pacientes as $p)
                                        <tr>
                                            <td>{{ $p->nombre }}</td>
                                            <td>{{ $p->apellido }}</td>
                                            <td>{{ $p->telefono }}</td>
                                            <td>{{ $p->email }}</td>
                                            <td>{{ $p->created_at->format('d/m/Y') }}</td>

                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">

                                                    <a href="{{ route('pacientes.show', $p->id) }}" class="btn btn-sm"
                                                        style="background:#bfa2db;color:white;border-radius:6px;">
                                                        Ver Info.
                                                    </a>

                                                    <!-- Reemplaza el enlace de editar -->
                                                    <a href="{{ route('pacientes.edit', $p->id) }}" class="btn btn-sm"
                                                        style="background:#a97bc9;color:white;border-radius:6px;">
                                                        Editar
                                                    </a>

                                                    <!-- Y el formulario de eliminación -->
                                                    <form action="{{ route('pacientes.destroy', $p->id) }}" method="POST"
                                                        onsubmit="return confirm('¿Seguro que desea eliminar?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm"
                                                            style="background:#d16ba5;color:white;border-radius:6px;">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </body>

    </html>
@endsection
