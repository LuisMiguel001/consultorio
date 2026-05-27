<!-- resources/views/landing.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
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

        body.modal-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
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

        /* Header */
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

        /* Hero */
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

        /* Sections */
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

        /* Features */
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

        /* Pricing */
        .pricing {
            background: #f5f9ff;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            align-items: stretch;
        }

        .price-card {
            background: white;
            border-radius: 28px;
            padding: 45px 35px;
            position: relative;
            transition: .35s ease;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
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
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .price span {
            font-size: 1rem;
            font-weight: 500;
        }

        .price-note {
            font-size: 0.85rem;
            margin-bottom: 25px;
            opacity: 0.8;
        }

        .features-list {
            list-style: none;
            margin-bottom: 35px;
            flex-grow: 1;
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
            width: 20px;
        }

        .price-card .btn {
            width: 100%;
        }

        .popular .btn {
            background: white;
            color: var(--primary);
        }

        /* Sección de Bancos Mejorada */
        .banks-section {
            margin-top: 80px;
            text-align: center;
            padding: 40px 30px;
            background: white;
            border-radius: 28px;
            border: 1px solid var(--border);
        }

        .banks-title {
            color: var(--primary-dark);
            margin-bottom: 15px;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .banks-subtitle {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
            font-size: 1rem;
        }

        .banks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        /* Tarjetas de Banco Mejoradas */
        .bank-card {
            background: white;
            border-radius: 24px;
            padding: 0;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            overflow: hidden;
            position: relative;
        }

        .bank-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .bank-header {
            padding: 25px 25px 20px;
            text-align: center;
            position: relative;
        }

        .bank-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .bank-name {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .bank-type {
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 500;
        }

        .bank-details {
            padding: 20px 25px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
        }

        .detail-item {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text);
            font-size: 0.85rem;
        }

        .detail-value {
            font-family: 'Monaco', 'Courier New', monospace;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.9rem;
            background: white;
            padding: 6px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value:hover {
            background: var(--primary-light);
            transform: scale(1.02);
        }

        .copy-icon {
            font-size: 0.8rem;
            opacity: 0.6;
        }

        .bank-footer {
            padding: 15px 25px 25px;
            text-align: center;
        }

        .tooltip-bank {
            position: relative;
            display: inline-block;
        }

        .tooltip-bank .tooltip-text {
            visibility: hidden;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.75rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip-bank:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Colores específicos por banco */
        .bank-card.banreservas .bank-header {
            background: linear-gradient(135deg, #003366, #002244);
            color: white;
        }
        .bank-card.banreservas .bank-logo {
            background: #ffd700;
            color: #003366;
        }

        .bank-card.bhd .bank-header {
            background: linear-gradient(135deg, #f05a28, #d44a1a);
            color: white;
        }
        .bank-card.bhd .bank-logo {
            background: white;
            color: #f05a28;
        }

        .bank-card.popular .bank-header {
            background: linear-gradient(135deg, #cc0000, #990000);
            color: white;
        }
        .bank-card.popular .bank-logo {
            background: white;
            color: #cc0000;
        }

        .exchange-note {
            background: #f0f7ff;
            padding: 18px;
            border-radius: 16px;
            max-width: 550px;
            margin: 30px auto 0 auto;
            border-left: 4px solid var(--primary);
        }

        /* Modal de Contacto Rediseñado */
        .modal-contacto {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: .3s ease;
            padding: 16px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .modal-contacto.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content-contacto {
            width: 100%;
            max-width: 550px;
            max-height: calc(100vh - 32px);
            background: white;
            border-radius: 28px;
            position: relative;
            animation: modalShow .3s ease;
            box-shadow: 0 25px 60px rgba(15, 23, 42, .3);
            overflow: hidden;
        }

        @keyframes modalShow {
            from {
                transform: translateY(20px) scale(.96);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 28px 28px 20px;
            position: relative;
        }

        .modal-header h2 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 800;
        }

        .modal-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .close-modal-contacto {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            color: white;
            transition: .25s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal-contacto:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 28px;
            max-height: calc(100vh - 250px);
            overflow-y: auto;
        }

        /* Stepper */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .step {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
            background: white;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            color: #64748b;
            transition: all 0.3s;
        }

        .step.active .step-number {
            background: var(--primary);
            color: white;
            box-shadow: 0 0 0 5px rgba(13, 71, 161, 0.2);
        }

        .step.completed .step-number {
            background: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-light);
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 700;
        }

        /* Plan Resumen Mejorado */
        .plan-resumen {
            background: linear-gradient(135deg, #f0f7ff, #e8f0fe);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(13, 71, 161, 0.2);
        }

        .plan-resumen h4 {
            color: var(--primary-dark);
            margin-bottom: 12px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .plan-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(13, 71, 161, 0.2);
        }

        .plan-detail-row:last-child {
            border-bottom: none;
        }

        .plan-detail-label {
            font-weight: 600;
            color: var(--text);
        }

        .plan-detail-value {
            font-weight: 700;
            color: var(--primary-dark);
        }

        .price-comparison {
            display: flex;
            gap: 15px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid rgba(13, 71, 161, 0.3);
            flex-wrap: wrap;
        }

        .price-comparison > div {
            background: white;
            padding: 8px;
            border-radius: 12px;
            text-align: center;
            flex: 1;
            min-width: 70px;
        }

        /* Formulario */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 0.85rem;
        }

        .form-group label i {
            margin-right: 6px;
            color: var(--primary);
        }

        .form-group input {
            width: 100%;
            height: 50px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            padding: 0 16px;
            font-size: 0.95rem;
            outline: none;
            transition: .25s;
            background: white;
        }

        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 71, 161, .10);
        }

        .btn-whatsapp {
            width: 100%;
            height: 54px;
            font-size: 1rem;
            background: #25D366;
            color: white;
            margin-top: 10px;
            border-radius: 14px;
            font-weight: 700;
        }

        .btn-whatsapp:hover {
            background: #128C7E;
            transform: translateY(-2px);
        }

        .terms-text {
            font-size: 0.7rem;
            color: var(--text-light);
            text-align: center;
            margin-top: 15px;
        }

        /* Modal Demo */
        .modal-demo {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: .3s ease;
            padding: 16px;
            overflow-y: auto;
        }

        .modal-demo.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content-demo {
            width: 100%;
            max-width: 450px;
            max-height: calc(100vh - 32px);
            background: white;
            border-radius: 28px;
            padding: 28px 24px 32px;
            position: relative;
            animation: modalShow .3s ease;
            box-shadow: 0 25px 60px rgba(15, 23, 42, .3);
            overflow-y: auto;
        }

        .close-modal {
            position: sticky;
            top: 0;
            float: right;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 12px;
            background: #f1f5f9;
            cursor: pointer;
            font-size: 0.9rem;
            color: #334155;
            transition: .25s;
            margin-bottom: 16px;
            margin-right: -8px;
        }

        .close-modal:hover {
            background: #e2e8f0;
        }

        .modal-content-demo h2 {
            font-size: 1.6rem;
            margin-bottom: 10px;
            color: var(--primary-dark);
            clear: both;
        }

        .modal-content-demo p {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .select-wrapper select {
            width: 100%;
            height: 52px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            padding: 0 14px;
            font-size: 0.95rem;
            outline: none;
            margin-bottom: 20px;
            background: white;
        }

        .btn-demo-submit {
            width: 100%;
            height: 52px;
            font-size: 1rem;
            border-radius: 14px;
        }

        /* Alertas */
        .alert-floating {
            position: fixed;
            top: 95px;
            right: 20px;
            z-index: 10000;
            min-width: 280px;
            max-width: 400px;
            padding: 14px 18px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: 600;
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
            animation: slideIn .35s ease;
        }

        .alert-floating.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .alert-floating.success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* CTA */
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

        /* Footer */
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

        /* Responsive */
        .menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }

        @media(max-width:992px) {
            .hero h1 { font-size: 3rem; }
            .section-header h2, .cta h2 { font-size: 2.4rem; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media(max-width:768px) {
            .menu-btn { display: block; }
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
            .nav-links.active { display: flex; }
            .hero { padding: 90px 0; }
            .hero h1 { font-size: 2.5rem; }
            .section-header h2, .cta h2 { font-size: 2rem; }
            .price-card.popular { transform: none; }
            .footer-grid { grid-template-columns: 1fr; }
            .banks-grid { grid-template-columns: 1fr; }
            .modal-header h2 { font-size: 1.4rem; }
        }

        @media(max-width:500px) {
            .hero h1 { font-size: 2rem; }
            .hero-buttons, .cta-buttons { flex-direction: column; }
            .btn, .btn-primary, .btn-outline { width: 100%; }
            .bank-card { margin: 0 10px; }
        }
    </style>
</head>

<body>
    <header>
        @if (session('error'))
            <div class="alert-floating error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="alert-floating success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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
                    <a href="{{ route('login') }}" class="btn btn-primary" style="color:white;">Comenzar</a>
                </div>

                <button class="menu-btn" id="menuBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>El Sistema de Gestión Médica que Transforma tu Práctica</h1>
                <p>Simplifica la administración de tu consultorio con DoctorClick. Agenda, expedientes digitales, recetas electrónicas y mucho más, todo en un solo lugar.</p>
                <div class="hero-buttons">
                    <button type="button" class="btn btn-light" onclick="abrirModalDemo()">
                        <i class="fa-solid fa-bolt"></i> Probar Demo Gratis
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-primary">Comenzar Ahora <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas">
        <div class="container">
            <div class="section-header">
                <h2>Todo lo que necesitas para gestionar tu consultorio</h2>
                <p>Herramientas profesionales diseñadas específicamente para médicos</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-calendar"></i></div>
                    <h3>Gestión de Citas</h3>
                    <p>Agenda y administra citas médicas de manera eficiente con recordatorios automáticos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-user-doctor"></i></div>
                    <h3>Expedientes Digitales</h3>
                    <p>Almacena y accede a historiales clínicos completos de forma segura y organizada.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-file-lines"></i></div>
                    <h3>Recetas Electrónicas</h3>
                    <p>Genera y envía recetas médicas digitales con firma electrónica válida.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-chart-column"></i></div>
                    <h3>Reportes y Estadísticas</h3>
                    <p>Visualiza métricas clave de tu consultorio con dashboards intuitivos y análisis avanzados.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-regular fa-clock"></i></div>
                    <h3>Gestión de Tiempo</h3>
                    <p>Optimiza tu agenda y reduce tiempos de espera mediante automatización inteligente.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-shield-heart"></i></div>
                    <h3>Seguridad HIPAA</h3>
                    <p>Protección de datos médicos con cifrado de nivel empresarial.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="precios">
        <div class="container">
            <div class="section-header">
                <h2>Planes que se adaptan a tus consultas</h2>
                <p>Elige el plan perfecto para ti. Cancela cuando quieras.</p>
            </div>
            <div class="pricing-grid">
                <!-- BÁSICO -->
                <div class="price-card">
                    <div class="plan-name">Básico</div>
                    <div class="price">$25 <span>/mes</span></div>
                    <div class="price-note">RD$ 1,500 /mes</div>
                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> 3,000 pacientes registrados</li>
                        <li><i class="fa-regular fa-circle-check"></i> 200 citas mensuales</li>
                        <li><i class="fa-regular fa-circle-check"></i> 1 doctor</li>
                        <li><i class="fa-regular fa-circle-check"></i> 1 secretaria</li>
                    </ul>
                    <button onclick="abrirModalContacto('Básico', '25', '1,500')" class="btn btn-primary">Seleccionar Plan</button>
                </div>

                <!-- ESTÁNDAR -->
                <div class="price-card popular">
                    <div class="badge">MÁS POPULAR</div>
                    <div class="plan-name">Estándar</div>
                    <div class="price">$40 <span>/mes</span></div>
                    <div class="price-note">RD$ 2,400 /mes</div>
                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> Pacientes ilimitados</li>
                        <li><i class="fa-regular fa-circle-check"></i> 500 citas mensuales</li>
                        <li><i class="fa-regular fa-circle-check"></i> 2 doctores</li>
                        <li><i class="fa-regular fa-circle-check"></i> 2 secretarias</li>
                        <li><i class="fa-regular fa-circle-check"></i> 2 enfermeras</li>
                        <li><i class="fa-regular fa-circle-check"></i> Recordatorios WhatsApp (500 créditos)</li>
                    </ul>
                    <button onclick="abrirModalContacto('Estándar', '40', '2,400')" class="btn">Seleccionar Plan</button>
                </div>

                <!-- PLUS -->
                <div class="price-card">
                    <div class="plan-name">Plus</div>
                    <div class="price">$130 <span>/mes</span></div>
                    <div class="price-note">RD$ 7,800 /mes</div>
                    <ul class="features-list">
                        <li><i class="fa-regular fa-circle-check"></i> Pacientes ilimitados</li>
                        <li><i class="fa-regular fa-circle-check"></i> 1,500 citas mensuales</li>
                        <li><i class="fa-regular fa-circle-check"></i> 4 doctores</li>
                        <li><i class="fa-regular fa-circle-check"></i> 4 secretarias</li>
                        <li><i class="fa-regular fa-circle-check"></i> 4 enfermeras / auxiliares</li>
                        <li><i class="fa-regular fa-circle-check"></i> Recordatorios WhatsApp (1,500 créditos)</li>
                    </ul>
                    <button onclick="abrirModalContacto('Plus', '130', '7,800')" class="btn btn-primary">Seleccionar Plan</button>
                </div>
            </div>

            <!-- Sección de Bancos Mejorada con Tarjetas -->
            <div class="banks-section">
                <h3 class="banks-title">
                    <i class="fa-solid fa-building-columns" style="margin-right: 12px;"></i>
                    Métodos de Pago Disponibles
                </h3>
                <p class="banks-subtitle">
                    Realiza tu pago mediante transferencia bancaria en cualquiera de nuestras entidades aliadas.<br>
                    <strong>¡Haz clic en los números de cuenta para copiarlos!</strong>
                </p>

                <div class="banks-grid">
                    <!-- BanReservas -->
                    <div class="bank-card banreservas">
                        <div class="bank-header">
                            <div class="bank-logo">
                                <i class="fa-solid fa-landmark"></i>
                            </div>
                            <div class="bank-name">BanReservas</div>
                            <div class="bank-type">Banco de Reservas de la República Dominicana</div>
                        </div>
                        <div class="bank-details">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-regular fa-credit-card"></i> N° Cuenta:</span>
                                <span class="detail-value tooltip-bank" onclick="copiarAlPortapapeles('001-123456-7', this)">
                                    <span>001-123456-7</span>
                                    <i class="fa-regular fa-copy copy-icon"></i>
                                    <span class="tooltip-text">¡Copiado!</span>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-chart-line"></i> Tipo:</span>
                                <span class="detail-value">Cuenta Corriente Empresarial</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-user"></i> Beneficiario:</span>
                                <span class="detail-value">DoctorClick SRL</span>
                            </div>
                        </div>
                        <div class="bank-footer">
                            <small><i class="fa-regular fa-clock"></i> Transferencias en línea disponibles 24/7</small>
                        </div>
                    </div>

                    <!-- Banco BHD -->
                    <div class="bank-card bhd">
                        <div class="bank-header">
                            <div class="bank-logo">
                                <i class="fa-solid fa-chart-simple"></i>
                            </div>
                            <div class="bank-name">Banco BHD</div>
                            <div class="bank-type">Banco BHD León</div>
                        </div>
                        <div class="bank-details">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-regular fa-credit-card"></i> N° Cuenta:</span>
                                <span class="detail-value tooltip-bank" onclick="copiarAlPortapapeles('789-012345-6', this)">
                                    <span>789-012345-6</span>
                                    <i class="fa-regular fa-copy copy-icon"></i>
                                    <span class="tooltip-text">¡Copiado!</span>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-chart-line"></i> Tipo:</span>
                                <span class="detail-value">Cuenta de Ahorros Premium</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-user"></i> Beneficiario:</span>
                                <span class="detail-value">DoctorClick SRL</span>
                            </div>
                        </div>
                        <div class="bank-footer">
                            <small><i class="fa-regular fa-clock"></i> Transferencias en línea disponibles 24/7</small>
                        </div>
                    </div>

                    <!-- Banco Popular -->
                    <div class="bank-card popular">
                        <div class="bank-header">
                            <div class="bank-logo">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <div class="bank-name">Banco Popular</div>
                            <div class="bank-type">Banco Popular Dominicano</div>
                        </div>
                        <div class="bank-details">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-regular fa-credit-card"></i> N° Cuenta:</span>
                                <span class="detail-value tooltip-bank" onclick="copiarAlPortapapeles('456-789012-3', this)">
                                    <span>456-789012-3</span>
                                    <i class="fa-regular fa-copy copy-icon"></i>
                                    <span class="tooltip-text">¡Copiado!</span>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-chart-line"></i> Tipo:</span>
                                <span class="detail-value">Cuenta Corriente</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fa-solid fa-user"></i> Beneficiario:</span>
                                <span class="detail-value">DoctorClick SRL</span>
                            </div>
                        </div>
                        <div class="bank-footer">
                            <small><i class="fa-regular fa-clock"></i> Transferencias en línea disponibles 24/7</small>
                        </div>
                    </div>
                </div>

                <div class="exchange-note">
                    <p style="margin: 0; font-size: 0.85rem;">
                        <i class="fa-regular fa-clock"></i> Los datos bancarios completos se enviarán a tu correo al finalizar la contratación.<br>
                        <strong>Tipo de cambio referencial: 1 USD = 60 DOP</strong> (sujeto a variación según el banco)
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="demo">
        <div class="container">
            <div class="cta-content">
                <h2>¿Listo para modernizar tu consultorio?</h2>
                <p>Únete a cientos de profesionales de la salud que ya confían en DoctorClick.</p>
                <div class="cta-buttons">
                    <button class="btn btn-light" onclick="abrirModalDemo()">Probar Demo Gratis</button>
                    <a href="{{ route('login') }}" class="btn btn-dark">Comenzar Ahora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="logo" style="color:white;">
                        <div class="logo-icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <span>DoctorClick</span>
                    </a>
                    <p>La solución completa para la gestión de tus consultas médicas.</p>
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
            <div class="footer-bottom">© {{ date('Y') }} DoctorClick. Todos los derechos reservados.</div>
        </div>
    </footer>

    <!-- Modal Demo -->
    <div class="modal-demo" id="modalDemo">
        <div class="modal-content-demo">
            <button class="close-modal" onclick="cerrarModalDemo()"><i class="fa-solid fa-xmark"></i></button>
            <h2>Selecciona tu especialidad</h2>
            <p>Configuraremos el demo automáticamente según tu área médica.</p>
            <form action="{{ route('demo.crear') }}" method="POST">
                @csrf
                <div class="select-wrapper">
                    <select name="especialidad_id" required>
                        <option value="">Selecciona una especialidad</option>
                        @foreach ($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-demo-submit"><i class="fa-solid fa-stethoscope"></i> Entrar al Demo</button>
            </form>
        </div>
    </div>

    <!-- Modal Contacto Rediseñado -->
    <div class="modal-contacto" id="modalContacto">
        <div class="modal-content-contacto">
            <div class="modal-header">
                <button class="close-modal-contacto" onclick="cerrarModalContacto()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <h2>Solicitar Activación</h2>
                <p>Completa tus datos para activar tu plan</p>
            </div>
            <div class="modal-body">
                <!-- Stepper -->
                <div class="stepper">
                    <div class="step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Plan</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Datos</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Enviar</div>
                    </div>
                </div>

                <!-- Resumen del Plan Mejorado -->
                <div class="plan-resumen" id="planResumen"></div>

                <form id="formContacto" onsubmit="return false;">
                    <div class="form-group">
                        <label><i class="fa-regular fa-user"></i> Nombre Completo</label>
                        <input type="text" id="nombre" placeholder="Ej: Juan Pérez Rodríguez" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fa-regular fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" id="email" placeholder="Ej: juan@consultorio.com" required>
                    </div>
                    <button type="button" onclick="enviarWhatsApp()" class="btn btn-whatsapp">
                        <i class="fa-brands fa-whatsapp"></i> Enviar Solicitud por WhatsApp
                    </button>
                    <div class="terms-text">
                        Al enviar aceptas nuestros <a href="#" style="color: var(--primary);">Términos y Condiciones</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Menu móvil
        const menuBtn = document.getElementById('menuBtn');
        const navLinks = document.getElementById('navLinks');
        menuBtn.addEventListener('click', () => navLinks.classList.toggle('active'));

        // Función para copiar al portapapeles
        function copiarAlPortapapeles(texto, elemento) {
            navigator.clipboard.writeText(texto).then(() => {
                const tooltip = elemento.querySelector('.tooltip-text');
                if (tooltip) {
                    const textoOriginal = tooltip.textContent;
                    tooltip.textContent = '✓ Copiado';
                    setTimeout(() => {
                        tooltip.textContent = textoOriginal;
                    }, 1500);
                }
            });
        }

        // Modales
        function abrirModalDemo() {
            document.getElementById('modalDemo').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function cerrarModalDemo() {
            document.getElementById('modalDemo').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        let planSeleccionado = { nombre: '', precioUSD: '', precioRD: '' };

        function abrirModalContacto(planNombre, precioUSD, precioRD) {
            planSeleccionado = { nombre: planNombre, precioUSD: precioUSD, precioRD: precioRD };

            const montoDOP = parseInt(precioRD.replace(/,/g, ''));
            const mensualidades = [
                { plazo: '3 meses', descuento: '3%', total: Math.round(montoDOP * 3 * 0.97) },
                { plazo: '6 meses', descuento: '5%', total: Math.round(montoDOP * 6 * 0.95) },
                { plazo: '12 meses', descuento: '10%', total: Math.round(montoDOP * 12 * 0.90) }
            ];

            document.getElementById('planResumen').innerHTML = `
                <h4><i class="fa-solid fa-file-invoice"></i> Resumen de tu selección</h4>
                <div class="plan-detail-row">
                    <span class="plan-detail-label">Plan seleccionado:</span>
                    <span class="plan-detail-value">${planNombre}</span>
                </div>
                <div class="plan-detail-row">
                    <span class="plan-detail-label">Precio mensual:</span>
                    <span class="plan-detail-value">$${precioUSD} USD (RD$${precioRD})</span>
                </div>
                <div class="price-comparison">
                    <div><small>💳 Pago mensual</small><br><strong>RD$${precioRD}</strong></div>
                    <div><small>🎯 3 meses (-3%)</small><br><strong>RD$${mensualidades[0].total.toLocaleString()}</strong></div>
                    <div><small>🏆 6 meses (-5%)</small><br><strong>RD$${mensualidades[1].total.toLocaleString()}</strong></div>
                    <div><small>⭐ 12 meses (-10%)</small><br><strong>RD$${mensualidades[2].total.toLocaleString()}</strong></div>
                </div>
                <div class="plan-detail-row" style="margin-top:12px; border-top:1px dashed rgba(13,71,161,0.2); padding-top:12px;">
                    <span class="plan-detail-label">Método de pago:</span>
                    <span class="plan-detail-value">Transferencia Bancaria</span>
                </div>
            `;

            document.getElementById('modalContacto').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function cerrarModalContacto() {
            document.getElementById('modalContacto').classList.remove('active');
            document.body.classList.remove('modal-open');
            document.getElementById('nombre').value = '';
            document.getElementById('telefono').value = '';
            document.getElementById('email').value = '';
        }

        window.addEventListener('click', function(e) {
            const modalDemo = document.getElementById('modalDemo');
            if (e.target === modalDemo) cerrarModalDemo();
            const modalContacto = document.getElementById('modalContacto');
            if (e.target === modalContacto) cerrarModalContacto();
        });

        function enviarWhatsApp() {
            const nombre = document.getElementById('nombre').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            const email = document.getElementById('email').value.trim();

            if (!nombre || !telefono || !email) {
                alert('⚠️ Por favor, completa todos los campos');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('⚠️ Por favor, ingresa un correo electrónico válido');
                return;
            }

            const mensaje = `🏥 *NUEVA SOLICITUD - DoctorClick* 🏥%0A%0A` +
                `👤 *DATOS DEL CLIENTE*%0A` +
                `• Nombre: ${nombre}%0A` +
                `• Correo: ${email}%0A%0A` +
                `📋 *PLAN SELECCIONADO*%0A` +
                `• Plan: ${planSeleccionado.nombre}%0A` +
                `• Precio: $${planSeleccionado.precioUSD} USD (RD$${planSeleccionado.precioRD}/mes)%0A` +
                `• Método: Transferencia Bancaria%0A%0A` +
                `🏦 *INSTRUCCIONES DE PAGO*%0A` +
                `Banco: BanReservas / BHD / Popular%0A` +
                `Beneficiario: DoctorClick SRL%0A` +
                `✅ Monto a pagar: RD$${planSeleccionado.precioRD} (mensual)%0A%0A` +
                `*Tipo de Cambio:* 1 USD = 60 DOP%0A%0A` +
                `📌 *ESTADO:* Pendiente de pago - Enviar comprobante para activación`;

            const numeroWhatsApp = '18297268194';
            const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${mensaje}`;
            window.open(urlWhatsApp, '_blank');
            cerrarModalContacto();
            alert('✅ Solicitud preparada. Serás redirigido a WhatsApp para completar el proceso.');
        }
    </script>
</body>

</html>
