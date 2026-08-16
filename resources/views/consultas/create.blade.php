@extends('layouts.app')

@section('content')

    @php
        $especialidad = auth()->user()->especialidad->slug ?? null;

        use Carbon\Carbon;

        $now = Carbon::now()->format('Y-m-d\TH:i');

        $servicios = \App\Models\Servicio::where('consultorio_id', auth()->user()->consultorio_id)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $zonasAgrupadas = \App\Models\ZonaCorporal::agrupadas();
    @endphp

    <div class="container my-4">

        <div class="card shadow-sm border-0" style="border-radius:15px;">

            <div class="card-header d-flex justify-content-between align-items-center"
                style="
                    background:#0d47a1;
                    color:white;
                    border-radius:15px 15px 0 0;
                ">

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

                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-light"
                    style="color:#0d47a1;border-radius:6px;">
                    Cancelar
                </a>

            </div>

            <div class="card-body p-4">

                <form method="POST" action="{{ route('consultas.store') }}" id="formConsulta">

                    @csrf

                    <input type="hidden" name="cita_id" value="{{ $cita_id }}">

                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                    <!-- FECHA -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Fecha y Hora</strong>
                        </label>

                        <input type="datetime-local" name="fecha_consulta" class="form-control" value="{{ $now }}"
                            readonly required>

                        <small class="text-muted">
                            La fecha y hora se registran automáticamente
                        </small>

                    </div>

                    <!-- TIPO CONSULTA -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Tipo de Consulta</strong>
                        </label>

                        <select name="tipo_consulta" class="form-select" required>

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

                        <textarea name="motivo_consulta" class="form-control" rows="3" placeholder="Describa el motivo de la consulta..."></textarea>

                    </div>

                    <!-- GINECOLOGÍA -->

                    @if ($especialidad === 'ginecologia')
                        <div class="card mb-3"
                            style="
                                background-color:#f8f9fa;
                                border-radius:12px;
                            ">

                            <div class="card-header"
                                style="
                                    background-color:#e3f2fd;
                                    color:#0d47a1;
                                    border-radius:12px 12px 0 0;
                                ">

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

                                        <input type="date" name="fum" class="form-control">

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Ciclo Menstrual
                                        </label>

                                        <input type="text" name="ciclo" class="form-control" placeholder="Ej: 28 días">

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Gestas
                                        </label>

                                        <input type="number" name="gestas" class="form-control" min="0">

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Partos
                                        </label>

                                        <input type="number" name="partos" class="form-control" min="0">

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Abortos
                                        </label>

                                        <input type="number" name="abortos" class="form-control" min="0">

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Cesáreas
                                        </label>

                                        <input type="number" name="cesareas" class="form-control" min="0">

                                    </div>

                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Embarazo Actual
                                        </label>

                                        <select name="embarazo" class="form-select">

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

                                        <input type="number" name="semanas" class="form-control" min="0"
                                            max="42">

                                    </div>

                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Método Anticonceptivo
                                        </label>

                                        <select name="metodo" class="form-select">

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

                                        <select name="vida_sexual" class="form-select">

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

                                    <textarea name="examen_pelvico" class="form-control" rows="2"></textarea>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Examen de Mamas
                                    </label>

                                    <textarea name="mamas" class="form-control" rows="2"></textarea>

                                </div>

                            </div>

                        </div>
                    @endif

                    {{-- DERMATOLOGÍA --}}
