@extends('layouts.app')

@section('content')

    <div class="container py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Cuentas Pendientes
                </h3>

                <p class="text-muted mb-0">
                    Pacientes pendientes de cobro
                </p>
            </div>

            <a href="{{ route('caja.panel') }}"
                class="btn btn-outline-primary">

                <i class="fa-solid fa-cash-register me-1"></i>

                Volver a Caja
            </a>

        </div>

        {{-- ALERTAS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABLA --}}
        <div class="card border-0 shadow-sm"
            style="border-radius:16px;">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead
                            style="background:#0d47a1; color:white;">

                            <tr>
                                <th>Paciente</th>
                                <th>Consulta</th>
                                <th>Servicios</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th width="120">Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($cuentas as $cuenta)

                                <tr>

                                    {{-- PACIENTE --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $cuenta->paciente->nombre }}
                                            {{ $cuenta->paciente->apellido }}
                                        </div>

                                        <small class="text-muted">
                                            ID:
                                            {{ $cuenta->paciente->id }}
                                        </small>

                                    </td>

                                    {{-- FECHA --}}
                                    <td>

                                        <div>
                                            {{ \Carbon\Carbon::parse($cuenta->created_at)->format('d/m/Y') }}
                                        </div>

                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($cuenta->created_at)->format('h:i A') }}
                                        </small>

                                    </td>

                                    {{-- SERVICIOS --}}
                                    <td>

                                        @foreach ($cuenta->detalles as $detalle)

                                            <div class="mb-1">

                                                <span class="badge bg-light text-dark border">

                                                    {{ $detalle->servicio->nombre }}

                                                </span>

                                            </div>

                                        @endforeach

                                    </td>

                                    {{-- TOTAL --}}
                                    <td>

                                        <span class="fw-bold text-success">

                                            RD$
                                            {{ number_format($cuenta->total, 2) }}

                                        </span>

                                    </td>

                                    {{-- ESTADO --}}
                                    <td>

                                        @if ($cuenta->estado == 'pendiente')

                                            <span class="badge bg-warning text-dark">
                                                Pendiente
                                            </span>
                                        @endif

                                        @if ($cuenta->estado == 'pagado')

                                            <span class="badge bg-success">
                                                Pagado
                                            </span>
                                        @endif

                                        @if ($cuenta->estado == 'parcial')

                                            <span class="badge bg-info">
                                                Parcial
                                            </span>
                                        @endif

                                    </td>

                                    {{-- ACCIONES --}}
                                    <td>

                                        <button
                                            class="btn btn-primary btn-sm"
                                            onclick="abrirModalCobro(
                                                {{ $cuenta->id }},
                                                '{{ $cuenta->paciente->nombre }} {{ $cuenta->paciente->apellido }}',
                                                '{{ $cuenta->total }}'
                                            )">

                                            <i class="fa-solid fa-money-bill"></i>

                                            Cobrar

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="fa-solid fa-circle-check fa-2x mb-3"></i>

                                            <div>
                                                No hay cuentas pendientes.
                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- MODAL COBRO --}}
    <div class="modal fade"
        id="modalCobro"
        tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content border-0"
                style="border-radius:18px;">

                <form
                    method="POST"
                    action="{{ route('caja.cobrarCuenta') }}">

                    @csrf

                    <input type="hidden"
                        name="cuenta_id"
                        id="cuenta_id">

                    <div class="modal-header border-0 pb-0">

                        <h5 class="modal-title fw-bold">

                            Registrar Cobro

                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Paciente

                            </label>

                            <input
                                type="text"
                                id="paciente_nombre"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Total a Cobrar

                            </label>

                            <input
                                type="text"
                                id="monto_total"
                                class="form-control fw-bold text-success"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Método de Pago

                            </label>

                            <select
                                name="metodo_pago"
                                class="form-select"
                                required>

                                <option value="">
                                    Seleccione
                                </option>

                                <option value="Efectivo">
                                    Efectivo
                                </option>

                                <option value="Transferencia">
                                    Transferencia
                                </option>

                                <option value="Tarjeta">
                                    Tarjeta
                                </option>

                                <option value="Seguro Médico">
                                    Seguro Médico
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="modal-footer border-0">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Cancelar

                        </button>

                        <button
                            type="submit"
                            class="btn btn-success">

                            <i class="fa-solid fa-check me-1"></i>

                            Confirmar Cobro

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection

@push('scripts')

<script>

    function abrirModalCobro(
        cuentaId,
        paciente,
        total
    ) {

        document.getElementById('cuenta_id').value = cuentaId;

        document.getElementById('paciente_nombre').value = paciente;

        document.getElementById('monto_total').value =
            'RD$ ' + parseFloat(total).toFixed(2);

        let modal = new bootstrap.Modal(
            document.getElementById('modalCobro')
        );

        modal.show();
    }

</script>

@endpush
