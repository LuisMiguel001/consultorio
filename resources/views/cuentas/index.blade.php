@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="card border-0 shadow">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                Facturas / Cuentas Pacientes
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Paciente</th>

                            <th>Fecha</th>

                            <th>Total</th>

                            <th>Estado</th>

                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($cuentas as $cuenta)

                            <tr>

                                <td>
                                    {{ $cuenta->id }}
                                </td>

                                <td>
                                    {{ $cuenta->paciente->nombre }}
                                    {{ $cuenta->paciente->apellido }}
                                </td>

                                <td>
                                    {{ $cuenta->created_at->format('d/m/Y') }}
                                </td>

                                <td class="fw-bold text-success">
                                    RD$
                                    {{ number_format($cuenta->total, 2) }}
                                </td>

                                <td>

                                    @if($cuenta->estado == 'pagado')

                                        <span class="badge bg-success">
                                            Pagado
                                        </span>

                                    @elseif($cuenta->estado == 'parcial')

                                        <span class="badge bg-warning">
                                            Parcial
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Pendiente
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('cuentas.show', $cuenta->id) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Ver
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center">

                                    No hay cuentas registradas.

                                </td>

                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
