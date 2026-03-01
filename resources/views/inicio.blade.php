@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Pacientes</h6>
                            <h2 class="mb-0">150</h2>
                        </div>
                        <i class="bi bi-people-fill fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Citas Hoy</h6>
                            <h2 class="mb-0">12</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:15px; background: linear-gradient(135deg, #5f2c82 0%, #49a09d 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Nuevos</h6>
                            <h2 class="mb-0">8</h2>
                        </div>
                        <i class="bi bi-person-plus fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:15px; background: linear-gradient(135deg, #373b44 0%, #4286f4 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Ingresos Hoy</h6>
                            <h2 class="mb-0">$45.2k</h2>
                        </div>
                        <i class="bi bi-graph-up fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-graph-up fs-1 text-secondary opacity-25"></i>
                        <p class="mt-3 text-secondary">Gráfico de actividad</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Próximas Citas</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">María González</h6>
                                    <small class="text-secondary">10:30 AM</small>
                                </div>
                                <span class="badge bg-success">Hoy</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0">Juan Pérez</h6>
                                    <small class="text-secondary">2:00 PM</small>
                                </div>
                                <span class="badge bg-warning text-dark">Mañana</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
