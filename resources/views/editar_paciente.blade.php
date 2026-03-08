@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #0d47a1;
            --primary-dark: #002171;
            --primary-light: #e8f1fb;
            --primary-soft: #f4f8fd;
            --primary-border: #90caf9;
            --text-primary: #0a1a2f;
            --text-secondary: #1565c0;
        }

        body {
            background: var(--primary-soft);
        }

        .paciente-card {
            border-radius: 18px;
            background: var(--primary-light);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .07);
        }

        .paciente-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 18px 18px 0 0;
            padding: 1.5rem;
        }

        .paciente-card input.form-control,
        .paciente-card select.form-select,
        .paciente-card textarea.form-control {
            border-radius: 8px;
            border: 1px solid var(--primary-border);
        }

        .paciente-card button.btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
        }

        .paciente-card .btn-secondary {
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d0f0fd;
            color: var(--primary-dark);
            border-radius: 6px;
        }

        .alert-danger {
            background-color: #fdd0d0;
            color: #a70000;
            border-radius: 6px;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
        }
    </style>

    <div class="container my-5">
        <div class="card shadow border-0 paciente-card">

            <div class="card-header text-center">
                <h4>Editar Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}</h4>
            </div>

            <div class="card-body p-4">

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

                <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('_paciente_form', ['paciente' => $paciente])

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            Actualizar Paciente
                        </button>
                        <a href="{{ route('pacientes.lista') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
