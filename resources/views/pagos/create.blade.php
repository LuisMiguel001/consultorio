@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Pago / Suscripción</h4>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('pagos.store') }}" method="POST" enctype="multipart/form-data" id="formPago">
                    @csrf

                    {{-- Pestañas para elegir entre suscripción existente o nueva --}}
                    <ul class="nav nav-tabs mb-3" id="pagoTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="existente-tab" data-bs-toggle="tab"
                                data-bs-target="#existente" type="button" role="tab">
                                📋 Renovar Suscripción
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="nueva-tab" data-bs-toggle="tab" data-bs-target="#nueva"
                                type="button" role="tab">
                                🆕 Nueva Suscripción
                            </button>
                        </li>
                    </ul>

                    <input type="hidden" name="tipo_registro" id="tipo_registro" value="existente">

                    <div class="tab-content" id="pagoTabsContent">
                        {{-- TAB 1: Suscripción existente (Renovación) --}}
                        <div class="tab-pane fade show active" id="existente" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Selecciona una suscripción existente para registrar un
                                pago de renovación.
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Suscripción *</label>
                                <select name="suscripcion_id" class="form-select" id="suscripcion_existente">
                                    <option value="">Seleccionar suscripción</option>
                                    @foreach ($suscripciones as $suscripcion)
                                        <option value="{{ $suscripcion->id }}">
                                            {{ $suscripcion->consultorio->nombre }} -
                                            {{ $suscripcion->plan->nombre }}
                                            ({{ ucfirst($suscripcion->estado) }})
                                            @if ($suscripcion->fecha_fin > now())
                                                - Vence:
                                                {{ \Carbon\Carbon::parse($suscripcion->fecha_fin)->format('d/m/Y') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- TAB 2: Nueva suscripción --}}
                        <div class="tab-pane fade" id="nueva" role="tabpanel">
                            <div class="alert alert-success">
                                <i class="fas fa-star"></i> ¿Nuevo cliente? Crea una nueva suscripción con su primer pago.
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Consultorio *</label>
                                    <select name="nuevo_consultorio_id" class="form-select" id="nuevo_consultorio">
                                        <option value="">Seleccionar consultorio</option>
                                        @foreach ($consultorios as $consultorio)
                                            <option value="{{ $consultorio->id }}"
                                                {{ $consultorioId == $consultorio->id ? 'selected' : '' }}>
                                                {{ $consultorio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Plan *</label>
                                    <select name="nuevo_plan_id" class="form-select" id="nuevo_plan">
                                        <option value="">Seleccionar plan</option>
                                        @foreach ($planes as $plan)
                                            <option value="{{ $plan->id }}"
                                                data-precio-mensual="{{ $plan->precio_mensual }}"
                                                data-precio-anual="{{ $plan->precio_anual }}">
                                                {{ $plan->nombre }} -
                                                Mensual: ${{ number_format($plan->precio_mensual, 2) }} /
                                                Anual: ${{ number_format($plan->precio_anual, 2) }}
                                                @if ($plan->nombre == 'Estándar')
                                                    ⭐ (Recomendado)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Período *</label>
                                    <select name="periodo" class="form-select" id="periodo">
                                        <option value="mensual">📅 Mensual</option>
                                        <option value="anual">🎯 Anual (2 meses gratis)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Método de pago *</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="transferencia">🏦 Transferencia bancaria</option>
                                <option value="tarjeta">💳 Tarjeta de crédito/débito</option>
                                <option value="efectivo">💰 Efectivo</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto *</label>
                            <input type="number" step="0.01" name="monto" class="form-control" id="monto"
                                placeholder="0.00" required>
                            <small class="text-muted">El monto se auto-completará según el plan seleccionado</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Referencia de transferencia</label>
                        <input type="text" name="referencia" class="form-control"
                            placeholder="Número de comprobante o referencia">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comprobante de pago</label>
                        <input type="file" name="comprobante" class="form-control" accept="image/*,application/pdf">
                        <small class="text-muted">Sube imagen o PDF del comprobante (máx 5MB)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notas adicionales</label>
                        <textarea name="notas" class="form-control" rows="3" placeholder="Información adicional sobre el pago..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" id="btnRegistrar">
                        <i class="fas fa-save"></i> Registrar Pago
                    </button>
                    <a href="{{ route('pagos.index') }}" class="btn btn-secondary btn-lg">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Control de pestañas y required fields
        const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
        const tipoRegistro = document.getElementById('tipo_registro');
        const suscripcionSelect = document.querySelector('[name="suscripcion_id"]');
        const nuevoConsultorio = document.getElementById('nuevo_consultorio');
        const nuevoPlan = document.getElementById('nuevo_plan');
        const periodoSelect = document.getElementById('periodo');

        // Función para actualizar required fields
        function actualizarRequired() {
            const tabActiva = document.querySelector('.tab-pane.active').id;

            if (tabActiva === 'existente') {
                tipoRegistro.value = 'existente';
                // Agregar required a suscripción existente
                suscripcionSelect.setAttribute('required', 'required');
                // Remover required de los campos de nueva suscripción
                nuevoConsultorio.removeAttribute('required');
                nuevoPlan.removeAttribute('required');
                periodoSelect.removeAttribute('required');
                // También deshabilitar los campos de nueva suscripción para que no se envíen
                nuevoConsultorio.disabled = true;
                nuevoPlan.disabled = true;
                periodoSelect.disabled = true;
                // Habilitar el campo de suscripción existente
                suscripcionSelect.disabled = false;
            } else {
                tipoRegistro.value = 'nueva';
                // Remover required de suscripción existente
                suscripcionSelect.removeAttribute('required');
                // Agregar required a los campos de nueva suscripción
                nuevoConsultorio.setAttribute('required', 'required');
                nuevoPlan.setAttribute('required', 'required');
                periodoSelect.setAttribute('required', 'required');
                // Deshabilitar el campo de suscripción existente
                suscripcionSelect.disabled = true;
                // Habilitar los campos de nueva suscripción
                nuevoConsultorio.disabled = false;
                nuevoPlan.disabled = false;
                periodoSelect.disabled = false;
            }
        }

        // Escuchar cambios de pestaña
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                actualizarRequired();
                actualizarMonto();
            });
        });

        // Función para actualizar el monto automáticamente
        function actualizarMonto() {
            const tabActiva = document.querySelector('.tab-pane.active').id;
            const montoInput = document.getElementById('monto');

            if (tabActiva === 'nueva') {
                const planSelect = document.getElementById('nuevo_plan');
                const periodo = document.getElementById('periodo').value;

                if (planSelect && planSelect.value) {
                    const selectedOption = planSelect.options[planSelect.selectedIndex];
                    let precio = 0;

                    if (periodo === 'mensual') {
                        precio = selectedOption.getAttribute('data-precio-mensual');
                    } else {
                        precio = selectedOption.getAttribute('data-precio-anual');
                    }

                    if (precio && precio > 0) {
                        montoInput.value = precio;
                        console.log('Monto actualizado a:', precio);
                    } else {
                        montoInput.value = '';
                    }
                } else {
                    montoInput.value = '';
                }
            } else {
                montoInput.value = '';
            }
        }

        // Escuchar cambios en el plan y período
        if (nuevoPlan) {
            nuevoPlan.addEventListener('change', actualizarMonto);
        }
        if (periodoSelect) {
            periodoSelect.addEventListener('change', actualizarMonto);
        }

        // Inicializar
        actualizarRequired();
        actualizarMonto();
    });
</script>
