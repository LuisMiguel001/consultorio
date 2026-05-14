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
                <div class="row mb-4">

                    <div class="col-md-3">
                        <div class="card p-3">
                            <h6>Consultorios activos</h6>
                            <h3>{{ $consultoriosActivos }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-3">
                            <h6>Suscripciones vencidas</h6>
                            <h3>{{ $suscripcionesVencidas }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-3">
                            <h6>Ingresos del mes</h6>
                            <h3>RD$ {{ number_format($ingresosMes, 2) }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-3">
                            <h6>Próximos a vencer</h6>
                            <h3>{{ $proximosVencer }}</h3>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Consultorio</th>
                                <th>Plan</th>
                                <th>Estado Suscripción</th>
                                <th>Vence</th>
                                <th>Días</th>
                                <th>Usuarios</th>
                                <th>Último Pago</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($consultorios as $consultorio)
                                @php
                                    $suscripcion = $consultorio->suscripcionActiva;

                                    $estado = $consultorio->estadoSuscripcion();

                                    $plan = optional($suscripcion)->plan;

                                    $ultimoPago = optional($suscripcion)->pagos()?->latest()?->first();
                                @endphp

                                <tr>

                                    {{-- CONSULTORIO --}}
                                    <td>
                                        <div class="fw-bold">
                                            {{ $consultorio->nombre }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $consultorio->email }}
                                        </small>
                                    </td>

                                    {{-- PLAN --}}
                                    <td>

                                        @if ($plan)
                                            <span class="badge bg-primary">
                                                {{ $plan->nombre }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Sin plan
                                            </span>
                                        @endif

                                    </td>

                                    {{-- ESTADO SUSCRIPCIÓN --}}
                                    <td>

                                        <span class="badge bg-{{ $estado['clase'] }}">
                                            {{ $estado['icono'] }}
                                            {{ $estado['mensaje'] }}
                                        </span>

                                    </td>

                                    {{-- FECHA VENCIMIENTO --}}
                                    <td>

                                        @if ($suscripcion)
                                            {{ $suscripcion->fecha_fin->format('d/m/Y') }}
                                        @else
                                            —
                                        @endif

                                    </td>

                                    {{-- DÍAS RESTANTES --}}
                                    <td>

                                        @if ($suscripcion)
                                            <strong>
                                                {{ $suscripcion->diasRestantes() }}
                                            </strong> días
                                        @else
                                            —
                                        @endif

                                    </td>

                                    {{-- USUARIOS --}}
                                    <td>

                                        <div class="small">

                                            <div>
                                                👨‍⚕️
                                                {{ $consultorio->doctores()->count() }}
                                                /
                                                {{ optional($plan)->max_doctores ?? '∞' }}
                                            </div>

                                            <div>
                                                👩‍💼
                                                {{ $consultorio->secretarias()->count() }}
                                                /
                                                {{ optional($plan)->max_secretarias ?? '∞' }}
                                            </div>

                                            <div>
                                                👩‍⚕️
                                                {{ $consultorio->enfermeras()->count() }}
                                                /
                                                {{ optional($plan)->max_enfermeras ?? '∞' }}
                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        @if ($ultimoPago)
                                            <div>
                                                RD$
                                                {{ number_format($ultimoPago->monto, 2) }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $ultimoPago->fecha_pago?->format('d/m/Y') }}
                                            </small>
                                        @else
                                            <span class="text-danger">
                                                Sin pagos
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ESTADO CONSULTORIO --}}
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

                                    {{-- ACCIONES --}}
                                    <td class="text-center">

                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <a href="{{ route('consultorios.show', $consultorio) }}"
                                                class="btn btn-sm btn-info text-white" title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            {{-- EDITAR --}}
                                            <a href="{{ route('consultorios.edit', $consultorio) }}"
                                                class="btn btn-sm btn-main">

                                                <i class="bi bi-pencil"></i>

                                            </a>

                                            {{-- ACTIVAR / DESACTIVAR --}}
                                            <form action="{{ route('consultorios.toggle', $consultorio) }}" method="POST">

                                                @csrf

                                                <button
                                                    class="btn btn-sm {{ $consultorio->activo ? 'btn-warning' : 'btn-success' }}">

                                                    <i class="bi bi-power"></i>

                                                </button>

                                            </form>

                                            {{-- REGISTRAR PAGO --}}
                                            <a href="{{ route('pagos.create') }}?consultorio={{ $consultorio->id }}"
                                                class="btn btn-sm btn-success">

                                                <i class="bi bi-cash"></i>

                                            </a>

                                            {{-- VER SUSCRIPCIÓN --}}
                                            @if ($suscripcion)
                                                <a href="{{ route('suscripciones.edit', $suscripcion) }}"
                                                    class="btn btn-sm btn-primary">

                                                    <i class="bi bi-credit-card"></i>

                                                </a>
                                            @endif

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
