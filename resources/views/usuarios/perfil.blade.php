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

        .perfil-card {
            max-width: 750px;
            margin: auto;
            margin-top: 30px;
            background: var(--primary-light);
            color: var(--primary-dark);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }

        .perfil-card .card-header {
            background: var(--primary-color);
            color: white;
            border-radius: 16px 16px 0 0;
            font-weight: bold;
        }

        .perfil-card .form-label {
            font-weight: 500;
        }

        .perfil-card .btn-primary,
        .perfil-card .btn-warning {
            border-radius: 8px;
            color: white;
        }

        .perfil-card .btn-primary {
            background: var(--primary-color);
        }

        .perfil-card .btn-warning {
            background: var(--primary-border);
            color: white;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }
    </style>

    <div class="container">

        <RadzenCard class="perfil-card">
            <ChildContent>
                {{-- Información Básica --}}
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-4 border-0 shadow-sm" style="border-radius:14px;">
                        <div class="card-header border-0 d-flex justify-content-center align-items-center"
                            style=" color: white; border-radius:18px 18px 0 0; background: #0d47a1;">
                            <h5 class="mb-0">Mi Perfil</h5>
                        </div>
                        <div class="card-body">

                            <h6 class="mb-3 text-uppercase fw-bold" style="color:var(--primary-color);">
                                Información Personal
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Usuario</label>
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Cambiar Contraseña --}}
                <form action="{{ route('perfil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-4 border-0 shadow-sm" style="border-radius:14px;">
                        <div class="card-body">
                            <h6 class="mb-3 text-uppercase fw-bold" style="color:var(--primary-color);">
                                Cambiar Contraseña
                            </h6>

                            <div class="mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" name="current_password" class="form-control">
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <button class="btn btn-secondary">
                                Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </form>
            </ChildContent>
        </RadzenCard>
    </div>
@endsection
