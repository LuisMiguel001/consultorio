@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Lista de Pacientes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #0d47a1;
                --primary-dark: #002171;
                --primary-light: #e8f1fb;
                --primary-soft: #f4f8fd;
                --primary-border: #90caf9;
                --text-primary: #0a1a2f;
                --text-secondary: #1565c0;
            }

            body {
                background: var(--primary-soft);
            }

            .card-header {
                background: var(--primary-color);
                color: white;
            }

            .table thead {
                background: var(--primary-light);
                color: var(--primary-dark);
            }

            .table-hover tbody tr:hover {
                background-color: var(--primary-light);
            }

            .btn-view {
                background: var(--primary-border);
                color: white;
            }

            .btn-edit {
                background: var(--primary-color);
                color: white;
            }

            .btn-archive {
                background: var(--primary-dark);
                color: white;
            }

            .btn:hover {
                opacity: 0.85;
            }

            .pagination .page-link {
                color: var(--primary-color);
            }

            .pagination .page-item.active .page-link {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }
        </style>
    </head>

    <body>
        <div class="container my-4">

            <div class="card shadow-sm border-0" style="border-radius:18px; background:#f4f8fd;">

                <!-- HEADER -->
                <div class="card-header border-0 d-flex justify-content-between align-items-center"
                    style=" color: white; border-radius:18px 18px 0 0; background: #0d47a1;">
                    <h5 class="mb-0">Pacientes</h5>
                    <a href="{{ route('pacientes.create') }}" class="btn btn-light" style="border-radius:8px;">
                        Nuevo paciente
                    </a>
                </div>

                <div class="card-body">
                    <!-- BUSCADOR SIMPLE -->
                    <form method="GET" action="{{ route('pacientes.lista') }}">
                        <div class="row gx-2 gy-2 mb-4">
                            <div class="col-md-5">
                                <input type="text" name="buscar" class="form-control"
                                    placeholder="Nombre, apellido, cédula, NSS, teléfono o correo"
                                    value="{{ request('buscar') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_desde" class="form-control"
                                    value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="fecha_hasta" class="form-control"
                                    value="{{ request('fecha_hasta') }}">
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
                            Total de pacientes: <strong>{{ $pacientes->Total() }}</strong>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-hover"
                                style="background:rgb(255, 250, 250);border-radius:12px;overflow:hidden;">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>NSS</th>
                                        <th>Registro</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pacientes as $p)
                                        <tr>
                                            <td>{{ $p->nombre }} {{ $p->apellido }}</td>
                                            <td>{{ $p->telefono }}</td>
                                            <td>{{ $p->nss }}</td>
                                         <td>{{ $p->created_at ? $p->created_at->format('d/m/Y') : '' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('pacientes.show', $p->id) }}"
                                                        class="btn btn-sm btn-view">
                                                        Ver Info.
                                                    </a>
                                                    <a href="{{ route('pacientes.edit', $p->id) }}"
                                                        class="btn btn-sm btn-edit">
                                                        Editar
                                                    </a>
                                                    <form action="{{ route('pacientes.destroy', $p->id) }}" method="POST"
                                                        onsubmit="return confirm('¿Seguro que desea archivar este paciente?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-archive">
                                                            Archivar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                            <div class="text-muted small mb-3 mb-md-0">
                                Mostrando {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }} de
                                {{ $pacientes->total() }} pacientes
                            </div>

                            <div class="pagination-wrapper">
                                {{ $pacientes->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
