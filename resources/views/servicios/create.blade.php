@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-primary text-white">
            Nuevo Servicio
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('servicios.store') }}">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Precio
                    </label>

                    <input type="number"
                           step="0.01"
                           name="precio"
                           class="form-control"
                           required>

                </div>

                <div class="form-check mb-4">

                    <input type="checkbox"
                           name="activo"
                           value="1"
                           checked
                           class="form-check-input">

                    <label class="form-check-label">
                        Servicio activo
                    </label>

                </div>

                <button class="btn btn-primary">
                    Guardar Servicio
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
