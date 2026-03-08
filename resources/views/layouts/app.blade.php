<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consultorio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #f4f8fd;
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0d47a1, #002171);
            color: white;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo {
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .menu {
            flex-grow: 1;
            padding-top: 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            margin: 4px 10px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .menu i {
            font-size: 18px;
        }

        .sidebar.collapsed span {
            display: none;
        }

        /* USER */

        .user-box {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 15px;
            text-align: center;
        }

        .logout-btn {
            background: white;
            color: #0d47a1;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            margin-top: 8px;
        }

        /* CONTENT */

        .content {
            flex-grow: 1;
            padding: 25px;
        }

        /* BUTTON TOGGLE */

        .toggle-btn {
            border: none;
            background: none;
            color: white;
            font-size: 20px;
            margin: 10px;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="sidebar" id="sidebar">

            <div class="logo d-flex justify-content-between align-items-center">

                <span>Dr. Lorenzo</span>

                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>

            </div>

            <div class="menu">

                @hasanyrole('admin|doctor')
                    <a href="{{ route('pacientes.inicio') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Inicio</span>
                    </a>
                @endhasanyrole


                @can('ver pacientes')
                    <a href="{{ route('pacientes.lista') }}">
                        <i class="bi bi-people"></i>
                        <span>Lista de Pacientes</span>
                    </a>
                @endcan


                @can('crear pacientes')
                    <a href="{{ route('pacientes.create') }}">
                        <i class="bi bi-person-plus"></i>
                        <span>Registrar Paciente</span>
                    </a>
                @endcan


                @can('ver citas')
                    <a href="{{ route('citas.index') }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Lista de Citas</span>
                    </a>
                @endcan


                @can('crear citas')
                    <a href="{{ route('citas.create') }}">
                        <i class="bi bi-journal-medical"></i>
                        <span>Agendar Cita</span>
                    </a>
                @endcan


                @can('ver pacientes')
                    <a href="{{ route('pacientes.archivados') }}">
                        <i class="bi bi-archive"></i>
                        <span>Archivados</span>
                    </a>
                @endcan


                @can('ver perfil')
                    <a href="{{ route('perfil') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Mi Perfil</span>
                    </a>
                @endcan


                @role('admin')
                    <a href="{{ route('usuarios.index') }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Usuarios</span>
                    </a>
                @endrole

                <div class="user-box">

                    @auth

                        <div class="fw-bold">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="logout-btn">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Salir</span>
                            </button>
                        </form>

                    @endauth

                </div>
            </div>
        </div>

        <!-- CONTENT -->

        <div class="content flex-fill">

            @yield('content')

        </div>

    </div>

    <script>
        function toggleSidebar() {

            let sidebar = document.getElementById("sidebar");

            sidebar.classList.toggle("collapsed");
        }
    </script>

    @yield('scripts')

</body>

</html>
