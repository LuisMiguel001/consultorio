<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center" style="height:100vh; background:#f4eef8;">

<div class="card p-4 shadow" style="width:350px;">
    <h4 class="text-center mb-3">Iniciar Sesión</h4>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

<form method="POST" action="{{ route('login.post') }}">
        @csrf

        <input type="email" name="email" class="form-control mb-3" placeholder="Correo" required>

        <input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>

        <button class="btn btn-dark w-100">Entrar</button>
    </form>
</div>

</body>
</html>
