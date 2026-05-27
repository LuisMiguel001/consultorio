@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="card border-0 shadow">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between">

                <h5 class="mb-0">
                    Factura #{{ $cuenta->id }}
                </h5>

                <span>
                    {{ $cuenta->created_at->format('d/m/Y h:i A') }}
                </span>

            </div>

        </div>

        <div class="card-body">

            <h6>
                Paciente:
                {{ $cuenta->paciente->nombre }}
                {{ $cuenta->paciente->apellido }}
            </h6>

            <hr>

            <table class="table">

                <thead>

                    <tr>

                        <th>Servicio</th>

                        <th>Precio</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($cuenta->detalles as $detalle)

                        <tr>

                            <td>
                                {{ $detalle->servicio->nombre }}
                            </td>

                            <td>
                                RD$
                                {{ number_format($detalle->precio, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="text-end">

                <h4 class="text-success">

                    Total:
                    RD$
                    {{ number_format($cuenta->total, 2) }}

                </h4>

            </div>

            @if($cuenta->estado != 'pagado')

                <hr>

                <form
                    method="POST"
                    action="{{ route('cuentas.cobrar') }}"
                >

                    @csrf

                    <input
                        type="hidden"
                        name="cuenta_id"
                        value="{{ $cuenta->id }}"
                    >

                    <div class="row">

                        <div class="col-md-4">

                            <label>
                                Método Pago
                            </label>

                            <select
                                name="metodo_pago"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Seleccione
                                </option>

                                <option value="efectivo">
                                    Efectivo
                                </option>

                                <option value="transferencia">
                                    Transferencia
                                </option>

                                <option value="tarjeta">
                                    Tarjeta
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="mt-3">

                        <button class="btn btn-success">

                            Cobrar

                        </button>

                    </div>

                </form>

            @endif

        </div>

    </div>

</div>

@endsection
