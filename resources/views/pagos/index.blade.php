@extends('layouts.app')

@section('content')

<style>
    :root{
        --primary:#0d47a1;
        --dark:#002171;
        --light:#e8f1fb;
        --soft:#f4f8fd;
    }

    body{
        background:var(--soft);
    }

    .card-panel{
        border:none;
        border-radius:18px;
        box-shadow:0 5px 18px rgba(0,0,0,.08);
    }

    .table thead{
        background:var(--light);
    }

    .badge-status{
        font-size:.8rem;
        padding:7px 10px;
    }

    .stat-card{
        border-radius:18px;
        padding:20px;
        color:white;
        min-height:120px;
    }

    .bg-main{
        background:linear-gradient(135deg,#0d47a1,#002171);
    }

    .bg-success-soft{
        background:linear-gradient(135deg,#198754,#146c43);
    }

    .bg-warning-soft{
        background:linear-gradient(135deg,#ffc107,#fd7e14);
    }

    .bg-danger-soft{
        background:linear-gradient(135deg,#dc3545,#842029);
    }
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Administración de Pagos
            </h2>

            <div class="text-muted">
                Control de transferencias y suscripciones
            </div>
        </div>

        <a href="{{ route('pagos.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Registrar Pago
        </a>

    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-card bg-main">
                <h3>{{ $estadisticas['total'] }}</h3>
                <div>Total Pagos</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-warning-soft">
                <h3>{{ $estadisticas['pendientes'] }}</h3>
                <div>Pendientes</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-success-soft">
                <h3>{{ $estadisticas['aprobados'] }}</h3>
                <div>Aprobados</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-danger-soft">
                <h3>
                    RD$
                    {{ number_format($estadisticas['ingresosMes'],2) }}
                </h3>

                <div>Ingresos del Mes</div>
            </div>
        </div>

    </div>

    {{-- FILTROS --}}
    <div class="card card-panel mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-2">

                    <div class="col-md-3">
                        <input type="text"
                               name="consultorio"
                               class="form-control"
                               placeholder="Buscar consultorio"
                               value="{{ request('consultorio') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="estado"
                                class="form-select">

                            <option value="">
                                Todos
                            </option>

                            <option value="pendiente"
                                {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="aprobado"
                                {{ request('estado') == 'aprobado' ? 'selected' : '' }}>
                                Aprobado
                            </option>

                            <option value="rechazado"
                                {{ request('estado') == 'rechazado' ? 'selected' : '' }}>
                                Rechazado
                            </option>

                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date"
                               name="desde"
                               class="form-control"
                               value="{{ request('desde') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date"
                               name="hasta"
                               class="form-control"
                               value="{{ request('hasta') }}">
                    </div>

                    <div class="col-md-3 d-grid">
                        <button class="btn btn-primary">
                            Filtrar
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLA --}}
    <div class="card card-panel">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>Consultorio</th>
                            <th>Plan</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Estado</th>
                            <th class="text-center">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pagos as $pago)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $pago->consultorio->nombre }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $pago->plan->nombre }}
                                </td>

                                <td>
                                    RD$
                                    {{ number_format($pago->monto,2) }}
                                </td>

                                <td>
                                    {{ $pago->metodo_pago }}
                                </td>

                                <td>
                                    {{ $pago->referencia }}
                                </td>

                                <td>
                                    {{ $pago->fecha_pago?->format('d/m/Y') }}
                                </td>

                                <td>

                                    @if($pago->comprobante)

                                        <a href="{{ asset('storage/' . $pago->comprobante) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-info text-white">

                                            <i class="bi bi-image"></i>

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            Sin archivo
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($pago->estado == 'pendiente')

                                        <span class="badge bg-warning badge-status">
                                            Pendiente
                                        </span>

                                    @elseif($pago->estado == 'aprobado')

                                        <span class="badge bg-success badge-status">
                                            Aprobado
                                        </span>

                                    @else

                                        <span class="badge bg-danger badge-status">
                                            Rechazado
                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    <div class="d-flex gap-1 justify-content-center">

                                        @if($pago->estado == 'pendiente')

                                            <form action="{{ route('pagos.aprobar',$pago) }}" method="POST" title="Aprobar">
                                                @csrf

                                                <button class="btn btn-success btn-sm">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('pagos.rechazar',$pago) }}"
                                                  method="POST" title="Rechazar">
                                                @csrf

                                                <button class="btn btn-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    No hay pagos registrados

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-3">
        {{ $pagos->links() }}
    </div>

</div>

@endsection
