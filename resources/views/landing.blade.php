<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dr. Lorenzo - Tu Salud es Nuestra Prioridad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* Variables de colores y fuentes */
        :root {
            --primary-color: #2a7fba;
            --primary-dark: #1a5f8a;
            --secondary-color: #f8f9fa;
            --accent-color: #34c759;
            --text-dark: #333;
            --text-light: #666;
            --text-on-dark: #fff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: #f5f7fa;
        }

        h1,
        h2,
        h3,
        h4 {
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 2.5rem;
        }

        h2 {
            font-size: 2rem;
            color: var(--primary-color);
        }

        h3 {
            font-size: 1.5rem;
        }

        p {
            margin-bottom: 1rem;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 4px;
            background-color: var(--accent-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: var(--primary-color);
            color: var(--text-on-dark);
            border-radius: var(--border-radius);
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: var(--text-on-dark);
        }

        /* Header y navegación */
        header {
            background-color: var(--text-on-dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-icon {
            color: var(--primary-color);
            font-size: 2rem;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary-color);
        }

        /* HERO MODERNO (FIGMA STYLE) */
        .hero-modern {
            background-color: #f5f9ff;
            padding: 160px 0 100px;
            color: var(--text-dark);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            align-items: center;
            gap: 60px;
        }

        .hero-content h1 {
            font-size: 3rem;
            color: #1e3a5f;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 520px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e6f0ff;
            color: var(--primary-color);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .hero-info-left {
            justify-content: flex-start;
        }

        .hero-info {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-icon {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-top: 4px;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: var(--shadow);
        }

        .hero-card {
            position: absolute;
            bottom: 30px;
            left: -40px;
            background: #fff;
            padding: 20px 24px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-card i {
            font-size: 1.8rem;
            color: var(--accent-color);
        }

        .hero-card strong {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .hero-card span {
            display: block;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        @media (max-width: 576px) {
            .hero-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* Responsive Hero */
        @media (max-width: 992px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero-image {
                margin-top: 40px;
            }

            .hero-card {
                left: 20px;
            }
        }

        /* Sección Servicios */
        .services {
            background-color: var(--secondary-color);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background-color: var(--text-on-dark);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* Sección Doctor */
        .doctor {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .doctor-image {
            flex: 0 0 auto;
            width: clamp(320px, 50vw, 480px);
            /* Tamaño moderado y responsive */
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .doctor-image img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
            aspect-ratio: 3 / 4;
            /* Proporción de retrato */
        }

        .doctor-info {
            flex: 1;
        }

        .doctor-qualifications {
            margin-top: 30px;
        }

        .qualification-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .qualification-icon {
            color: var(--accent-color);
            margin-right: 15px;
            font-size: 1.2rem;
        }

        /* Responsive para pantallas pequeñas */
        @media (max-width: 768px) {
            .doctor {
                flex-direction: column;
                gap: 30px;
            }

            .doctor-image {
                width: clamp(180px, 60vw, 280px);
                margin: 0 auto;
            }
        }

        /* Sección Testimonios */
        .testimonials {
            background-color: var(--secondary-color);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        /* Estilos para el carrusel automático de testimonios */
        .testimonials-carousel-container {
            position: relative;
            overflow: hidden;
            padding: 20px 0;
            margin-top: 30px;
        }

        .testimonials-row {
            display: flex;
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
            position: relative;
        }

        .testimonials-track {
            display: flex;
            gap: 25px;
            padding: 10px 0;
            will-change: transform;
        }

        /* Estilos para las tarjetas del carrusel */
        .testimonials-row .testimonial-card {
            flex: 0 0 350px;
            min-width: 350px;
            background-color: var(--text-on-dark);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .testimonials-row .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Animaciones para las filas */
        .testimonials-row-top .testimonials-track {
            animation: scroll-left 40s linear infinite;
        }

        .testimonials-row-bottom .testimonials-track {
            animation: scroll-right 35s linear infinite;
        }

        /* Pausar animación al hacer hover */
        .testimonials-row:hover .testimonials-track {
            animation-play-state: paused;
        }

        /* Overlay para gradiente en los bordes */
        .carousel-overlay {
            position: absolute;
            top: 0;
            width: 100px;
            height: 100%;
            z-index: 10;
            pointer-events: none;
        }

        .carousel-overlay.left {
            left: 0;
            background: linear-gradient(to right,
                    var(--secondary-color) 0%,
                    rgba(248, 249, 250, 0.8) 50%,
                    transparent 100%);
        }

        .carousel-overlay.right {
            right: 0;
            background: linear-gradient(to left,
                    var(--secondary-color) 0%,
                    rgba(248, 249, 250, 0.8) 50%,
                    transparent 100%);
        }

        /* Animaciones clave */
        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        @keyframes scroll-right {
            0% {
                transform: translateX(-50%);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* Responsive para el carrusel */
        @media (max-width: 992px) {
            .testimonials-row .testimonial-card {
                flex: 0 0 300px;
                min-width: 300px;
            }

            .carousel-overlay {
                width: 60px;
            }
        }

        @media (max-width: 768px) {
            .testimonials-row .testimonial-card {
                flex: 0 0 280px;
                min-width: 280px;
            }

            .testimonials-row {
                margin-bottom: 20px;
            }

            .testimonials-track {
                gap: 20px;
            }

            .carousel-overlay {
                width: 40px;
            }

            .testimonials-row-top .testimonials-track {
                animation: scroll-left 30s linear infinite;
            }

            .testimonials-row-bottom .testimonials-track {
                animation: scroll-right 25s linear infinite;
            }
        }

        @media (max-width: 576px) {
            .testimonials-row .testimonial-card {
                flex: 0 0 260px;
                min-width: 260px;
                padding: 20px;
            }

            .carousel-overlay {
                width: 30px;
            }
        }

        .testimonial-card {
            background-color: var(--text-on-dark);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
        }

        .testimonial-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stars {
            color: #ffc107;
        }

        .patient-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .patient-since {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        /* Sección Contacto */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 50px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .contact-icon {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-top: 5px;
        }

        .contact-form {
            background-color: var(--text-on-dark);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: inherit;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .map-container {
            width: 100%;
            height: 100px;
            border-radius: 16px;
            overflow: hidden;
            /* CLAVE */
            box-shadow: var(--shadow);
            background: #eee;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Sección Consultorio */
        .clinic-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }

        .clinic-image {
            width: 100%;
            height: 300px;
            /* todas iguales */
            object-fit: cover;
            /* recorte limpio */
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            cursor: pointer;
            /* indica que es clickeable */
            transition: transform 0.3s ease;
        }

        .clinic-image:hover {
            transform: scale(1.03);
        }

        .lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 2000;
        }

        .lightbox.active {
            opacity: 1;
            pointer-events: auto;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            font-size: 2.5rem;
            color: #fff;
            cursor: pointer;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 50px;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
        }

        /* Footer */
        footer {
            background-color: var(--primary-dark);
            color: var(--text-on-dark);
            padding: 60px 0 30px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section {
            flex: 1;
            min-width: 250px;
        }

        .footer-section h3 {
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .doctor {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                background-color: var(--text-on-dark);
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                transition: var(--transition);
            }

            .nav-links.active {
                left: 0;
            }

            .nav-links li {
                margin: 15px 0;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero-buttons {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .hero-buttons .btn {
                width: 100%;
                max-width: 300px;
            }

            .section {
                padding: 60px 0;
            }
        }

        @media (max-width: 576px) {
            .hero {
                padding: 150px 0 80px;
            }

            .hero-info {
                align-items: center;
            }

            .info-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                width: 100%;
                max-width: 320px;
            }

            .stats {
                flex-direction: column;
                gap: 40px;
            }
        }

        /*Boton WhatsApp
.whatsapp-float {
  position: fixed;
  bottom: 25px;
  right: 25px;
  background: #25d366;
  color: #fff;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  box-shadow: 0 4px 12px rgba(0,0,0,.3);
  z-index: 3000;
}*/
    </style>
</head>

<body>
    <!-- Header y Navegación -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="https://i.postimg.cc/tCkfpLSY/Whats-App-Image-2026-03-09-at-5-10-37-PM.png"
                        class="logo-icon" height="30" width="30" />
                    <span class="logo-text">Dr. Lorenzo García</span>
                </div>

                <button class="mobile-menu-btn" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-links" id="nav-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#servicios">Procedimientos</a></li>
                    <li><a href="#doctor">Sobre Mí</a></li>
                    <li><a href="#consultorio">Más de Nosotros</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Sección Hero / Inicio -->
    <section class="hero hero-modern" id="inicio">
        <div class="container hero-grid">
            <div class="hero-content">
                <span class="hero-badge">
                    <i class="fas fa-heart"></i> Cirugía Cardiovascular Especializada
                </span>

                <h1>Pionero en Cirugía Cardiovascular del Nordeste</h1>

                <p>
                    Experiencia avanzada en procedimientos de corazón, cirugía
                    venosa con láser y tratamientos vasculares de última tecnología.
                    Top Ranking Cirujano Cardiovascular del Nordeste.
                    Más de 10,000 pacientes atendidos.
                </p>

                <div class="hero-buttons">
                    <a href="#contacto" class="btn">
                        <i class="far fa-calendar-alt"></i> Agendar Cita
                    </a>
                    <a href="#servicios" class="btn btn-secondary">
                        Ver Procedimientos
                    </a>
                </div>

                <div class="hero-info hero-info-left">
                    <div class="info-item">
                        <i class="fas fa-award info-icon"></i>
                        <div>
                            <strong>Experiencia</strong>
                            <p>Más de 10,000 pacientes</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-map-marker-alt info-icon"></i>
                        <div>
                            <strong>Ubicación</strong>
                            <p>San Francisco de Macorís</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-image">
                <img src="https://i.postimg.cc/VNsTbCzP/Whats-App-Image-2026-03-07-at-9-49-59-AM-(1).jpg"
                    alt="Cirujano Cardiovascular" />
                <div class="hero-card">
                    <img src="https://i.postimg.cc/tCkfpLSY/Whats-App-Image-2026-03-09-at-5-10-37-PM.png"
                        alt="Logo Dr. García" style="width: 44px; height: 44px; object-fit: contain;" />
                    <div>
                        <strong>Alta Especialización</strong>
                        <span>Cirugía Cardiovascular</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Servicios -->
    <section class="section services" id="servicios">
        <div class="container">
            <h2 class="section-title">Procedimientos Especializados</h2>

            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-heart service-icon"></i>
                    <h3>Cirugía de Corazón</h3>
                    <p>
                        Cirugía de las Válvulas Cardiacas.<br />
                        Cirugía Bypass Coronario.
                    </p>
                </div>

                <div class="service-card">
                    <i class="fas fa-wave-square service-icon"></i>
                    <h3>Cirugía Venosa con Láser</h3>
                    <p>
                        Manejo avanzado de patología venosa mediante técnicas mínimamente
                        invasivas.
                    </p>
                </div>

                <div class="service-card">
                    <i class="fas fa-procedures service-icon"></i>
                    <h3>Insuficiencia Renal</h3>
                    <p>
                        Creación de fístula arteriovenosa, colocación de catéter
                        permanente y manejo de acceso para hemodiálisis.
                    </p>
                </div>

                <div class="service-card">
                    <i class="fas fa-band-aid service-icon"></i>
                    <h3>Tratamiento de Várices</h3>
                    <p>
                        Procedimientos modernos y efectivos para la corrección de várices.
                    </p>
                </div>
                <div class="service-card">
                    <i class="fas fa-brain service-icon"></i>
                    <h3>Endarterectomía Carotídea</h3>
                    <p>Cirugía para eliminar la placa de las arterias carótidas y reducir el riesgo de accidente
                        cerebrovascular.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-heartbeat service-icon"></i>
                    <h3>Aneurisma de Aorta Abdominal</h3>
                    <p>Tratamiento quirúrgico y endovascular para la corrección de aneurismas de la aorta abdominal.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-walking service-icon"></i>
                    <h3>Angioplastia de Miembros Inferiores</h3>
                    <p>Procedimiento mínimamente invasivo para restablecer el flujo sanguíneo en arterias obstruidas de
                        las piernas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Dr. -->
    <section class="section" id="doctor">
        <div class="container">
            <div class="doctor">
                <div class="doctor-image">
                    <img src="https://i.postimg.cc/Fsk5p3t4/Whats-App-Image-2026-03-07-at-9-44-53-PM-(1).jpg"
                        alt="Dr. Lorenzo" />
                </div>

                <div class="doctor-info">
                    <h2>Dr. Lorenzo García</h2>

                    <p>
                        Pionero en la cirugía cardiovascular del nordeste. Primer
                        especialista en realizar procedimientos de corazón abierto en la
                        región.
                    </p>

                    <p>
                        Manejo avanzado de patología venosa con láser y procedimientos de
                        última tecnología. Especialista en pacientes con insuficiencia
                        renal, creación de fístula arteriovenosa, colocación de catéter
                        permanente.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Testimonios -->

    <!-- Sección Consultorio -->
    <section class="section" id="consultorio">
        <div class="container">
            <h2 class="section-title">Encargado de su Cuidado</h2>

            <div class="clinic-images">
                <img src="https://i.postimg.cc/ZKLbp667/Whats-App-Image-2026-03-07-at-9-49-59-AM.jpg"
                    alt="Consultorio 1" class="clinic-image" />
                <img src="https://i.postimg.cc/tCkfpLSY/Whats-App-Image-2026-03-09-at-5-10-37-PM.png"
                    alt="Consultorio 2" class="clinic-image" />
                <img src="https://i.postimg.cc/nrJxQLzy/DSC-6927.jpg" alt="Consultorio 3" class="clinic-image" />
            </div>

            <div style="display: flex; justify-content: center; margin-top: 20px;">
                <img src="https://i.postimg.cc/k4tMbZy4/Whats-App-Image-2026-03-07-at-9-44-53-PM.jpg"
                    alt="Consultorio 4" class="clinic-image" style="max-width: calc(33.33% - 14px);" />
            </div>
        </div>
    </section>

    <!-- Sección Contacto -->
    <section class="section" id="contacto">
        <div class="container">
            <h2 class="section-title">Contáctanos</h2>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 50px">
                Estamos aquí para atenderte. Ponte en contacto con nosotros para que agendes
                tu cita de forma rápida y sencilla.
            </p>

            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Información de Contacto</h3>

                    <div class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <div>
                            <h4>Teléfono</h4>
                            <p>+1 (809) 588-5601</p>
                        </div>
                    </div>

                    <!-- <div class="contact-item">
              <i class="fas fa-envelope contact-icon"></i>
              <div>
                <h4>Email</h4>
                <p>do@gmail.com</p>
              </div>
            </div-->

                    <div class="contact-item">
                        <i class="far fa-clock contact-icon"></i>
                        <div>
                            <h4>Horario</h4>
                            <p>
                                Lunes a Viernes: Previa cita a partir de las 9AM<br />
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <div>
                            <h4>Dirección</h4>
                            <p>C/. Duarte, Edificio Profesional Dr. Reynaldo Almanzar, 2Do Nive.l<br />San Francisco de
                                Macorís</p>
                        </div>
                    </div>
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d941.3922733279405!2d-70.25859704849792!3d19.301096363614818!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8eae2deeb606e03d%3A0xd3b901431e39fb92!2sEdificio%20Medico%20Dr.Almanzar%20(Siglo%2021)!5e0!3m2!1ses-419!2sdo!4v1773155131750!5m2!1ses-419!2sdo"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="contact-form">
                    <h3>Envíanos un Mensaje</h3>
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Nombre Completo</label>
                            <input type="text" id="name" placeholder="Tu nombre" required
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras" />
                        </div>

                        <div class="form-group">
                            <label for="message">Motivo de la consulta</label>
                            <textarea id="message" placeholder="Ej: Consulta general, dolor, chequeo..." required></textarea>
                        </div>

                        <button type="submit" class="btn" style="width: 100%">
                            <i class="fab fa-whatsapp" style="color: #25d366"></i> Agendar
                            por WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="logo" style="margin-bottom: 20px">
                        <i class="fas fa-heartbeat logo-icon"></i>
                        <span class="logo-text">Dr. Lorenzo García</span>
                    </div>
                    <p>
                        Especialista en Cirugía Cardiovascular. Innovación, precisión y
                        experiencia al servicio de tu salud.
                    </p>
                </div>

                <div class="footer-section">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#servicios">Procedimientos</a></li>
                        <li><a href="#doctora">Sobre Mí</a></li>
                        <li><a href="#consultorio">Más de Nosotros</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Servicios</h3>
                    <ul class="footer-links">
                        <li><a href="#servicios">Cirugía de Corazón Abierto</a></li>
                        <li><a href="#servicios">Cirugía Venosa con Láser</a></li>
                        <li><a href="#servicios">Insuficiencia Renal</a></li>
                        <li><a href="#servicios">Tratamiento de Várices</a></li>
                        <li><a href="#servicios">Termodiálisis</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Contacto</h3>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone"></i> 1 (809) 588-5601</li>
                        <!--li><i class="fas fa-envelope"></i> juanperez@gmail.com</li-->
                        <li>
                            <i class="fas fa-map-marker-alt"></i> C/. Duarte, Edificio Profesional
                            Dr. Reynaldo Almanzar, 2Do Nivel, San Francisco
                            de Macorís
                        </li>
                        <li><i class="far fa-clock"></i> Lun-Vie: 9:00-17:00</li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                <p>&copy; <span id="year"></span> Dr. Lorenzo García.</p>
            </div>
        </div>
    </footer>
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="" alt="Imagen ampliada" />
    </div>
    <script src="script.js"></script>
    <!--a
      href="https://wa.me/18297268194"
      class="whatsapp-float"
      target="_blank"
    >
      <i class="fab fa-whatsapp"></i>
    </a-->
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Menú móvil
        const mobileMenuBtn = document.getElementById("mobile-menu-btn");
        const navLinks = document.getElementById("nav-links");

        if (mobileMenuBtn && navLinks) {
            mobileMenuBtn.addEventListener("click", () => {
                navLinks.classList.toggle("active");
                mobileMenuBtn.innerHTML = navLinks.classList.contains("active") ?
                    '<i class="fas fa-times"></i>' :
                    '<i class="fas fa-bars"></i>';
            });
        }

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll(".nav-links a").forEach((link) => {
            link.addEventListener("click", () => {
                navLinks.classList.remove("active");
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        // Formulario
        const contactForm = document.getElementById("contactForm");

        if (contactForm) {
            contactForm.addEventListener("submit", (e) => {
                e.preventDefault();

                const name = document.getElementById("name").value.trim();
                const message = document.getElementById("message").value.trim();

                const whatsappNumber = "18297268194"; ///18095885601

                const text = `
                        *SOLICITUD DE CITA MÉDICA*

                        *Nombre:* ${name}

                        *Motivo de la consulta:*
                        ${message}

                        Quedo atento(a) a su confirmación.
                        Muchas gracias.
                        `.trim();

                const url = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(text)}`;

                window.open(url, "_blank");

                contactForm.reset();
            });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener("click", function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute("href"));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: "smooth",
                    });
                }
            });
        });

        /* ================= LIGHTBOX ================= */

        const images = document.querySelectorAll(".clinic-image");
        const lightbox = document.getElementById("lightbox");
        const lightboxImg = document.getElementById("lightbox-img");
        const closeBtn = document.querySelector(".lightbox-close");

        if (images.length && lightbox && lightboxImg && closeBtn) {
            images.forEach((img) => {
                img.addEventListener("click", () => {
                    lightboxImg.src = img.src;
                    lightbox.classList.add("active");
                    document.body.style.overflow = "hidden";
                });
            });

            closeBtn.addEventListener("click", () => {
                lightbox.classList.remove("active");
                document.body.style.overflow = "";
            });

            lightbox.addEventListener("click", (e) => {
                if (e.target === lightbox) {
                    lightbox.classList.remove("active");
                    document.body.style.overflow = "";
                }
            });
        }
    });

    /*Validación de campos del formulario */
    const phoneInput = document.getElementById("phone");

    if (phoneInput) {
        phoneInput.addEventListener("input", () => {
            phoneInput.value = phoneInput.value.replace(/\D/g, "");
        });
    }

    window.onbeforeunload = () => "¿Estás seguro de salir de la página?";

    document.getElementById("year").textContent = new Date().getFullYear();
</script>
