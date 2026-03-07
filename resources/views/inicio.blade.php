@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- TARJETAS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm"
                style="border-radius:15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Pacientes</h6>
                            <h2 class="mb-0">{{ $totalPacientes }}</h2>
                        </div>
                        <i class="bi bi-people-fill fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm"
                style="border-radius:15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Citas Hoy</h6>
                            <h2 class="mb-0">{{ $citasHoy }}</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm"
                style="border-radius:15px; background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Prom. Semanal Citas</h6>
                            <h2 class="mb-0">{{ $promedioSemanal }}</h2>
                        </div>
                        <i class="bi bi-bar-chart-line fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm"
                style="border-radius:15px; background: linear-gradient(135deg, #373b44 0%, #4286f4 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Prom. Mensual</h6>
                            <h2 class="mb-0">{{ $promedioMensual }}</h2>
                        </div>
                        <i class="bi bi-graph-up fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRÁFICO + PRÓXIMAS CITAS --}}
    <div class="row g-3">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Citas por Mes — {{ now()->year }}</h5>
                </div>
                <div class="card-body">
                    <div style="position:relative; height:320px;">
                        <canvas id="graficoPacientes"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Próximas Citas</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($proximasCitas as $cita)
                        @php
                            $esHoy = \Carbon\Carbon::parse($cita->fecha)->isToday();
                            $esMañana = \Carbon\Carbon::parse($cita->fecha)->isTomorrow();
                            $etiqueta = $esHoy ? 'Hoy' : ($esMañana ? 'Mañana' : \Carbon\Carbon::parse($cita->fecha)->format('d/m'));
                            $badge = $esHoy ? 'bg-success' : ($esMañana ? 'bg-warning text-dark' : 'bg-secondary');
                        @endphp
                        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                            <div>
                                <h6 class="mb-0">{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</h6>
                                <small class="text-secondary">
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}
                                    @if($cita->motivo)
                                        · {{ Str::limit($cita->motivo, 25) }}
                                    @endif
                                </small>
                            </div>
                            <span class="badge {{ $badge }}">{{ $etiqueta }}</span>
                        </div>
                    @empty
                        <div class="text-center text-secondary py-5">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                            No hay citas próximas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const datos = @json($citasPorMes);

    const nombresMeses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    const labels = datos.map(d => nombresMeses[d.mes - 1]);
    const totales = datos.map(d => d.total);

    new Chart(document.getElementById('graficoPacientes'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Citas',
                data: totales,
                backgroundColor: [
                    '#667eea','#f093fb','#f5576c','#5f2c82',
                    '#49a09d','#4286f4','#ffa502','#2ed573',
                    '#ff6b81','#1e90ff','#a55eea','#20bf6b'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#f0f0f0' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

});
</script>
@endsection
