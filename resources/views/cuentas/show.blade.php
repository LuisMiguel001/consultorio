@extends('layouts.app')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Factura #{{ $cuenta->id }}</h5>
                <span>{{ $cuenta->created_at->format('d/m/Y h:i A') }}</span>
            </div>
        </div>

        <div class="card-body">

            <h6>
                Paciente:
                {{ $cuenta->paciente->nombre }}
                {{ $cuenta->paciente->apellido }}
            </h6>

            <hr>

            {{-- ============================================================
                 TABLA DE SERVICIOS
                 ============================================================ --}}
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                        @if($cuenta->estado !== 'pagado')
                            <th class="text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuenta->detalles as $detalle)
                        <tr id="fila-detalle-{{ $detalle->id }}">

                            <td>{{ $detalle->servicio->nombre }}</td>

                            <td>{{ $detalle->cantidad ?? 1 }}</td>

                            <td>RD$ {{ number_format($detalle->precio, 2) }}</td>

                            <td>RD$ {{ number_format($detalle->subtotal ?? $detalle->precio, 2) }}</td>

                            @if($cuenta->estado !== 'pagado')
                                <td class="text-center">

                                    {{-- Botón Editar --}}
                                    <button
                                        class="btn btn-sm btn-outline-primary btn-accion-detalle"
                                        data-accion="editar"
                                        data-detalle-id="{{ $detalle->id }}"
                                        data-precio="{{ $detalle->precio }}"
                                        data-cantidad="{{ $detalle->cantidad ?? 1 }}"
                                        data-nombre="{{ $detalle->servicio->nombre }}"
                                    >
                                        <i class="bi bi-pencil"></i> Editar
                                    </button>

                                    {{-- Botón Eliminar --}}
                                    <button
                                        class="btn btn-sm btn-outline-danger btn-accion-detalle"
                                        data-accion="eliminar"
                                        data-detalle-id="{{ $detalle->id }}"
                                        data-nombre="{{ $detalle->servicio->nombre }}"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </td>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <h4 class="text-success" id="total-display">
                    Total: RD$ {{ number_format($cuenta->total, 2) }}
                </h4>
            </div>

            {{-- Formulario de cobro --}}
            @if($cuenta->estado != 'pagado')
                <hr>
                <form method="POST" action="{{ route('cuentas.cobrar') }}">
                    @csrf
                    <input type="hidden" name="cuenta_id" value="{{ $cuenta->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Método Pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success">Cobrar</button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>

{{-- ============================================================
     MODAL 1 — VERIFICAR CREDENCIALES DEL DOCTOR
     ============================================================ --}}
<div class="modal fade" id="modalVerificarDoctor" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock"></i>
                    Verificación del Doctor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Para modificar esta factura ingrese las credenciales
                    del doctor responsable de la consulta.
                </p>

                <div id="alerta-verificacion" class="alert alert-danger d-none"></div>

                <div class="mb-3">
                    <label class="form-label">Correo del doctor</label>
                    <input
                        type="email"
                        id="doctor-email"
                        class="form-control"
                        placeholder="correo@ejemplo.com"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input
                        type="password"
                        id="doctor-password"
                        class="form-control"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    class="btn btn-warning"
                    id="btn-confirmar-verificacion"
                >
                    <span id="spinner-verificacion" class="spinner-border spinner-border-sm d-none me-1"></span>
                    Verificar y continuar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ============================================================
     MODAL 2 — EDITAR DETALLE
     ============================================================ --}}
<div class="modal fade" id="modalEditarDetalle" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-editar-titulo">Editar servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="form-editar-detalle">
                @csrf
                @method('PUT')

                {{-- Token de autorización del doctor (se rellena en JS) --}}
                <input type="hidden" name="edit_token" id="input-edit-token">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Precio unitario (RD$)</label>
                        <input
                            type="number"
                            name="precio"
                            id="input-precio"
                            class="form-control"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad</label>
                        <input
                            type="number"
                            name="cantidad"
                            id="input-cantidad"
                            class="form-control"
                            min="1"
                            required
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL 3 — CONFIRMAR ELIMINACIÓN
     ============================================================ --}}
