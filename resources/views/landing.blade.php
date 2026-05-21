<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoctorClick - Sistema de Gestión Médica</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0d47a1;
            --primary-dark: #002171;
            --primary-light: #edf4ff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --bg: #f8fbff;
            --success: #2563eb;
            --shadow: 0 10px 30px rgba(13, 71, 161, .10);
            --radius: 20px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--text);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        /* =========================
           HEADER
        ==========================*/
        header {
            width: 100%;
            background: rgba(255, 255, 255, .95);
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, .7);
        }

        .navbar {
            height: 78px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary);
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 8px 20px rgba(13, 71, 161, .25);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 35px;
        }

        .nav-links a {
            color: #475569;
            font-size: .95rem;
            font-weight: 500;
            transition: .25s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            border: none;
            outline: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: .3s ease;
            font-weight: 600;
            border-radius: 14px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 14px 28px;
            box-shadow: 0 10px 25px rgba(13, 71, 161, .25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: var(--primary-dark);
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 2px solid #bfd5ff;
            padding: 14px 28px;
        }

        .btn-outline:hover {
            background: var(--primary-light);
        }

        /* =========================
           HERO
        ==========================*/
        .hero {
            background: linear-gradient(to bottom, #f5f9ff, #eef5ff);
            padding: 120px 0 110px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(13, 71, 161, .05);
            border-radius: 50%;
            top: -250px;
            right: -150px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(13, 71, 161, .04);
            border-radius: 50%;
            bottom: -220px;
            left: -120px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            margin: auto;
        }

        .hero h1 {
            font-size: 4rem;
            line-height: 1.08;
            color: var(--primary-dark);
            font-weight: 800;
            margin-bottom: 28px;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-light);
            max-width: 720px;
            margin: 0 auto 45px;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        /* =========================
           SECTIONS
        ==========================*/
        section {
            padding: 100px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 65px;
        }

        .section-header h2 {
            font-size: 3rem;
            color: var(--primary-dark);
            margin-bottom: 18px;
            font-weight: 800;
        }

        .section-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 700px;
            margin: auto;
            line-height: 1.8;
        }

        /* =========================
           FEATURES
        ==========================*/
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 35px 30px;
            border: 1px solid #edf2f7;
            transition: .35s ease;
            box-shadow: 0 5px 15px rgba(15, 23, 42, .03);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, .08);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #eff6ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 25px;
        }

        .feature-card h3 {
            font-size: 1.35rem;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.8;
            font-size: 1rem;
        }

        /* =========================
           PRICING
        ==========================*/
        .pricing {
            background: #f5f9ff;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            align-items: center;
        }

        .price-card {
            background: white;
            border-radius: 28px;
            padding: 45px 35px;
            position: relative;
            transition: .35s ease;
            border: 1px solid #e2e8f0;
        }

        .price-card:hover {
            transform: translateY(-8px);
        }

        .price-card.popular {
            background: linear-gradient(180deg, #0d47a1, #114fb1);
            color: white;
            transform: scale(1.04);
            box-shadow: 0 30px 50px rgba(13, 71, 161, .25);
        }

        .badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            color: var(--primary);
            font-size: .75rem;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 999px;
        }

        .plan-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .price {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .price span {
            font-size: 1rem;
            font-weight: 500;
        }

        .features-list {
            list-style: none;
            margin-bottom: 35px;
        }

        .features-list li {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: inherit;
        }

        .features-list i {
            font-size: .95rem;
        }

        .price-card .btn {
            width: 100%;
        }

        .popular .btn {
            background: white;
            color: var(--primary);
        }

        /* =========================
           CTA
        ==========================*/
        .cta {
            background: linear-gradient(135deg, #0d47a1, #002171);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
            top: -200px;
            right: -100px;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta h2 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 800;
        }

        .cta p {
            font-size: 1.15rem;
            opacity: .9;
            max-width: 700px;
            margin: auto auto 40px;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .btn-light {
            background: white;
            color: var(--primary);
            padding: 14px 28px;
        }

        .btn-dark {
            background: #00154d;
            color: white;
            padding: 14px 28px;
        }

        .btn-dark:hover {
            background: #02103a;
        }

        /* =========================
           FOOTER
        ==========================*/
        footer {
            background: #001b63;
            color: white;
            padding: 80px 0 30px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-brand p {
            margin-top: 18px;
            color: #cbd5e1;
            line-height: 1.8;
            max-width: 320px;
        }

        .footer-column h4 {
            margin-bottom: 22px;
            font-size: 1.1rem;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-links a {
            color: #cbd5e1;
            transition: .25s;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding-top: 25px;
            text-align: center;
            color: #cbd5e1;
        }

        /* =========================
           MOBILE
        ==========================*/
        .menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }

        @media(max-width:992px) {

            .hero h1 {
                font-size: 3rem;
            }

            .section-header h2,
            .cta h2 {
                font-size: 2.4rem;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:768px) {

            .menu-btn {
                display: block;
            }

            .nav-links {
                position: absolute;
                top: 78px;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                align-items: flex-start;
                padding: 30px;
                gap: 20px;
                border-bottom: 1px solid var(--border);
                display: none;
            }

            .nav-links.active {
                display: flex;
            }

            .hero {
                padding: 90px 0;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-header h2,
            .cta h2 {
                font-size: 2rem;
            }

            .price-card.popular {
                transform: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:500px) {

            .hero h1 {
                font-size: 2rem;
            }

            .btn,
            .btn-primary,
            .btn-outline {
                width: 100%;
            }

            .hero-buttons,
            .cta-buttons {
                flex-direction: column;
            }

            .section-header h2 {
                font-size: 1.8rem;
            }

            .price {
                font-size: 3rem;
            }
        }
    </style>
</head>

<body>

    <!-- =========================
     HEADER
========================= -->
    <header>
        <div class="container">
            <nav class="navbar">

                <a href="/" class="logo">
                    <div class="logo-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <span>DoctorClick</span>
                </a>

                <div class="nav-links" id="navLinks">
                    <a href="#caracteristicas">Características</a>
                    <a href="#precios">Precios</a>
                    <a href="#demo">Demo</a>

                    <a href="{{ route('login') }}" class="btn btn-primary" style="color:white;">
                        Comenzar
                    </a>
                </div>

                <button class="menu-btn" id="menuBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>

            </nav>
        </div>
    </header>

    <!-- =========================
     HERO
========================= -->
    <section class="hero">

        <div class="container">
            <div class="hero-content">

                <h1>
                    El Sistema de Gestión Médica
                    que Transforma tu Práctica
                </h1>

                <p>
                    Simplifica la administración de tu consultorio con DoctorClick.
                    Agenda, expedientes digitales, recetas electrónicas y mucho más,
                    todo en un solo lugar.
                </p>

                <div class="hero-buttons">

                    <form action="{{ route('demo.crear') }}" method="POST">

                        @csrf

                        <button type="submit" class="btn btn-light">

                            <i class="fa-solid fa-bolt"></i>
                            Probar Demo Gratis

                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Comenzar Ahora
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>
        </div>

    </section>

    <section id="caracteristicas">

        <div class="container">

            <div class="section-header">
                <h2>
                    Todo lo que necesitas para gestionar tu consultorio
                </h2>

                <p>
                    Herramientas profesionales diseñadas específicamente
                    para médicos y clínicas
                </p>
            </div>

            <div class="features-grid">

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-regular fa-calendar"></i>
                    </div>

                    <h3>Gestión de Citas</h3>

                    <p>
                        Agenda y administra citas médicas de manera eficiente
                        con recordatorios automáticos.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>

                    <h3>Expedientes Digitales</h3>

                    <p>
                        Almacena y accede a historiales clínicos completos
                        de forma segura y organizada.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>

                    <h3>Recetas Electrónicas</h3>

                    <p>
                        Genera y envía recetas médicas digitales con firma
                        electrónica válida.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>

                    <h3>Reportes y Estadísticas</h3>

                    <p>
                        Visualiza métricas clave de tu consultorio con dashboards
                        intuitivos y análisis avanzados.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-regular fa-clock"></i>
                    </div>

                    <h3>Gestión de Tiempo</h3>

                    <p>
                        Optimiza tu agenda y reduce tiempos de espera
                        mediante automatización inteligente.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-heart"></i>
                    </div>

                    <h3>Seguridad HIPAA</h3>

                    <p>
                        Protección de datos médicos con cifrado
                        de nivel empresarial.
                    </p>
                </div>

            </div>

        </div>

    </section>

    <!-- =========================
     PRICING
========================= -->
    <section class="pricing" id="precios">

        <div class="container">

            <div class="section-header">
                <h2>
                    Planes que se adaptan a tu práctica
                </h2>

                <p>
                    Elige el plan perfecto para ti. Cancela cuando quieras.
                </p>
            </div>

            <div class="pricing-grid">

                <!-- BASIC -->
                <div class="price-card">

                    <div class="plan-name">Básico</div>

                    <div class="price">
                        $49
                        <span>/mes</span>
                    </div>

                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> Hasta 100 pacientes</li>
                        <li><i class="fa-regular fa-circle-check"></i> Agenda digital</li>
                        <li><i class="fa-regular fa-circle-check"></i> Expedientes básicos</li>
                        <li><i class="fa-regular fa-circle-check"></i> Soporte por email</li>
                    </ul>

                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Seleccionar Plan
                    </a>

                </div>

                <!-- PRO -->
                <div class="price-card popular">

                    <div class="badge">
                        MÁS POPULAR
                    </div>

                    <div class="plan-name">Profesional</div>

                    <div class="price">
                        $99
                        <span>/mes</span>
                    </div>

                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> Pacientes ilimitados</li>
                        <li><i class="fa-regular fa-circle-check"></i> Recetas electrónicas</li>
                        <li><i class="fa-regular fa-circle-check"></i> Reportes avanzados</li>
                        <li><i class="fa-regular fa-circle-check"></i> Soporte prioritario</li>
                        <li><i class="fa-regular fa-circle-check"></i> Integraciones API</li>
                    </ul>

                    <a href="{{ route('login') }}" class="btn">
                        Seleccionar Plan
                    </a>

                </div>

                <!-- CLINIC -->
                <div class="price-card">

                    <div class="plan-name">Clínica</div>

                    <div class="price">
                        $199
                        <span>/mes</span>
                    </div>

                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> Múltiples médicos</li>
                        <li><i class="fa-regular fa-circle-check"></i> Todo lo de Profesional</li>
                        <li><i class="fa-regular fa-circle-check"></i> Dashboard administrativo</li>
                        <li><i class="fa-regular fa-circle-check"></i> Soporte 24/7</li>
                        <li><i class="fa-regular fa-circle-check"></i> Personalización completa</li>
                    </ul>

                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Seleccionar Plan
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- =========================
     CTA
========================= -->
    <section class="cta" id="demo">

        <div class="container">

            <div class="cta-content">

                <h2>
                    ¿Listo para modernizar tu consultorio?
                </h2>

                <p>
                    Únete a cientos de profesionales de la salud
                    que ya confían en DoctorClick.
                </p>

                <div class="cta-buttons">

                    <a href="#" class="btn btn-light">
                        Probar Demo Gratis
                    </a>

                    <a href="{{ route('login') }}" class="btn btn-dark">
                        Comenzar Ahora
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- =========================
     FOOTER
========================= -->
    <footer>

        <div class="container">

            <div class="footer-grid">

                <div class="footer-brand">

                    <a href="/" class="logo" style="color:white;">
                        <div class="logo-icon">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                        <span>DoctorClick</span>
                    </a>

                    <p>
                        La solución completa para la gestión
                        de tus consultas.
                    </p>

                </div>

                <div class="footer-column">

                    <h4>Producto</h4>

                    <div class="footer-links">
                        <a href="#caracteristicas">Características</a>
                        <a href="#precios">Precios</a>
                        <a href="#demo">Demo</a>
                    </div>

                </div>

                <div class="footer-column">

                    <h4>Empresa</h4>

                    <div class="footer-links">
                        <a href="#">Sobre Nosotros</a>
                        <a href="#">Blog</a>
                        <a href="#">Contacto</a>
                    </div>

                </div>

                <div class="footer-column">

                    <h4>Legal</h4>

                    <div class="footer-links">
                        <a href="#">Privacidad</a>
                        <a href="#">Términos</a>
                        <a href="#">Seguridad</a>
                    </div>

                </div>

            </div>

            <div class="footer-bottom">
                © {{ date('Y') }} DoctorClick. Todos los derechos reservados.
            </div>

        </div>

    </footer>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const navLinks = document.getElementById('navLinks');

        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>

</body>

</html>
