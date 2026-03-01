@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4eef8;">

<div class="container my-5">
    <div class="card shadow border-0" style="border-radius:18px;">

        <div class="card-header text-center"
             style="background:#a97bc9;color:white;border-radius:18px 18px 0 0;">
            <h4>Registro de Paciente</h4>
        </div>

        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf

                <!-- DATOS PERSONALES -->
                <h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
                    Datos personales
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="apellido"
                               class="form-control @error('apellido') is-invalid @enderror"
                               value="{{ old('apellido') }}">
                        @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="cedula"
                               class="form-control @error('cedula') is-invalid @enderror"
                               value="{{ old('cedula') }}">
                        @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento') }}">
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Sexo</label>
                        <select name="sexo"
                                class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">--Seleccione--</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                        @error('sexo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <hr class="my-4">

                <!-- CONTACTO -->
                <h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
                    Información de contacto
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono"
                               class="form-control"
                               value="{{ old('telefono') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email"
                               class="form-control"
                               value="{{ old('email') }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion"
                               class="form-control"
                               value="{{ old('direccion') }}">
                    </div>

                </div>

                <hr class="my-4">

                <!-- SEGURO -->
                <h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
                    Información adicional
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">ARS</label>
                        <select name="seguro_medico" class="form-select">
                            <option value="">--Seleccione--</option>
                            <option>ARS SeNaSa</option>
                            <option>ARS Universal</option>
                            <option>ARS Humano</option>
                            <option>Mapfre Salud ARS</option>
                            <option>Primera ARS</option>
                            <option>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NSS</label>
                        <input type="text" name="nss"
                               class="form-control"
                               value="{{ old('nss') }}">
                    </div>

                </div>

                <div class="text-center mt-4">
                    <button type="submit"
                            class="btn"
                            style="background:#a97bc9;color:white;border-radius:8px;">
                        Guardar Paciente
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>

@endsection
