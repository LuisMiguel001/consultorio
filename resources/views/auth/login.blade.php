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
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: system-ui;
        }

        /* CARD PRINCIPAL */

        .login-card {
            width: 850px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            animation: fadeIn .6s ease;
        }

        /* LADO IZQUIERDO */

        .logo-side {
            flex: 1;
            background: linear-gradient(135deg, #0ea5a5, #0f766e);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* CORAZON */

        .heart {
            width: 260px;
            animation: heartbeat 1s infinite;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.2));
        }

        /* LATIDO */

        @keyframes heartbeat {

            0% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.07);
            }

            40% {
                transform: scale(1);
            }

            60% {
                transform: scale(1.12);
            }

            100% {
                transform: scale(1);
            }

        }

        /* LADO DERECHO */

        .form-side {
            flex: 1;
            padding: 45px 40px;
        }

        .form-side h3 {
            font-weight: 700;
            margin-bottom: 35px;
            text-align: center;
            color: #1e293b;
        }

        /* INPUT */

        .user-box {
            position: relative;
            margin-bottom: 30px;
        }

        .user-box input {
            width: 100%;
            border: none;
            border-bottom: 2px solid #e2e8f0;
            outline: none;
            padding: 8px 3px;
            font-size: 15px;
            background: transparent;
        }

        .user-box label {
            position: absolute;
            top: 8px;
            left: 2px;
            color: #64748b;
            font-size: 14px;
            transition: .3s;
            pointer-events: none;
        }

        .user-box input:focus~label,
        .user-box input:not(:placeholder-shown)~label {
            top: -14px;
            font-size: 12px;
            color: #0f766e;
            font-weight: 600;
        }

        .user-box input:focus {
            border-bottom: 2px solid #0f766e;
        }

        /* BOTON */

        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #0f766e, #115e59);
            transition: .3s;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* ANIMACION */

        @keyframes fadeIn {

            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- LADO IZQUIERDO -->
        <div class="logo-side">
            <img src="https://i.postimg.cc/tCkfpLSY/Whats-App-Image-2026-03-09-at-5-10-37-PM.png" class="heart"
                id="heart">
        </div>


        <!-- LADO DERECHO -->
        <div class="form-side">

            <h3>Iniciar Sesión</h3>

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="user-box">
                    <input type="text" name="email" required placeholder=" " value="{{ old('email') }}">
                    <label>Usuario</label>
                </div>

                <div class="user-box">
                    <input type="password" name="password" required placeholder=" ">
                    <label>Contraseña</label>
                </div>

                <button class="login-button" id="submitBtn">
                    Entrar
                </button>

            </form>

            @if (session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif

        </div>

    </div>


    <script>
        const sound = document.getElementById("heartbeatSound");
        let audioEnabled = false;

        // activar audio cuando el usuario interactúe
        document.addEventListener("click", () => {

            if (!audioEnabled) {
                sound.play().then(() => {

                    audioEnabled = true;

                    setInterval(() => {
                        sound.currentTime = 0;
                        sound.play();
                    }, 1000);

                }).catch(() => {});

            }

        }, {
            once: true
        });
    </script>

</body>

</html>
