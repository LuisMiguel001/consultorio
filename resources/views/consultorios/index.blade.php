@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary: #0d47a1;
            --primary-dark: #002171;
            --soft: #f4f8fd;
            --border: #edf2f7;
        }

        body {
            background: var(--soft);
        }

        .main-card {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .main-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 25px;
        }

        .stat-card {
            border: none;
            border-radius: 18px;
            padding: 22px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
            transition: .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: #e8f1fb;
            color: var(--primary);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border: none;
            background: #f8fbff;
            color: #4a5568;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 18px 14px;
        }

        .table tbody td {
            padding: 18px 14px;
            border-color: var(--border);
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: #f8fbff;
        }

        .consultorio-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .btn-main {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            border-radius: 10px;
        }

        .btn-main:hover {
            color: white;
            opacity: .95;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-soft {
            padding: 8px 12px;
            border-radius: 30px;
            font-weight: 500;
        }

        .modal-content {
            border: none;
            border-radius: 22px;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
        }

        .consumo-box {
            border-radius: 18px;
            padding: 18px;
            background: #f8fbff;
            border: 1px solid #e3edf8;
        }

        .progress {
            height: 10px;
            border-radius: 20px;
        }

        .usage-label {
            font-size: .9rem;
            color: #64748b;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="main-card">

            <div class="main-header d-flex justify-content-between align-items-center">

                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-hospital me-2"></i>
                        Gestión de Consultorios
                    </h3>

                    <div class="opacity-75">
                        Administración general del sistema
                    </div>
                </div>

                <a href="{{ route('consultorios.create') }}" class="btn btn-light rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>
                    Nuevo Consultorio
                </a>

            </div>

            <div class="p-4">

                {{-- STATS --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-3">
                        <div class="stat-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="text-muted small">
                                        Consultorios Activos
                                    </div>

                                    <h2 class="fw-bold mb-0 mt-2">
                                        {{ $consultoriosActivos }}
                                    </h2>
                                </div>

                                <div class="stat-icon">
                                    <i class="fas fa-hospital"></i>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="text-muted small">
                                        Suscripciones Vencidas
                                    </div>

                                    <h2 class="fw-bold mb-0 mt-2">
                                        {{ $suscripcionesVencidas }}
                                    </h2>
                                </div>

                                <div class="stat-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="text-muted small">
                                        Ingresos del Mes
                                    </div>

                                    <h2 class="fw-bold mb-0 mt-2">
                                        RD$ {{ number_format($ingresosMes, 2) }}
                                    </h2>
                                </div>

                                <div class="stat-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="text-muted small">
                                        Próximos a Vencer
                                    </div>

                                    <h2 class="fw-bold mb-0 mt-2">
                                        {{ $proximosVencer }}
                                    </h2>
                                </div>

                                <div class="stat-icon">
                                    <i class="fas fa-bell"></i>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- TABLA --}}
                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Consultorio</th>
                                <th>Plan</th>
                                <th>Vencimiento</th>
                                <th>Usuarios</th>
                                <th>Último Pago</th>
                                <th>Estado</th>
                                <th width="240">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($consultorios as $consultorio)
                                @php

                                    $suscripcion = $consultorio->suscripcionActiva;

                                    $estado = $consultorio->estadoSuscripcion();

                                    $plan = optional($suscripcion)->plan;

                                    $ultimoPago = optional($suscripcion)->pagos()?->latest()?->first();

                                    $consumo = $consultorio->consumoActual();

                                    $consultas = $consumo->consultas ?? 0;

                                    $citas = App\Models\Cita::where('consultorio_id', $consultorio->id)->count();

                                    $pacientes = App\Models\Paciente::where(
                                        'consultorio_id',
                                        $consultorio->id,
                                    )->count();

                                    $whatsapp = $consumo->mensajes_whatsapp ?? 0;
                                @endphp

                                <tr>

                                    {{-- CONSULTORIO --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="consultorio-avatar">
                                                {{ strtoupper(substr($consultorio->nombre, 0, 1)) }}
                                            </div>

                                            <div>

                                                <div class="fw-bold">
                                                    {{ $consultorio->nombre }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ $consultorio->email }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    {{-- PLAN --}}
                                    <td>

                                        @if ($plan)
                                            <span class="badge bg-primary badge-soft">
                                                {{ $plan->nombre }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger badge-soft">
                                                Sin plan
                                            </span>
                                        @endif

                                    </td>

                                    {{-- VENCIMIENTO --}}
                                    <td>

                                        @if ($suscripcion)
                                            <div class="fw-semibold">
                                                {{ $suscripcion->fecha_fin->format('d/m/Y') }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $suscripcion->diasRestantes() }} días restantes
                                            </small>
                                        @else
                                            —
                                        @endif

                                    </td>

                                    {{-- USUARIOS --}}
                                    <td>

                                        <div class="small">

                                            <div>
                                                👨‍⚕️ {{ $consultorio->doctores()->count() }}
                                            </div>

                                            <div>
                                                👩‍💼 {{ $consultorio->secretarias()->count() }}
                                            </div>

                                            <div>
                                                👩‍⚕️ {{ $consultorio->enfermeras()->count() }}
                                            </div>

                                        </div>

                                    </td>

                                    {{-- PAGO --}}
                                    <td>

                                        @if ($ultimoPago)
                                            <div class="fw-bold">
                                                RD$ {{ number_format($ultimoPago->monto, 2) }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $ultimoPago->fecha_pago?->format('d/m/Y') }}
                                            </small>
                                        @else
                                            <span class="badge bg-danger">
                                                Sin pagos
                                            </span>
                                        @endif

                                    </td>

                                    {{-- ESTADO --}}
                                    <td>

                                        @if ($consultorio->activo)
                                            <span class="badge bg-success badge-soft">
                                                Activo
                                            </span>
                                        @else
                                            <span class="badge bg-danger badge-soft">
                                                Inactivo
                                            </span>
                                        @endif

                                    </td>

                                    {{-- ACCIONES --}}
                                    <td>

                                        <div class="d-flex gap-2 flex-wrap">

                                            <a href="{{ route('consultorios.show', $consultorio) }}"
                                                class="btn btn-info text-white btn-action">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('consultorios.edit', $consultorio) }}"
                                                class="btn btn-main btn-action">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button class="btn btn-dark btn-action" data-bs-toggle="modal"
                                                data-bs-target="#consumoModal{{ $consultorio->id }}">

                                                <i class="bi bi-bar-chart"></i>

                                            </button>

                                            <a href="{{ route('pagos.create') }}?consultorio={{ $consultorio->id }}"
                                                class="btn btn-success btn-action">
                                                <i class="bi bi-cash"></i>
                                            </a>

                                            <form action="{{ route('consultorios.toggle', $consultorio) }}" method="POST">

                                                @csrf

                                                <button class="btn btn-warning btn-action">

                                                    <i class="bi bi-power"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                                {{-- MODAL CONSUMO --}}
                                <div class="modal fade" id="consumoModal{{ $consultorio->id }}" tabindex="-1">

                                    <div class="modal-dialog modal-lg modal-dialog-centered">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    📊 Consumo del Plan
                                                </h5>

                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            <div class="modal-body p-4">

                                                <div class="mb-4">

                                                    <h4 class="fw-bold mb-1">
                                                        {{ $consultorio->nombre }}
                                                    </h4>

                                                    <div class="text-muted">
                                                        {{ $plan->nombre ?? 'Sin plan' }}
                                                    </div>

                                                </div>

                                                {{-- CONSULTAS --}}
                                                <div class="consumo-box mb-3">

                                                    <div class="d-flex justify-content-between mb-2">

                                                        <span class="fw-semibold">
                                                            🩺 Consultas
                                                        </span>

                                                        <span>
                                                            {{ $consultas }}
                                                            /
                                                            {{ $plan->max_consultas ?? '∞' }}
                                                        </span>

                                                    </div>

                                                    @php
                                                        $porcentajeConsultas =
                                                            $plan && $plan->max_consultas
                                                                ? min(($consultas / $plan->max_consultas) * 100, 100)
                                                                : 0;
                                                    @endphp

                                                    <div class="progress">
                                                        <div class="progress-bar"
                                                            style="width: {{ $porcentajeConsultas }}%">
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- PACIENTES --}}
                                                <div class="consumo-box mb-3">

                                                    <div class="d-flex justify-content-between mb-2">

                                                        <span class="fw-semibold">
                                                            👥 Pacientes
                                                        </span>

                                                        <span>
                                                            {{ $pacientes }}
                                                            /
                                                            {{ $plan->max_pacientes ?? '∞' }}
                                                        </span>

                                                    </div>

                                                    @php
                                                        $porcentajePacientes =
                                                            $plan && $plan->max_pacientes
                                                                ? min(($pacientes / $plan->max_pacientes) * 100, 100)
                                                                : 0;
                                                    @endphp

                                                    <div class="progress">

                                                        <div class="progress-bar bg-info"
                                                            style="width: {{ $porcentajePacientes }}%">
                                                        </div>

                                                    </div>
                                                </div>

                                                {{-- CITAS --}}
                                                <div class="consumo-box mb-3">

                                                    <div class="d-flex justify-content-between mb-2">

                                                        <span class="fw-semibold">
                                                            📅 Citas
                                                        </span>

                                                        <span>
                                                            {{ $citas }}
                                                            /
                                                            {{ $plan->max_citas ?? '∞' }}
                                                        </span>

                                                    </div>

                                                    @php
                                                        $porcentajeCitas =
                                                            $plan && $plan->max_citas
                                                                ? min(($citas / $plan->max_citas) * 100, 100)
                                                                : 0;
                                                    @endphp

                                                    <div class="progress">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ $porcentajeCitas }}%">
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- WHATSAPP --}}
                                                <div class="consumo-box">

                                                    <div class="d-flex justify-content-between mb-2">

                                                        <span class="fw-semibold">
                                                            📱 WhatsApp
                                                        </span>

                                                        <span>
                                                            {{ $whatsapp }}
                                                            /
                                                            {{ $plan->max_mensajes_whatsapp ?? '∞' }}
                                                        </span>

                                                    </div>

                                                    @php
                                                        $porcentajeWhatsapp =
                                                            $plan && $plan->max_mensajes_whatsapp
                                                                ? min(
                                                                    ($whatsapp / $plan->max_mensajes_whatsapp) * 100,
                                                                    100,
                                                                )
                                                                : 0;
                                                    @endphp

                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning"
                                                            style="width: {{ $porcentajeWhatsapp }}%">
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-4">
                    {{ $consultorios->links() }}
                </div>

            </div>

        </div>

    </div>
@endsection
