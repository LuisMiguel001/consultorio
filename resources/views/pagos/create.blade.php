@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="card shadow border-0 rounded-4">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Registrar Pago
            </h4>

        </div>

        <div class="card-body">

            <form action="{{ route('pagos.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Suscripción
                    </label>

                    <select name="suscripcion_id"
                            class="form-select"
                            required>

                        <option value="">
                            Seleccionar
                        </option>

                        @foreach($suscripciones as $suscripcion)

                            <option value="{{ $suscripcion->id }}">

                                {{ $suscripcion->consultorio->nombre }}
                                -
                                {{ $suscripcion->plan->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Método de pago
                        </label>

                        <select name="metodo_pago"
                                class="form-select"
                                required>

                            <option value="transferencia">
                                Transferencia bancaria
                            </option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Monto
                        </label>

                        <input type="number"
                               step="0.01"
                               name="monto"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Referencia transferencia
                    </label>

                    <input type="text"
                           name="referencia"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Comprobante
                    </label>

                    <input type="file"
                           name="comprobante"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Notas
                    </label>

                    <textarea name="notas"
                              class="form-control"
                              rows="4"></textarea>

                </div>

                <button class="btn btn-primary">
                    Registrar Pago
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
