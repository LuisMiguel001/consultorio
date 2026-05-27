@extends('layouts.app')

@section('content')

    @php
        $especialidad = auth()->user()->especialidad->slug ?? null;

        use Carbon\Carbon;

        $now = Carbon::now()->format('Y-m-d\TH:i');

        $servicios = \App\Models\Servicio::where(
            'consultorio_id',
            auth()->user()->consultorio_id
        )
        ->where('activo', 1)
        ->orderBy('nombre')
        ->get();
    @endphp

    <div class="container my-4">

        <div class="card shadow-sm border-0" style="border-radius:15px;">

            <div
                class="card-header d-flex justify-content-between align-items-center"
                style="
                    background:#0d47a1;
                    color:white;
                    border-radius:15px 15px 0 0;
                "
            >

                <div>

                    <h5 class="mb-0">
                        Nuevo Historial Clínico
                    </h5>

                    <small>
                        Paciente:
                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}
                    </small>

                </div>

                <a
                    href="{{ route('pacientes.show', $paciente->id) }}"
                    class="btn btn-sm btn-light"
                    style="
                        color:#0d47a1;
                        border-radius:6px;
                    "
                >
                    Cancelar
                </a>

            </div>

            <div class="card-body p-4">

                <form
                    method="POST"
                    action="{{ route('consultas.store') }}"
                    id="formConsulta"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="cita_id"
                        value="{{ $cita_id }}"
                    >

                    <input
                        type="hidden"
                        name="paciente_id"
                        value="{{ $paciente->id }}"
                    >

                    <!-- FECHA -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Fecha y Hora</strong>
                        </label>

                        <input
                            type="datetime-local"
                            name="fecha_consulta"
                            class="form-control"
                            value="{{ $now }}"
                            readonly
                            required
                        >

                        <small class="text-muted">
                            La fecha y hora se registran automáticamente
                        </small>

                    </div>

                    <!-- TIPO CONSULTA -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Tipo de Consulta</strong>
                        </label>

                        <select
                            name="tipo_consulta"
                            class="form-select"
                            required
                        >

                            <option value="">
                                --Seleccione--
                            </option>

                            <option value="Consulta General">
                                Consulta General
                            </option>

                            <option value="Control">
                                Control
                            </option>

                            <option value="Postquirurgico">
                                Postquirúrgico
                            </option>

                            <option value="Emergencia">
                                Emergencia
                            </option>

                        </select>

                    </div>

                    <!-- MOTIVO -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Motivo de Consulta</strong>
                        </label>

                        <textarea
                            name="motivo_consulta"
                            class="form-control"
                            rows="3"
                            placeholder="Describa el motivo de la consulta..."
                        ></textarea>

                    </div>

                    <!-- GINECOLOGÍA -->

                    @if ($especialidad === 'ginecologia')

                        <div
                            class="card mb-3"
                            style="
                                background-color:#f8f9fa;
                                border-radius:12px;
                            "
                        >

                            <div
                                class="card-header"
                                style="
                                    background-color:#e3f2fd;
                                    color:#0d47a1;
                                    border-radius:12px 12px 0 0;
                                "
                            >

                                <strong>
                                    Datos Ginecológicos
                                </strong>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            FUM
                                        </label>

                                        <input
                                            type="date"
                                            name="fum"
                                            class="form-control"
                                        >

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Ciclo Menstrual
                                        </label>

                                        <input
                                            type="text"
                                            name="ciclo"
                                            class="form-control"
                                            placeholder="Ej: 28 días"
                                        >

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Gestas
                                        </label>

                                        <input
                                            type="number"
                                            name="gestas"
                                            class="form-control"
                                            min="0"
                                        >

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Partos
                                        </label>

                                        <input
                                            type="number"
                                            name="partos"
                                            class="form-control"
                                            min="0"
                                        >

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Abortos
                                        </label>

                                        <input
                                            type="number"
                                            name="abortos"
                                            class="form-control"
                                            min="0"
                                        >

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Cesáreas
                                        </label>

                                        <input
                                            type="number"
                                            name="cesareas"
                                            class="form-control"
                                            min="0"
                                        >

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Embarazo Actual
                                        </label>

                                        <select
                                            name="embarazo"
                                            class="form-select"
                                        >

                                            <option value="0">
                                                No
                                            </option>

                                            <option value="1">
                                                Sí
                                            </option>

                                        </select>

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Semanas Gestación
                                        </label>

                                        <input
                                            type="number"
                                            name="semanas"
                                            class="form-control"
                                            min="0"
                                            max="42"
                                        >

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Método Anticonceptivo
                                        </label>

                                        <select
                                            name="metodo"
                                            class="form-select"
                                        >

                                            <option value="">
                                                Ninguno
                                            </option>

                                            <option value="Orales">
                                                Orales
                                            </option>

                                            <option value="Inyectable">
                                                Inyectable
                                            </option>

                                            <option value="Implante">
                                                Implante
                                            </option>

                                            <option value="DIU">
                                                DIU
                                            </option>

                                            <option value="Parche">
                                                Parche
                                            </option>

                                            <option value="Preservativo">
                                                Preservativo
                                            </option>

                                        </select>

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Vida Sexual Activa
                                        </label>

                                        <select
                                            name="vida_sexual"
                                            class="form-select"
                                        >

                                            <option value="0">
                                                No
                                            </option>

                                            <option value="1">
                                                Sí
                                            </option>

                                        </select>

                                    </div>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Examen Pélvico
                                    </label>

                                    <textarea
                                        name="examen_pelvico"
                                        class="form-control"
                                        rows="2"
                                    ></textarea>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Examen de Mamas
                                    </label>

                                    <textarea
                                        name="mamas"
                                        class="form-control"
                                        rows="2"
                                    ></textarea>

                                </div>

                            </div>

                        </div>

                    @endif

                    <!-- ENFERMEDAD -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Enfermedad Actual</strong>
                        </label>

                        <textarea
                            name="enfermedad_actual"
                            class="form-control"
                            rows="3"
                        ></textarea>

                    </div>

                    <!-- PLAN -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Plan</strong>
                        </label>

                        <textarea
                            name="plan"
                            class="form-control"
                            rows="3"
                        ></textarea>

                    </div>

                    <!-- OBSERVACIONES -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Observaciones</strong>
                        </label>

                        <textarea
                            name="observaciones"
                            class="form-control"
                            rows="2"
                        ></textarea>

                    </div>

                    <!-- BOTONES -->

                    <div class="text-center">

                        <button
                            type="button"
                            class="btn"
                            data-bs-toggle="modal"
                            data-bs-target="#modalServicios"
                            style="
                                background:#0d47a1;
                                color:white;
                                border:none;
                                border-radius:8px;
                                padding:8px 30px;
                            "
                        >
                            Guardar Consulta
                        </button>

                        <a
                            href="{{ route('pacientes.show', $paciente->id) }}"
                            class="btn btn-secondary"
                            style="border-radius:8px;"
                        >
                            Cancelar
                        </a>

                    </div>

                    <!-- MODAL -->

                    <div
                        class="modal fade"
                        id="modalServicios"
                        tabindex="-1"
                    >

                        <div
                            class="modal-dialog modal-lg modal-dialog-centered"
                        >

                            <div class="modal-content border-0 shadow">

                                <div class="modal-header bg-primary text-white">

                                    <h5 class="modal-title">
                                        Servicios y Procesos Realizados
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"
                                    ></button>

                                </div>

                                <div class="modal-body">

                                    @if($servicios->count())

                                        <div class="row">

                                            @foreach ($servicios as $servicio)

                                                <div class="col-md-6 mb-3">

                                                    <div
                                                        class="card border-0 shadow-sm h-100"
                                                    >

                                                        <div class="card-body">

                                                            <div class="form-check">

                                                                <input
                                                                    class="form-check-input servicio-check"
                                                                    type="checkbox"
                                                                    name="servicios[]"
                                                                    value="{{ $servicio->id }}"
                                                                    data-precio="{{ $servicio->precio }}"
                                                                    id="servicio{{ $servicio->id }}"
                                                                >

                                                                <label
                                                                    class="form-check-label w-100"
                                                                    for="servicio{{ $servicio->id }}"
                                                                >

                                                                    <strong>
                                                                        {{ $servicio->nombre }}
                                                                    </strong>

                                                                    <br>

                                                                    <span class="text-success">
                                                                        RD$
                                                                        {{ number_format($servicio->precio, 2) }}
                                                                    </span>

                                                                </label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                    @else

                                        <div class="alert alert-warning mb-0">

                                            No hay servicios registrados.

                                        </div>

                                    @endif

                                </div>

                                <div
                                    class="modal-footer d-flex justify-content-between"
                                >

                                    <div>

                                        <strong>Total:</strong>

                                        <span
                                            class="text-success fs-5"
                                            id="totalServicios"
                                        >
                                            RD$ 0.00
                                        </span>

                                    </div>

                                    <div>

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >
                                            Cancelar
                                        </button>

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            Guardar Consulta
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

<style>

    .form-label{
        font-weight:600;
        color:#1a2b3c;
        margin-bottom:0.5rem;
    }

    .form-control,
    .form-select{
        border-radius:8px;
        border:1px solid #dee2e6;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:#0d47a1;
        box-shadow:0 0 0 0.2rem rgba(13,71,161,.25);
    }

    .btn:hover{
        opacity:.9;
        transform:translateY(-1px);
        transition:all .2s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const checks =
            document.querySelectorAll('.servicio-check');

        const totalLabel =
            document.getElementById('totalServicios');

        function calcularTotal() {

            let total = 0;

            checks.forEach(check => {

                if (check.checked) {

                    total += parseFloat(
                        check.dataset.precio
                    );

                }

            });

            totalLabel.innerText =
                'RD$ ' +
                total.toLocaleString(
                    'en-US',
                    {
                        minimumFractionDigits: 2
                    }
                );
        }

        checks.forEach(check => {
            check.addEventListener(
                'change',
                calcularTotal
            );
        });
    });
</script>
