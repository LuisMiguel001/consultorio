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

                <div class="card-header text-center" style="background:#a97bc9;color:white;border-radius:18px 18px 0 0;">
                    <h4>Registro de Paciente</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('pacientes.store') }}" method="POST" novalidate>
                        @csrf

                        @include('_paciente_form', ['paciente' => null])

                        <div class="text-center mt-4 d-flex justify-content-center gap-3">

                            <button type="submit" class="btn" style="background:#a97bc9;color:white;border-radius:8px;">
                                Guardar Paciente
                            </button>

                            <a href="{{ route('pacientes.lista') }}" class="btn btn-secondary" style="border-radius:8px;">
                                Cancelar
                            </a>
                            <button type="submit" name="accion" value="nuevo" class="btn btn-success">
                                Guardar y registrar otro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection
