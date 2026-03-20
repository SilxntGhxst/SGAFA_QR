<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — S.G.A.F.A QR</title>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"></noscript>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            display: flex;
            min-height: 100vh;
            background: #ebebeb;
        }

        /* ─── LEFT ─── */
        .panel-left {
            display: none;
            width: 58%;
            background: #ffffff;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 4rem;
            position: relative;
            overflow: hidden;
        }

        @media(min-width:1024px) {
            .panel-left {
                display: flex;
            }
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(226, 176, 138, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(226, 176, 138, 0.06) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .blob-1 {
            top: -120px;
            right: -120px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(226, 176, 138, 0.13) 0%, transparent 70%);
        }

        .blob-2 {
            bottom: -80px;
            left: -60px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(74, 134, 181, 0.09) 0%, transparent 70%);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .logo-box {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1e3a5f, #2d5a8e);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(30, 58, 95, .25);
            overflow: hidden;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo-box svg {
            color: #fff;
        }

        .logo-text {
            font-family: 'Sora', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a2e4a;
            letter-spacing: -0.03em;
        }

        .left-content {
            max-width: 500px;
            position: relative;
            z-index: 1;
        }

        .illustration {
            margin-bottom: 2.5rem;
        }

        .illustration img {
            width: 100%;
            max-width: 370px;
            filter: drop-shadow(0 20px 40px rgba(30, 58, 95, .15));
            animation: float 6s ease-in-out infinite;
        }

        .illus-placeholder {
            width: 370px;
            height: 270px;
            background: linear-gradient(135deg, #dbeafe, #e0f2fe);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 6s ease-in-out infinite;
        }

        .illus-placeholder svg {
            width: 120px;
            height: 120px;
            color: #4a86b5;
            opacity: .5;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        h1.headline {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.8rem, 2.8vw, 2.6rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.04em;
            color: #0f1f35;
            margin-bottom: 1.1rem;
        }

        h1.headline .warm {
            color: #e2834a;
        }

        h1.headline .cool {
            color: #3d7ab5;
        }

        .sub {
            color: #6b7a8d;
            font-size: .98rem;
            line-height: 1.65;
            margin-bottom: 1.8rem;
            max-width: 410px;
        }

        .pills {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem 1.1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 100px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            transition: all .2s;
            cursor: default;
        }

        .pill:hover {
            border-color: #e2834a;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(226, 131, 74, .12);
        }

        .pill svg {
            color: #e2834a;
        }

        .pill span {
            font-size: .7rem;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .left-footer {
            font-size: .7rem;
            font-weight: 500;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .1em;
            position: relative;
            z-index: 1;
        }

        /* ─── RIGHT ─── */
        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 28px;
            padding: 3rem 3.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .04), 0 12px 32px rgba(0, 0, 0, .08), 0 40px 80px rgba(0, 0, 0, .06);
            animation: slideUp .5s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-title {
            font-family: 'Sora', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #0f1f35;
            letter-spacing: -0.04em;
            margin-bottom: .35rem;
        }

        .card-sub {
            color: #9ca3af;
            font-size: .95rem;
            margin-bottom: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b0b8c4;
            pointer-events: none;
            transition: color .2s;
        }

        .form-group:focus-within .input-icon {
            color: #4a86b5;
        }

        .form-input {
            width: 100%;
            padding: 1.05rem 1.25rem 1.05rem 3.1rem;
            background: #f7f7f7;
            border: 1.5px solid #e8e8e8;
            border-radius: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            color: #374151;
            outline: none;
            transition: all .2s;
            appearance: none;
            -webkit-appearance: none;
        }

        .form-input::placeholder {
            color: #b0b8c4;
        }

        .form-input:focus {
            background: #fff;
            border-color: #4a86b5;
            box-shadow: 0 0 0 4px rgba(74, 134, 181, .1);
        }

        .eye-btn {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #b0b8c4;
            display: flex;
            align-items: center;
            transition: color .2s;
        }

        .eye-btn:hover {
            color: #4a86b5;
        }

        .btn-submit {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #4a86b5, #3a6e9f);
            color: #fff;
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(74, 134, 181, .35);
            transition: all .25s;
            margin-top: .5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .12), transparent);
            transition: left .4s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(74, 134, 181, .45);
        }

        .btn-submit:hover::after {
            left: 100%;
        }

        .forgot {
            text-align: center;
            margin-top: 1.4rem;
        }

        .forgot a {
            font-size: .85rem;
            font-weight: 500;
            color: #9ca3af;
            text-decoration: none;
            border-bottom: 1px dashed #d1d5db;
            padding-bottom: 1px;
            transition: all .2s;
        }

        .forgot a:hover {
            color: #4a86b5;
            border-color: #4a86b5;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e8e8e8, transparent);
            margin: 1.6rem 0;
        }

        .secure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            font-size: .72rem;
            color: #c0c8d4;
        }
    </style>
</head>

<body>

    {{-- LEFT --}}
    <div class="panel-left">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>

        <div class="logo-area">
            <div class="logo-box">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                    <path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4" />
                </svg>
            </div>
            <span class="logo-text">S.G.A.F.A QR</span>
        </div>

        <div class="left-content">
            <div class="illustration">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Central SGAFA" style="width: 350px; height: 350px; margin: 0 auto; display: block;">
            </div>

            <h1 class="headline">
                Sistema de Gestión de<br>
                <span class="warm">Activos Fijos</span> y<br>
                Auditoria con QR
            </h1>
            <p class="sub">Optimiza el control y seguimiento de activos de manera fácil y rápida escaneando códigos QR.</p>

            <div class="pills">
                <div class="pill">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Inventario Real</span>
                </div>
                <div class="pill">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span>Escaneo Rápido</span>
                </div>
                <div class="pill">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Reportes</span>
                </div>
            </div>
        </div>

        <p class="left-footer", style="margin-top: 20px;">© 2026 S.G.A.F.A QR System. All rights reserved.</p>
    </div>

    {{-- RIGHT --}}
    <div class="panel-right">
        <div class="login-card">
            <h2 class="card-title">Iniciar sesión</h2>
            <p class="card-sub">Bienvenido de nuevo al panel de control</p>

            <form action="/login" method="POST">
                <div class="form-group">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" class="form-input" placeholder="Correo electrónico" autocomplete="email">
                </div>

                <div class="form-group">
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input type="password" id="pwd" name="password" class="form-input" placeholder="Contraseña" style="padding-right:3rem;" autocomplete="current-password">
                    <button type="button" class="eye-btn" onclick="togglePwd()">
                        <svg id="eye-ico" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                {{-- Solo para navegación sin auth --}}
                <button type="button" class="btn-submit" onclick="window.location='/dashboard'">
                    Iniciar Sesión
                </button>

                <div class="forgot">
                    <a href="/forgot-password">¿Olvidaste tu contraseña?</a>
                </div>
            </form>

            <div class="divider"></div>
            <div class="secure">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Conexión segura y encriptada
            </div>
        </div>
    </div>

    <script>
        function togglePwd() {
            const i = document.getElementById('pwd');
            const ico = document.getElementById('eye-ico');
            if (i.type === 'password') {
                i.type = 'text';
                ico.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
            } else {
                i.type = 'password';
                ico.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>

</html>