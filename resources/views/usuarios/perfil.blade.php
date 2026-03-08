@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Mi Perfil</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Información básica --}}
        <div class="card mb-4">
            <div class="card-header">Información</div>
            <div class="card-body">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="card">
            <div class="card-header">Cambiar Contraseña</div>
            <div class="card-body">
                <form action="{{ route('perfil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Contraseña actual</label>
                        <input type="password" name="current_password" class="form-control">
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-warning">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
@endsection