@if ($especialidad === 'dermatologia')
    <div class="card mb-3" style="background-color:#f8f9fa; border-radius:12px;">

        <div class="card-header" style="background-color:#fce4ec; color:#ad1457; border-radius:12px 12px 0 0;">
            <strong>Datos Dermatológicos</strong>
        </div>

        <div class="card-body">

            <!-- CLÍNICO -->
            <h6 class="fw-bold text-muted mb-3">Evaluación Clínica</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fototipo (Fitzpatrick)</label>
                    <select name="fototipo_fitzpatrick" class="form-select">
                        <option value="">--Seleccione--</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                        <option value="VI">VI</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Piel</label>
                    <select name="tipo_piel" class="form-select">
                        <option value="">--Seleccione--</option>
                        <option value="Seca">Seca</option>
                        <option value="Grasa">Grasa</option>
                        <option value="Mixta">Mixta</option>
                        <option value="Sensible">Sensible</option>
                        <option value="Normal">Normal</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Exposición Solar</label>
                    <select name="exposicion_solar" class="form-select">
                        <option value="">--Seleccione--</option>
                        <option value="Baja">Baja</option>
                        <option value="Moderada">Moderada</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Motivo Dermatológico</label>
                    <input type="text" name="motivo_dermatologico" class="form-control"
                        placeholder="Ej: lesión pruriginosa en brazo derecho">
                </div>
            </div>

            <hr>
            <small class="text-muted d-block mb-2">Lesión (si aplica)</small>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Lesión</label>
                    <select name="lesion_tipo" class="form-select">
                        <option value="">--Ninguna--</option>
                        <option value="Mácula">Mácula</option>
                        <option value="Pápula">Pápula</option>
                        <option value="Nódulo">Nódulo</option>
                        <option value="Placa">Placa</option>
                        <option value="Vesícula">Vesícula</option>
                        <option value="Ampolla">Ampolla</option>
                        <option value="Pústula">Pústula</option>
                        <option value="Quiste">Quiste</option>
                        <option value="Tumor">Tumor</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Zona Corporal</label>
                    <select name="zona_corporal_id" class="form-select">
                        <option value="">--Seleccione--</option>
                        @foreach ($zonasAgrupadas as $grupo => $zonas)
                            <optgroup label="{{ $grupo }}">
                                @foreach ($zonas as $zona)
                                    <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <small class="text-muted">Necesario para comparar esta lesión entre consultas futuras</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Detalle de ubicación (opcional)</label>
                    <input type="text" name="lesion_localizacion" class="form-control"
                        placeholder="Ej: cara lateral, cerca del codo">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tamaño</label>
                    <input type="text" name="lesion_tamano" class="form-control" placeholder="Ej: 1.5 cm">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Color</label>
                    <input type="text" name="lesion_color" class="form-control">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Bordes</label>
                    <select name="lesion_bordes" class="form-select">
                        <option value="">--Seleccione--</option>
                        <option value="Definidos">Definidos</option>
                        <option value="Difusos">Difusos</option>
                        <option value="Irregulares">Irregulares</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Superficie</label>
                    <select name="lesion_superficie" class="form-select">
                        <option value="">--Seleccione--</option>
                        <option value="Lisa">Lisa</option>
                        <option value="Escamosa">Escamosa</option>
                        <option value="Ulcerada">Ulcerada</option>
                        <option value="Verrugosa">Verrugosa</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tiempo de Evolución</label>
                    <input type="text" name="tiempo_evolucion" class="form-control" placeholder="Ej: 3 semanas">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Síntomas Asociados</label>

                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="prurito" value="1" id="prurito">
                    <label class="form-check-label" for="prurito">Prurito</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="dolor" value="1" id="dolor">
                    <label class="form-check-label" for="dolor">Dolor</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="ardor" value="1" id="ardor">
                    <label class="form-check-label" for="ardor">Ardor</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input" name="sangrado" value="1" id="sangrado">
                    <label class="form-check-label" for="sangrado">Sangrado</label>
                </div>

                <input type="text" name="sintomas_asociados_otros" class="form-control mt-2"
                    placeholder="Otros síntomas...">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" name="dermatoscopia_realizada"
                            value="1" id="dermatoscopia_realizada">
                        <label class="form-check-label" for="dermatoscopia_realizada">
                            <strong>Dermatoscopia realizada</strong>
                        </label>
                    </div>
                    <textarea name="hallazgos_dermatoscopia" class="form-control" rows="2"
                        placeholder="Hallazgos de dermatoscopia..."></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" name="biopsia_realizada"
                            value="1" id="biopsia_realizada">
                        <label class="form-check-label" for="biopsia_realizada">
                            <strong>Biopsia realizada</strong>
                        </label>
                    </div>
                    <textarea name="resultado_biopsia" class="form-control" rows="2"
                        placeholder="Resultado de biopsia..."></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Antecedentes Dermatológicos</label>
                    <textarea name="antecedentes_dermatologicos" class="form-control" rows="2"
                        placeholder="Acné, psoriasis, dermatitis, cáncer de piel previo..."></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alergias Cutáneas</label>
                    <textarea name="alergias_cutaneas" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Diagnóstico Dermatológico</label>
                <input type="text" name="diagnostico_dermatologico" class="form-control">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tratamiento Tópico</label>
                    <textarea name="tratamiento_topico" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tratamiento Sistémico</label>
                    <textarea name="tratamiento_sistemico" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <!-- ESTÉTICO -->
            <hr>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="es_procedimiento_estetico"
                    value="1" id="es_procedimiento_estetico">
                <label class="form-check-label" for="es_procedimiento_estetico">
                    <strong>Esta consulta incluye un procedimiento estético</strong>
                </label>
            </div>

            <div id="bloqueEstetico" class="d-none">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Procedimiento</label>
                        <select name="procedimiento_estetico" class="form-select">
                            <option value="">--Seleccione--</option>
                            <option value="Botox">Botox / Toxina botulínica</option>
                            <option value="Rellenos">Rellenos (ácido hialurónico)</option>
                            <option value="Peeling">Peeling químico</option>
                            <option value="Laser">Láser</option>
                            <option value="Mesoterapia">Mesoterapia</option>
                            <option value="Hilos">Hilos tensores</option>
                            <option value="Microneedling">Microneedling</option>
                            <option value="Radiofrecuencia">Radiofrecuencia</option>
                            <option value="Hidratacion">Hidratación facial</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Zona Tratada</label>
                        <input type="text" name="zona_tratada" class="form-control"
                            placeholder="Ej: frente, entrecejo, pómulos, labios...">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Producto Utilizado</label>
                        <input type="text" name="producto_utilizado" class="form-control"
                            placeholder="Marca / tipo">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cantidad Aplicada</label>
                        <input type="text" name="cantidad_aplicada" class="form-control"
                            placeholder="Ej: 20 unidades / 1.5 ml">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Técnica de Aplicación</label>
                        <input type="text" name="tecnica_aplicacion" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Efectos Secundarios Esperados</label>
                        <textarea name="efectos_secundarios_esperados" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Recomendaciones Post-Procedimiento</label>
                        <textarea name="recomendaciones_post_procedimiento" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha Próxima Sesión</label>
                        <input type="date" name="fecha_proxima_sesion" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="consentimiento_informado"
                                value="1" id="consentimiento_informado">
                            <label class="form-check-label" for="consentimiento_informado">
                                Consentimiento informado firmado
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

                    <!-- ENFERMEDAD -->
                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Enfermedad Actual</strong>
                        </label>

                        <textarea name="enfermedad_actual" class="form-control" rows="3"></textarea>

                    </div>

                    <!-- PLAN -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Plan</strong>
                        </label>

                        <textarea name="plan" class="form-control" rows="3"></textarea>

                    </div>

                    <!-- OBSERVACIONES -->

                    <div class="mb-3">

                        <label class="form-label">
                            <strong>Observaciones</strong>
                        </label>

                        <textarea name="observaciones" class="form-control" rows="2"></textarea>

                    </div>

                    <!-- BOTONES -->

                    <div class="text-center">

                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modalServicios"
                            style="
                                background:#0d47a1;
                                color:white;
                                border:none;
                                border-radius:8px;
                                padding:8px 30px;
                            ">
                            Guardar Consulta
                        </button>

                        <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-secondary"
                            style="border-radius:8px;">
                            Cancelar
                        </a>

                    </div>

                    <!-- MODAL -->

                    <div class="modal fade" id="modalServicios" tabindex="-1">

                        <div class="modal-dialog modal-lg modal-dialog-centered">

                            <div class="modal-content border-0 shadow">

                                <div class="modal-header bg-primary text-white">

                                    <h5 class="modal-title">
                                        Servicios y Procesos Realizados
                                    </h5>

                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>

                                </div>

                                <div class="modal-body">

                                    @if ($servicios->count())
                                        <div class="row">

                                            @foreach ($servicios as $servicio)
                                                <div class="col-md-6 mb-3">

                                                    <div class="card border-0 shadow-sm h-100">

                                                        <div class="card-body">

                                                            <div class="form-check">

                                                                <input class="form-check-input servicio-check"
                                                                    type="checkbox" name="servicios[]"
                                                                    value="{{ $servicio->id }}"
                                                                    data-precio="{{ $servicio->precio }}"
                                                                    id="servicio{{ $servicio->id }}">

                                                                <label class="form-check-label w-100"
                                                                    for="servicio{{ $servicio->id }}">

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

                                <div class="modal-footer d-flex justify-content-between">

                                    <div>

                                        <strong>Total:</strong>

                                        <span class="text-success fs-5" id="totalServicios">
                                            RD$ 0.00
                                        </span>

                                    </div>

                                    <div>

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>

                                        <button type="submit" class="btn btn-primary">
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
    .form-label {
        font-weight: 600;
        color: #1a2b3c;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, .25);
    }

    .btn:hover {
        opacity: .9;
        transform: translateY(-1px);
        transition: all .2s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

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
                    'en-US', {
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

        // Mostrar/ocultar bloque de procedimiento estético
        const checkEstetico = document.getElementById('es_procedimiento_estetico');
        const bloqueEstetico = document.getElementById('bloqueEstetico');
        if (checkEstetico && bloqueEstetico) {
            checkEstetico.addEventListener('change', function() {
                bloqueEstetico.classList.toggle('d-none', !this.checked);
            });
        }
    });
</script>
