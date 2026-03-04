<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consultorio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="background:#f4eef8;">

    <div class="d-flex">
        <!-- MENÚ LATERAL -->
        <div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
            <h5 class="text-center mb-4">Consultorio</h5>

            <ul class="nav flex-column">

                <!-- INICIO -->
                <li class="nav-item mb-2">
                    <a href="{{ route('pacientes.inicio') }}" class="nav-link text-white">
                        Inicio
                    </a>
                </li>

                <hr class="text-white">


                    <li class="nav-item mb-2">
                        <a href="{{ route('pacientes.create') }}" class="nav-link text-white">
                            Registrar Paciente
                        </a>
                    </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('pacientes.lista') }}" class="nav-link text-white">
                        Lista de Pacientes
                    </a>
                </li>

                <hr class="text-white">

                <!-- CITAS -->
                <li class="nav-item mb-2">
                    <a href="{{ route('citas.create') }}" class="nav-link text-white">
                        📅 Agendar Cita
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('citas.index') }}" class="nav-link text-white">
                        🗂 Lista de Citas
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('usuarios.index') }}" class="nav-link text-white">
                        👥 Usuarios
                    </a>
                </li>

                <div class="text-center mb-3">
                    @auth
                        👤 {{ auth()->user()->name }}
                        {{ auth()->user()->role }}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-light mt-2">Cerrar sesión</button>
                        </form>
                    @endauth
                </div>
            </ul>
        </div>

        <!-- CONTENIDO -->
        <div class="flex-fill p-4">
            @yield('content')
        </div>

    </div>

</body>

</html>
