@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        {{-- PRIMERA FILA: 4 tarjetas --}}
        <div class="row g-3 mb-4">
            {{-- 1. Atendidos Hoy --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Atendidos Hoy</h6>
                        <h2 class="mb-0">{{ $atendidosHoy }}</h2>
                        <i class="bi bi-calendar-check fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>

            {{-- 2. Atendidos Semana --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Atendidos Semana</h6>
                        <h2 class="mb-0">{{ $atendidosSemana }}</h2>
                        <i class="bi bi-calendar-week fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>

            {{-- 3. Atendidos Mes --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Atendidos Mes</h6>
                        <h2 class="mb-0">{{ $atendidosMes }}</h2>
                        <i class="bi bi-calendar-month fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>

            {{-- 4. Promedio Semanal --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #373b44 0%, #4286f4 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Promedio Semanal</h6>
                        <h2 class="mb-0">{{ $promedioSemanaReal }}</h2>
                        <i class="bi bi-bar-chart-line fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEGUNDA FILA: 3 tarjetas --}}
        <div class="row g-3 mb-4">
            {{-- 5. Promedio Mensual (NUEVO) --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Promedio Mensual</h6>
                        <h2 class="mb-0">{{ $promedioMensual }}</h2>
                        <i class="bi bi-graph-up-arrow fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>

            {{-- 6. Tasa Asistencia --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Tasa Asistencia</h6>
                        <h2 class="mb-0">{{ $tasaAsistencia }}%</h2>
                        <i class="bi bi-percent fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>

            {{-- 7. Total Pacientes --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius:15px; background: linear-gradient(135deg, #4568DC 0%, #B06AB3 100%);">
                    <div class="card-body text-white position-relative">
                        <h6 class="text-white-50">Total Pacientes</h6>
                        <h2 class="mb-0">{{ number_format($totalPacientes) }}</h2>
                        <i class="bi bi-people-fill fs-1 opacity-50 position-absolute bottom-0 end-0 me-3 mb-3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRÁFICO COMPARATIVO + PRÓXIMAS CITAS (se mantiene igual) --}}
        <div class="row g-3">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">Citas por Mes — {{ now()->year }} (Programadas vs Realizadas)</h5>
                    </div>
                    <div class="card-body">
                        <div style="position:relative; height:320px;">
                            <canvas id="graficoComparativoMensual"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-check me-2 text-primary"></i>
                            Próximas Citas
                        </h5>
                        <span class="badge bg-primary rounded-pill">{{ $proximasCitas->count() }}</span>
                    </div>

                    <div class="card-body p-0">
                        @forelse($proximasCitas as $cita)
                            @php
                                $esHoy = \Carbon\Carbon::parse($cita->fecha)->isToday();
                                $esMañana = \Carbon\Carbon::parse($cita->fecha)->isTomorrow();
                                $etiqueta = $esHoy
                                    ? 'Hoy'
                                    : ($esMañana
                                        ? 'Mañana'
                                        : \Carbon\Carbon::parse($cita->fecha)->format('d/m'));
                                $badge = $esHoy ? 'bg-success' : ($esMañana ? 'bg-warning text-dark' : 'bg-secondary');

                                // Formato de hora más legible
                                $horaFormateada = \Carbon\Carbon::parse($cita->hora)->format('H:i');
                            @endphp

                            <div class="cita-item px-3 py-3 border-bottom hover-bg-light transition-all">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-light rounded-circle d-flex align-items-center justify-content-center me-2"
                                            style="width: 38px; height: 38px; background: #f0f2f5;">
                                            <span class="fw-bold text-primary">
                                                {{ substr($cita->paciente->nombre, 0, 1) }}{{ substr($cita->paciente->apellido, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold">
                                                {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}
                                            </h6>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="badge {{ $badge }}">{{ $etiqueta }}</span>
                                                <small class="text-secondary">
                                                    <i class="bi bi-clock me-1"></i>{{ $horaFormateada }} hrs
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($cita->motivo)
                                    <div class="ms-5 ps-2 mb-2">
                                        <small class="text-secondary">
                                            <i class="bi bi-chat-text me-1"></i>
                                            {{ Str::limit($cita->motivo, 40) }}
                                        </small>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-end gap-2 mt-2">
                                    <a href="{{ route('pacientes.show', $cita->paciente->id) }}"
                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                        title="Ver información del paciente">
                                        <i class="bi bi-info-circle"></i>
                                        <span class="d-none d-md-inline ms-1">Info</span>
                                    </a>
                                    <a href="{{ route('consultas.create', ['id' => $cita->paciente_id, 'cita' => $cita->id]) }}"
                                        class="btn btn-sm btn-success" title="Registrar Historial Clínico">
                                        <i class="bi bi-clipboard-pulse"></i>
                                        <span class="d-none d-md-inline ms-1">Atender</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-secondary py-5">
                                <i class="bi bi-calendar-x fs-1 d-block mb-3 text-muted"></i>
                                <p class="mb-0">No hay citas próximas</p>
                                <small class="text-muted">Las nuevas citas aparecerán aquí</small>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gráfico Comparativo Mensual
            const datosComparativos = @json($citasPorMesComparativo);
            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const labelsComp = datosComparativos.map(d => meses[d.mes - 1]);
            const realizadas = datosComparativos.map(d => d.realizadas);
            const totalesComp = datosComparativos.map(d => d.totales);

            const ctxComp = document.getElementById('graficoComparativoMensual').getContext('2d');
            new Chart(ctxComp, {
                type: 'bar',
                data: {
                    labels: labelsComp,
                    datasets: [{
                        label: 'Citas Realizadas',
                        data: realizadas,
                        backgroundColor: '#4CAF50',
                        borderRadius: 8,
                    }, {
                        label: 'Citas Programadas (Totales)',
                        data: totalesComp,
                        backgroundColor: '#FF9800',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#f0f0f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection

<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }

    .transition-all {
        transition: all 0.2s ease;
    }

    .avatar-circle {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-info {
        border-color: #dee2e6;
        color: #495057;
    }

    .btn-outline-info:hover {
        background-color: #e7f1ff;
        border-color: #0dcaf0;
        color: #0a58ca;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .cita-item .btn span {
            display: none !important;
        }

        .cita-item .btn {
            padding: 0.25rem 0.5rem;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
    }
</style>
