@extends('layouts.app')

@section('content')

<style>
    .stat-card{
        border-radius:16px;
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
        background:linear-gradient(135deg,#fd7e14,#dc3545);
    }

    .info-card{
        border:none;
        border-radius:16px;
        box-shadow:0 4px 15px rgba(0,0,0,.08);
    }

    .table td{
        vertical-align:middle;
    }
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                {{ $consultorio->nombre }}
            </h2>

            <div class="text-muted">
                {{ $consultorio->email }}
            </div>
        </div>

        <div>
            @if($consultorio->activo)
                <span class="badge bg-success fs-6">
                    Activo
                </span>
            @else
                <span class="badge bg-danger fs-6">
                    Inactivo
                </span>
            @endif
        </div>

    </div>

    {{-- ESTADÍSTICAS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="stat-card bg-main">
                <h3>{{ $estadisticas['usuarios'] }}</h3>
                <div>Total Usuarios</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-success-soft">
                <h3>
                    {{ $estadisticas['doctores'] }}
                    /
                    {{ optional($plan)->max_doctores ?? '∞' }}
                </h3>

                <div>Doctores</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-main">
                <h3>
                    {{ $estadisticas['secretarias'] }}
                    /
                    {{ optional($plan)->max_secretarias ?? '∞' }}
                </h3>

                <div>Secretarias</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-warning-soft">
                <h3>
                    {{ $suscripcion?->diasRestantes() ?? 0 }}
                </h3>

                <div>Días Restantes</div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- INFORMACIÓN SUSCRIPCIÓN --}}
        <div class="col-lg-4 mb-4">

            <div class="card info-card">

                <div class="card-header">
                    <strong>
                        Suscripción
                    </strong>
                </div>

                <div class="card-body">

                    @if($suscripcion)

                        <div class="mb-3">
                            <small class="text-muted">
                                Plan
                            </small>

                            <h5>
                                {{ $plan->nombre }}
                            </h5>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                Estado
                            </small>

                            <div>
                                <span class="badge bg-success">
                                    {{ $suscripcion->estado }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                Fecha inicio
                            </small>

                            <div>
                                {{ $suscripcion->fecha_inicio }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                Fecha vencimiento
                            </small>

                            <div>
                                {{ $suscripcion->fecha_fin }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                Próximo pago
                            </small>

                            <div>
                                {{ $suscripcion->proximo_pago }}
                            </div>
                        </div>

                    @else

                        <div class="alert alert-danger">
                            Sin suscripción activa
                        </div>

                    @endif

                </div>

            </div>

        </div>

        {{-- PAGOS --}}
        <div class="col-lg-8">

            <div class="card info-card">

                <div class="card-header d-flex justify-content-between">

                    <strong>
                        Historial de Pagos
                    </strong>

                    <a href="{{ route('pagos.create') }}?consultorio={{ $consultorio->id }}"
                       class="btn btn-primary btn-sm">
                        Registrar Pago
                    </a>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                    <th>Referencia</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($pagos as $pago)

                                    <tr>

                                        <td>
                                            {{ $pago->fecha_pago }}
                                        </td>

                                        <td>
                                            RD$
                                            {{ number_format($pago->monto,2) }}
                                        </td>

                                        <td>
                                            {{ $pago->metodo_pago }}
                                        </td>

                                        <td>

                                            @if($pago->estado == 'aprobado')
                                                <span class="badge bg-success">
                                                    Aprobado
                                                </span>
                                            @elseif($pago->estado == 'pendiente')
                                                <span class="badge bg-warning">
                                                    Pendiente
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    Rechazado
                                                </span>
                                            @endif

                                        </td>

                                        <td>
                                            {{ $pago->referencia }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            Sin pagos registrados
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- USUARIOS --}}
    <div class="card info-card mt-4">

        <div class="card-header">
            <strong>
                Usuarios del Consultorio
            </strong>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($consultorio->usuarios as $usuario)

                            <tr>

                                <td>
                                    {{ $usuario->name }}
                                </td>

                                <td>
                                    {{ $usuario->email }}
                                </td>

                                <td>

                                    @foreach($usuario->roles as $rol)

                                        <span class="badge bg-primary">
                                            {{ $rol->name }}
                                        </span>

                                    @endforeach

                                </td>

                                <td>

                                    @if($usuario->activo)
                                        <span class="badge bg-success">
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactivo
                                        </span>
                                    @endif

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
