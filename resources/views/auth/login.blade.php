<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-background {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* CARD */
        .login-box {
            width: 380px;
            padding: 42px 36px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-radius: 22px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.6s ease;
        }

        /* TÍTULO */
        .login-box h3 {
            color: #1e293b;
            text-align: center;
            margin-bottom: 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* INPUTS */
        .user-box {
            position: relative;
            margin-bottom: 28px;
        }

        .user-box input {
            width: 100%;
            padding: 10px 4px;
            font-size: 15px;
            color: #1e293b;
            border: none;
            border-bottom: 2px solid #e2e8f0;
            background: transparent;
            outline: none;
            transition: border-color 0.3s;
        }

        .user-box input:focus {
            border-bottom-color: #94a3b8;
        }

        .user-box label {
            position: absolute;
            top: 10px;
            left: 4px;
            color: #64748b;
            font-size: 14px;
            pointer-events: none;
            transition: 0.3s;
        }

        .user-box input:focus~label,
        .user-box input:not(:placeholder-shown)~label {
            top: -14px;
            font-size: 12px;
            color: #334155;
            font-weight: 600;
        }

        /* BOTÓN */
        .login-button {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 14px;
            border: none;
            color: #ffffff;
            background: linear-gradient(135deg, #2c3e50, #1e293b);
            transition: all 0.3s ease;
        }

        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(30, 41, 59, 0.15);
        }

        .login-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* LINK */
        .login-box a {
            color: #2c3e50;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
            color: #1e293b;
        }

        /* ALERTA */
        .login-box .alert {
            margin-top: 18px;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        /* ANIMACIÓN */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <section class="login-background">
        <div class="login-box">
            <h3>Iniciar Sesión</h3>

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="user-box">
                    <input type="text" id="email" name="email" required placeholder=" "
                        value="{{ old('email') }}">
                    <label for="email">Usuario</label>
                </div>

                <div class="user-box">
                    <input type="password" id="password" name="password" required placeholder=" ">
                    <label for="password">Contrase</label>
                </div>

                <button class="login-button" type="submit" id="submitBtn">
                    <span>Entrar</span>
                </button>
            </form>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para simular el estado "procesando" del botón
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const originalContent = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> <span>Procesando...</span>';
        });
    </script>
</body>

</html>
