<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        /* CARD PRINCIPAL - MÁS PEQUEÑA */
        .login-card {
            width: 400px;
            max-width: 90%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        /* HEADER OPCIONAL */
        .login-header {
            background: linear-gradient(135deg, #0f766e, #115e59);
            padding: 20px;
            text-align: center;
        }

        .login-header h4 {
            margin: 0;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
        }

        /* LADO DERECHO - FORMULARIO */
        .form-side {
            padding: 35px 30px;
        }

        .form-side h3 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: #1e293b;
            font-size: 1.5rem;
        }

        /* INPUT ESTILO MODERNO */
        .user-box {
            position: relative;
            margin-bottom: 30px;
        }

        .user-box input {
            width: 100%;
            border: none;
            border-bottom: 2px solid #e2e8f0;
            outline: none;
            padding: 10px 5px 5px 5px;
            font-size: 15px;
            background: transparent;
            transition: border-color 0.3s ease;
        }

        .user-box label {
            position: absolute;
            top: 10px;
            left: 5px;
            color: #94a3b8;
            font-size: 14px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .user-box input:focus~label,
        .user-box input:not(:placeholder-shown)~label {
            top: -12px;
            font-size: 11px;
            color: #0f766e;
            font-weight: 600;
        }

        .user-box input:focus {
            border-bottom: 2px solid #0f766e;
        }

        /* BOTÓN MODERNO */
        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            color: white;
            background: linear-gradient(135deg, #0f766e, #115e59);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 71, 161, 0.2);
            background: linear-gradient(135deg, #115e59, #0f766e);
        }

        .login-button:active {
            transform: translateY(0);
        }

        /* ALERTAS */
        .alert {
            border-radius: 12px;
            font-size: 13px;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 0;
        }

        /* ANIMACIÓN */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 480px) {
            .form-side {
                padding: 25px 20px;
            }

            .form-side h3 {
                font-size: 1.3rem;
                margin-bottom: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <!-- HEADER OPCIONAL (COMENTADO SI NO LO QUIERES)
        <div class="login-header">
            <h4>Sistema Médico</h4>
        </div>
        -->

        <!-- FORMULARIO -->
        <div class="form-side">
            <h3>Iniciar Sesión</h3>
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="user-box">
                    <input type="text" name="email" required placeholder=" " value="{{ old('email') }}"
                        autocomplete="email" autofocus>
                    <label>Usuario</label>
                </div>

                <div class="user-box">
                    <input type="password" name="password" required placeholder=" ">
                    <label>Contraseña</label>
                </div>

                <button type="submit" class="login-button" id="submitBtn">
                    Ingresar
                </button>

                {{-- ERRORES DE VALIDACIÓN --}}
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">

                        @foreach ($errors->all() as $error)
                            <div>
                                • {{ $error }}
                            </div>
                        @endforeach

                    </div>
                @endif

                {{-- ERROR SIMPLE --}}
                @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>

</html>
