<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultorio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4eef8;">

<div class="d-flex">

    <!-- MENÚ LATERAL -->
    <div class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
        <h5 class="text-center mb-4">Consultorio</h5>

        <ul class="nav flex-column">

            <li class="nav-item mb-2">
                <a href="{{ route('pacientes.create') }}"
                   class="nav-link text-white">
                   🧑 Registrar Paciente
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('pacientes.index') }}"
                   class="nav-link text-white">
                   📋 Lista de Pacientes
                </a>
            </li>

        </ul>
    </div>

    <!-- CONTENIDO -->
    <div class="flex-fill p-4">
        @yield('content')
    </div>

</div>

</body>
</html>
