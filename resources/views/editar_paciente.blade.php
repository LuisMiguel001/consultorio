@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4eef8;">

<div class="container my-5">
    <div class="card shadow border-0" style="border-radius:18px;">

        <div class="card-header text-center"
             style="background:#a97bc9;color:white;border-radius:18px 18px 0 0;">
            <h4>Editar Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}</h4>
        </div>

        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('_paciente_form', ['paciente' => $paciente])

                <div class="text-center mt-4">
                    <button type="submit"
                            class="btn"
                            style="background:#a97bc9;color:white;border-radius:8px;">
                        Actualizar Paciente
                    </button>
                    <a href="{{ route('pacientes.lista') }}"
                       class="btn btn-secondary"
                       style="border-radius:8px;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
@endsection
