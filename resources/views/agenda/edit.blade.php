@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <div class="card shadow-sm border-0" style="border-radius: 18px; background: white;">

            <!-- HEADER -->
            <div class="card-header border-0"
                style="background: linear-gradient(135deg, #a97bc9, #8e44ad);
            color: white;
            border-radius: 18px 18px 0 0;
            padding: 1.5rem;">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-edit me-2"></i>
                            Editar Procedimiento Cardiovascular
                        </h4>

                        <small class="opacity-75">
                            <i class="fas fa-user-md me-1"></i>
                            Dr. {{ Auth::user()->name }} - Cirugía Cardiovascular
                        </small>
                    </div>

                    <div>
                        <span class="badge bg-light text-dark p-2">
                            <i class="far fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                        </span>
                    </div>

                </div>
            </div>


            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form method="POST" action="{{ route('citas.update', $cita->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- ===================== -->
                    <!-- INFORMACIÓN PROCEDIMIENTO -->
                    <!-- ===================== -->

                    <div class="row mb-4">

                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3"
                                style="color:#4a2c6d;border-bottom:2px solid #e9d5ff;padding-bottom:10px;">

                                <i class="fas fa-clipboard-list me-2"></i>
                                Información del Procedimiento
                            </h5>
                        </div>

                        <!-- PACIENTE -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Paciente
                            </label>

                            <select name="paciente_id" class="form-select form-select-lg" required>

                                @foreach ($pacientes as $paciente)
                                    <option value="{{ $paciente->id }}"
                                        {{ $paciente->id == $cita->paciente_id ? 'selected' : '' }}
                                        data-cedula="{{ $paciente->cedula }}" data-telefono="{{ $paciente->telefono }}">

                                        {{ $paciente->nombre }} {{ $paciente->apellido }}
                                        ({{ $paciente->cedula }})
                                    </option>
                                @endforeach

                            </select>

                            <div class="form-text" id="paciente-info"></div>

                        </div>


                        <!-- PROCEDIMIENTO -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Tipo de Procedimiento
                            </label>

                            <select name="servicio_especifico" class="form-select form-select-lg" required>

                                <option value="Cirugía de Corazón Abierto"
                                    {{ $cita->servicio_especifico == 'Cirugía de Corazón Abierto' ? 'selected' : '' }}>

                                    🫀 Cirugía de Corazón Abierto
                                </option>

                                <option value="Cirugía Venosa con Láser"
                                    {{ $cita->servicio_especifico == 'Cirugía Venosa con Láser' ? 'selected' : '' }}>

                                    💉 Cirugía Venosa con Láser
                                </option>

                                <option value="Insuficiencia Renal"
                                    {{ $cita->servicio_especifico == 'Insuficiencia Renal' ? 'selected' : '' }}>

                                    🩺 Insuficiencia Renal
                                </option>

                                <option value="Tratamiento de Várices"
                                    {{ $cita->servicio_especifico == 'Tratamiento de Várices' ? 'selected' : '' }}>

                                    🦵 Tratamiento de Várices
                                </option>

                                <option value="Termodiálisis"
                                    {{ $cita->servicio_especifico == 'Termodiálisis' ? 'selected' : '' }}>

                                    🔬 Termodiálisis
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- ===================== -->
                    <!-- PROGRAMACIÓN -->
                    <!-- ===================== -->

                    <div class="row mb-4">

                        <div class="col-12">
                            <h5 class="text-uppercase fw-bold mb-3"
                                style="color:#4a2c6d;border-bottom:2px solid #e9d5ff;padding-bottom:10px;">

                                <i class="fas fa-clock me-2"></i>
                                Programación
                            </h5>
                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">Fecha</label>

                            <input type="date" name="fecha" class="form-control"
                                value="{{ \Carbon\Carbon::parse($cita->fecha)->format('Y-m-d') }}" required>

                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">Hora</label>

                            <input type="time" name="hora" class="form-control" value="{{ $cita->hora }}" required>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label">Duración</label>

                            <select name="duracion_minutos" class="form-select">

                                <option value="30" {{ $cita->duracion_minutos == 30 ? 'selected' : '' }}>30</option>
                                <option value="45" {{ $cita->duracion_minutos == 45 ? 'selected' : '' }}>45</option>
                                <option value="60" {{ $cita->duracion_minutos == 60 ? 'selected' : '' }}>60</option>
                                <option value="90" {{ $cita->duracion_minutos == 90 ? 'selected' : '' }}>90</option>
                                <option value="120" {{ $cita->duracion_minutos == 120 ? 'selected' : '' }}>120</option>
                                <option value="180" {{ $cita->duracion_minutos == 180 ? 'selected' : '' }}>180</option>

                            </select>

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label">Prioridad</label>

                            <select name="prioridad" class="form-select">

                                <option value="Normal" {{ $cita->prioridad == 'Normal' ? 'selected' : '' }}>
                                    Normal
                                </option>

                                <option value="Preferente" {{ $cita->prioridad == 'Preferente' ? 'selected' : '' }}>
                                    Preferente
                                </option>

                                <option value="Urgente" {{ $cita->prioridad == 'Urgente' ? 'selected' : '' }}>
                                    Urgente
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- ===================== -->
                    <!-- INFORMACIÓN MÉDICA -->
                    <!-- ===================== -->

                    <div class="row mb-4">

                        <div class="col-12">

                            <h5 class="text-uppercase fw-bold mb-3"
                                style="color:#4a2c6d;border-bottom:2px solid #e9d5ff;padding-bottom:10px;">

                                <i class="fas fa-notes-medical me-2"></i>
                                Información Médica

                            </h5>

                        </div>


                        <div class="col-6 mb-3">

                            <label class="form-label">Motivo de Consulta</label>

                            <input type="text" name="motivo_consulta" class="form-control"
                                value="{{ $cita->motivo_consulta }}">

                        </div>


                        <div class="col-6 mb-3">

                            <label class="form-label">Síntomas</label>

                            <input type="text" name="sintomas" class="form-control" value="{{ $cita->sintomas }}">

                        </div>


                        <div class="col-12 mb-3">

                            <label class="form-label">Notas</label>

                            <textarea name="notas_previas" class="form-control" rows="3">{{ $cita->notas_previas }}</textarea>

                        </div>

                    </div>


                    <!-- BOTONES -->

                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary btn-lg px-4">

                            Cancelar

                        </a>

                        <button type="submit" class="btn btn-lg px-5"
                            style="background: linear-gradient(135deg,#a97bc9,#8e44ad);
color:white;border:none;">

                            <i class="fas fa-save me-2"></i>
                            Actualizar Procedimiento

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
