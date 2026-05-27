@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">Servicios</h3>
            <small class="text-muted">
                Servicios médicos configurados
            </small>
        </div>

        <a href="{{ route('servicios.create') }}"
           class="btn btn-primary">
            Nuevo Servicio
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <table class="table align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($servicios as $servicio)

                        <tr>

                            <td>
                                {{ $servicio->nombre }}
                            </td>

                            <td>
                                RD$
                                {{ number_format($servicio->precio,2) }}
                            </td>

                            <td>

                                @if($servicio->activo)

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('servicios.edit',$servicio->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <form method="POST"
                                          action="{{ route('servicios.destroy',$servicio->id) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-4">
                                No hay servicios registrados
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
