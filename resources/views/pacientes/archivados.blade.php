@extends('layouts.app')

@section('content')

    <div class="container my-4">

        <div class="card shadow-sm border-0" style="border-radius:16px;background:#f6f1fa;">

            <!-- HEADER -->
            <div class="card-header border-0 d-flex justify-content-between align-items-center"
                style="background:#a97bc9;color:white;border-radius:16px 16px 0 0;">

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
                        Total archivados:
                        <strong>{{ $pacientes->total() }}</strong>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle"
                            style="background:white;border-radius:12px;overflow:hidden;">

                            <thead style="background:#ede4f5;color:#4b2e83;">
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

                                                <button class="btn btn-sm"
                                                    style="background:#6fbf73;color:white;border-radius:6px;">
                                                    Restaurar
                                                </button>

                                            </form>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">

                        <div class="text-muted small mb-3 mb-md-0">
                            Mostrando {{ $pacientes->firstItem() }} - {{ $pacientes->lastItem() }}
                            de {{ $pacientes->total() }}
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