<div class="modal fade" id="modalEliminarDetalle" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar servicio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>
                    ¿Está seguro de eliminar
                    <strong id="nombre-eliminar"></strong>
                    de la factura?
                </p>
            </div>

            <form method="POST" id="form-eliminar-detalle">
                @csrf
                @method('DELETE')
                <input type="hidden" name="edit_token" id="input-edit-token-eliminar">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // ─── Debug inicial ─────────────────────────────────────────────────────
    console.log('Script cargado');
    console.log('Botones encontrados:', document.querySelectorAll('.btn-accion-detalle').length);
    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');
    console.log('Modal verificar existe:', !!document.getElementById('modalVerificarDoctor'));

    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap NO está cargado. Revisar layouts/app.blade.php');
        return;
    }

    // ─── Estado local ──────────────────────────────────────────────────────
    let tokenDoctor     = null;
    let accionPendiente = null;
    const CUENTA_ID     = {{ $cuenta->id }};

    // ─── Referencias DOM ───────────────────────────────────────────────────
    const modalVerificar = new bootstrap.Modal(document.getElementById('modalVerificarDoctor'));
    const modalEditar    = new bootstrap.Modal(document.getElementById('modalEditarDetalle'));
    const modalEliminar  = new bootstrap.Modal(document.getElementById('modalEliminarDetalle'));

    // ─── 1. Capturar clics ─────────────────────────────────────────────────
    document.querySelectorAll('.btn-accion-detalle').forEach(function (btn) {
        console.log('Registrando botón:', btn.dataset.accion, btn.dataset.detalleId);
        btn.addEventListener('click', function () {
            console.log('Clic en botón:', this.dataset.accion, this.dataset.detalleId);

            accionPendiente = {
                accion:    this.dataset.accion,
                detalleId: this.dataset.detalleId,
                nombre:    this.dataset.nombre,
                precio:    this.dataset.precio,
                cantidad:  this.dataset.cantidad,
            };

            if (tokenDoctor) {
                abrirModalAccion();
            } else {
                abrirModalVerificacion();
            }
        });
    });

    // ─── 2. Verificación ───────────────────────────────────────────────────
    function abrirModalVerificacion() {
        console.log('Abriendo modal verificación');
        document.getElementById('doctor-email').value    = '';
        document.getElementById('doctor-password').value = '';
        ocultarAlerta();
        modalVerificar.show();
    }

    document.getElementById('btn-confirmar-verificacion')
        .addEventListener('click', verificarDoctor);

    ['doctor-email', 'doctor-password'].forEach(function (id) {
        document.getElementById(id).addEventListener('keydown', function (e) {
            if (e.key === 'Enter') verificarDoctor();
        });
    });

    function verificarDoctor() {
        const email    = document.getElementById('doctor-email').value.trim();
        const password = document.getElementById('doctor-password').value;

        if (!email || !password) {
            mostrarAlerta('Complete ambos campos.');
            return;
        }

        const spinner = document.getElementById('spinner-verificacion');
        const btn     = document.getElementById('btn-confirmar-verificacion');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        fetch('{{ route("cuentas.verificar-doctor") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ email, password, cuenta_id: CUENTA_ID }),
        })
        .then(function (res) {
            return res.json().then(function (d) { return { status: res.status, d }; });
        })
        .then(function ({ status, d }) {
            console.log('Respuesta verificación:', status, d);
            if (d.ok) {
                tokenDoctor = d.token;
                modalVerificar.hide();
                setTimeout(abrirModalAccion, 300);
            } else {
                mostrarAlerta(d.mensaje || 'Credenciales incorrectas.');
            }
        })
        .catch(function (err) {
            console.error('Error fetch:', err);
            mostrarAlerta('Error de conexión.');
        })
        .finally(function () {
            spinner.classList.add('d-none');
            btn.disabled = false;
        });
    }

    function mostrarAlerta(msg) {
        const el = document.getElementById('alerta-verificacion');
        el.textContent = msg;
        el.classList.remove('d-none');
    }

    function ocultarAlerta() {
        document.getElementById('alerta-verificacion').classList.add('d-none');
    }

    // ─── 3. Acción post-verificación ───────────────────────────────────────
    function abrirModalAccion() {
        console.log('Abriendo modal acción:', accionPendiente?.accion);
        if (!accionPendiente) return;

        if (accionPendiente.accion === 'editar') {
            abrirModalEditar();
        } else {
            abrirModalEliminar();
        }
    }

    function abrirModalEditar() {
        document.getElementById('modal-editar-titulo').textContent =
            'Editar: ' + accionPendiente.nombre;
        document.getElementById('input-precio').value             = accionPendiente.precio;
        document.getElementById('input-cantidad').value           = accionPendiente.cantidad;
        document.getElementById('input-edit-token').value         = tokenDoctor;
        document.getElementById('form-editar-detalle').action     =
            '/cuentas/detalle/' + accionPendiente.detalleId;
        modalEditar.show();
    }

    function abrirModalEliminar() {
        document.getElementById('nombre-eliminar').textContent         = accionPendiente.nombre;
        document.getElementById('input-edit-token-eliminar').value     = tokenDoctor;
        document.getElementById('form-eliminar-detalle').action        =
            '/cuentas/detalle/' + accionPendiente.detalleId;
        modalEliminar.show();
    }

    // ─── 4. Limpiar estado al cerrar modales ───────────────────────────────
    document.getElementById('modalEditarDetalle')
        .addEventListener('hidden.bs.modal', function () {
            tokenDoctor = null; accionPendiente = null;
        });
    document.getElementById('modalEliminarDetalle')
        .addEventListener('hidden.bs.modal', function () {
            tokenDoctor = null; accionPendiente = null;
        });
});
</script>
