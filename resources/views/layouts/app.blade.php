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
            background: #f0f2f5;
        }

        /* ===== SIDEBAR CON COLORES INVERTIDOS ===== */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            color: #2c3e50;
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.05);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 100;
        }

        /* ESTADO INICIAL CERRADO */
        .sidebar {
            width: 80px;
        }

        .sidebar.expanded {
            width: 260px;
        }

        /* ===== LOGO CON COMPORTAMIENTO HÍBRIDO ===== */
        .sidebar .logo {
            display: flex;
            padding: 20px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            background: rgba(13, 71, 161, 0.02);
            min-height: 80px;
            width: 100%;
            transition: all 0.3s ease;
        }

        /* Cuando está ABIERTO - horizontal (uno al lado del otro) */
        .sidebar.expanded .logo {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar.expanded .logo-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar.expanded .toggle-btn {
            margin-left: auto;
            /* Empuja el botón a la derecha */
        }

        /* Cuando está CERRADO - vertical (apilados) */
        .sidebar:not(.expanded) .logo {
            flex-direction: column-reverse;
            align-items: center;
            justify-content: center;
            padding: 15px 5px;
            min-height: 100px;
        }

        .sidebar:not(.expanded) .logo-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
        }

        .sidebar:not(.expanded) .logo-content span {
            display: none;
        }

        .sidebar:not(.expanded) .toggle-btn {
            margin: 0 auto;
            /* Centrado */
        }

        /* Elementos comunes del logo */
        .sidebar .logo img {
            flex-shrink: 0;
            width: 35px;
            height: 35px;
        }

        .sidebar .logo span {
            color: #0d47a1;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }

        /* Botón toggle - estilos base */
        .toggle-btn {
            border: none;
            background: rgba(13, 71, 161, 0.1);
            color: #0d47a1;
            font-size: 1.3rem;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            flex-shrink: 0;
        }

        .toggle-btn:hover {
            background: rgba(13, 71, 161, 0.2);
            transform: scale(1.05);
        }

        /* Menú principal */
        .menu {
            flex-grow: 1;
            padding-top: 15px;
        }

        /* Enlaces del menú */
        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #2c3e50;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 10px;
            margin: 4px 12px;
            transition: all 0.2s ease;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Iconos - más visibles */
        .menu i {
            font-size: 1.3rem;
            color: #0d47a1;
            width: 24px;
            text-align: center;
            transition: 0.2s;
            flex-shrink: 0;
        }

        /* Hover en enlaces */
        .menu a:hover {
            background: linear-gradient(90deg, rgba(13, 71, 161, 0.08) 0%, rgba(13, 71, 161, 0.02) 100%);
            color: #0d47a1;
        }

        .menu a:hover i {
            transform: scale(1.1);
            color: #0d47a1;
        }

        /* Enlace activo */
        .menu a.active {
            background: rgba(13, 71, 161, 0.1);
            color: #0d47a1;
            font-weight: 600;
            border-left: 3px solid #0d47a1;
        }

        .menu a.active i {
            color: #0d47a1;
        }

        /* Sidebar colapsado - menú */
        .sidebar:not(.expanded) .menu span {
            display: none;
        }

        .sidebar:not(.expanded) .menu a {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar:not(.expanded) .menu i {
            font-size: 1.5rem;
            margin: 0;
        }

        /* ===== USER BOX ===== */
        .user-box {
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            padding: 15px;
            text-align: center;
        }

        .user-box .fw-bold {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0d47a1;
            font-size: 0.95rem;
            margin-bottom: 12px;
            white-space: nowrap;
        }

        .user-box i {
            font-size: 1.3rem;
            color: #0d47a1;
            flex-shrink: 0;
        }

        /* Botón de logout */
        .logout-btn {
            background: transparent;
            border: 1px solid rgba(13, 71, 161, 0.3);
            color: #0d47a1;
            border-radius: 8px;
            padding: 8px 16px;
            width: 100%;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.2s;
            white-space: nowrap;
        }

        .logout-btn:hover {
            background: #0d47a1;
            color: white;
            border-color: #0d47a1;
        }

        .logout-btn:hover i {
            color: white;
        }

        /* Sidebar colapsado - user box */
        .sidebar:not(.expanded) .user-box .fw-bold span,
        .sidebar:not(.expanded) .logout-btn span {
            display: none;
        }

        .sidebar:not(.expanded) .user-box .fw-bold {
            justify-content: center;
        }

        .sidebar:not(.expanded) .logout-btn {
            padding: 10px;
            justify-content: center;
        }

        .sidebar:not(.expanded) .logout-btn i {
            margin: 0;
            font-size: 1.3rem;
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .main-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            padding: 30px;
            background: #f0f2f5;
            transition: width 0.3s ease;
            width: calc(100% - 80px);
        }

        .sidebar.expanded~.content {
            width: calc(100% - 260px);
        }

        /* Tarjetas y elementos del contenido */
        .content-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Asegurar que los elementos internos usen todo el ancho */
        .content .container,
        .content .container-fluid {
            width: 100%;
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            }

            .content {
                width: calc(100% - 80px);
                margin-left: 80px;
            }

            .sidebar.expanded~.content {
                width: calc(100% - 260px);
                margin-left: 260px;
            }
        }
    </style>
</head>

<body>

    <div class="main-wrapper">
        <!-- Sidebar con estado inicial CERRADO -->
        <div class="sidebar" id="sidebar">

            <div class="logo">
                <div class="logo-content">
                    <img src="https://i.postimg.cc/tCkfpLSY/Whats-App-Image-2026-03-09-at-5-10-37-PM.png" height="30"
                        width="30" alt="logo" />
                    <span>Dr. Lorenzo</span>
                </div>
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <div class="menu">
                @auth
                    <a href="{{ route('pacientes.inicio') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Inicio</span>
                    </a>
                @endauth

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

                @auth
                    <a href="{{ route('perfil') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Mi Perfil</span>
                    </a>
                @endauth

                @role('admin')
                    <a href="{{ route('usuarios.index') }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Usuarios</span>
                    </a>
                @endrole


                <!-- USER BOX -->
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
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("expanded");
        }

        // Marcar enlace activo basado en la URL actual
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.pathname;
            document.querySelectorAll('.menu a').forEach(link => {
                if (link.getAttribute('href') === currentUrl) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @yield('scripts')

</body>

</html>
