@extends('layouts.app')

@section('content')

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

        .card {
            background: var(--primary-soft);
        }

        .card-header {
            background: var(--primary-color);
            color: white;
        }

        .btn-light {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .table thead {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .table-hover tbody tr:hover {
            background-color: var(--primary-soft);
        }

        .btn-restore {
            background: var(--primary-border);
            color: white;
            border-radius: 6px;
        }

        .pagination .page-link {
            color: var(--primary-color);
            border-radius: 8px;
            border: 1px solid var(--primary-border);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-link:hover {
            background: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-dark);
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }
    </style>

    <div class="container my-4">

        <div class="card shadow-sm border-0" style="border-radius:16px;">

            <!-- HEADER -->
            <div class="card-header border-0 d-flex justify-content-between align-items-center"
                style="border-radius:18px 18px 0 0;">
                <h5 class="mb-0">Pacientes Archivados</h5>
                <a href="{{ route('pacientes.lista') }}" class="btn btn-light">
                    Volver a pacientes
                </a>
            </div>

            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($pacientes->count() == 0)
                    <div class="alert alert-warning text-center">
                        No hay pacientes archivados.
                    </div>
                @else
                    <div class="text-end mb-2 text-muted">
                        Total archivados: <strong>{{ $pacientes->total() }}</strong>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle"
                            style="background:white;border-radius:12px;overflow:hidden;">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>NSS</th>
                                    <th>Archivado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pacientes as $p)
                                    <tr>
                                        <td>{{ $p->nombre }} {{ $p->apellido }}</td>
                                        <td>{{ $p->telefono }}</td>
                                        <td>{{ $p->nss }}</td>
                                        <td>{{ $p->deleted_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('pacientes.restaurar', $p->id) }}" method="POST"
                                                onsubmit="return confirm('¿Restaurar este paciente?')">
                                                @csrf
                                                <button class="btn btn-sm btn-restore">Restaurar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                        <div class="text-muted small mb-3 mb-md-0">
                            Mostrando {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }} de
                            {{ $pacientes->total() }}
                        </div>
                        <div>
                            {{ $pacientes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
