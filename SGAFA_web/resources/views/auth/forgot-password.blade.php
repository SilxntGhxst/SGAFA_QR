<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — S.G.A.F.A QR</title>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"></noscript>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
        @media(min-width:1024px){ .panel-left { display: flex; } }

        .panel-left::before {
            content:'';
            position:absolute; inset:0;
            background-image:
                linear-gradient(rgba(226,176,138,0.06) 1px, transparent 1px),
                linear-gradient(90deg,rgba(226,176,138,0.06) 1px,transparent 1px);
            background-size:48px 48px;
            pointer-events:none;
        }

        .blob { position:absolute; border-radius:50%; pointer-events:none; }
        .blob-1 { top:-120px; right:-120px; width:380px; height:380px;
            background:radial-gradient(circle,rgba(226,176,138,0.13) 0%,transparent 70%); }
        .blob-2 { bottom:-80px; left:-60px; width:300px; height:300px;
            background:radial-gradient(circle,rgba(74,134,181,0.09) 0%,transparent 70%); }

        .logo-area {
            display:flex; align-items:center; gap:10px;
            position:relative; z-index:1;
        }
        .logo-box {
            width:42px; height:42px;
            background:linear-gradient(135deg,#1e3a5f,#2d5a8e);
            border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 4px 14px rgba(30,58,95,.25);
            overflow:hidden;
        }
        .logo-box svg { color:#fff; }
        .logo-text {
            font-family:'Sora',sans-serif;
            font-size:1.4rem; font-weight:800;
            color:#1a2e4a; letter-spacing:-0.03em;
        }

        .left-content { max-width:500px; position:relative; z-index:1; }

        .illus-placeholder {
            width:370px; height:270px;
            background:linear-gradient(135deg,#dbeafe,#e0f2fe);
            border-radius:24px;
            display:flex; align-items:center; justify-content:center;
            animation:float 6s ease-in-out infinite;
            margin-bottom:2.5rem;
        }
        .illus-placeholder svg { width:120px; height:120px; color:#4a86b5; opacity:.5; }

        @keyframes float {
            0%,100%{ transform:translateY(0); }
            50%{ transform:translateY(-10px); }
        }

        h1.headline {
            font-family:'Sora',sans-serif;
            font-size:clamp(1.8rem,2.8vw,2.6rem);
            font-weight:800; line-height:1.1;
            letter-spacing:-0.04em;
            color:#0f1f35; margin-bottom:1.1rem;
        }
        h1.headline .warm { color:#e2834a; }
        h1.headline .cool { color:#3d7ab5; }

        .sub {
            color:#6b7a8d; font-size:.98rem;
            line-height:1.65; margin-bottom:1.8rem; max-width:410px;
        }

        .pills { display:flex; gap:.75rem; flex-wrap:wrap; }
        .pill {
            display:flex; align-items:center; gap:.5rem;
            padding:.45rem 1.1rem;
            border:1.5px solid #e2e8f0; border-radius:100px;
            background:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.05);
            transition:all .2s;
            cursor:default;
        }
        .pill:hover { border-color:#e2834a; transform:translateY(-1px); box-shadow:0 4px 16px rgba(226,131,74,.12); }
        .pill svg { color:#e2834a; }
        .pill span { font-size:.7rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.08em; }

        .left-footer { font-size:.7rem; font-weight:500; color:#9ca3af; text-transform:uppercase; letter-spacing:.1em; position:relative; z-index:1; }

        /* ─── RIGHT ─── */
        .panel-right {
            flex:1;
            display:flex; align-items:center; justify-content:center;
            padding:2rem;
        }

        .login-card {
            width:100%; max-width:420px;
            background:#fff;
            border-radius:28px;
            padding:3rem 3.25rem;
            box-shadow:0 4px 6px rgba(0,0,0,.04),0 12px 32px rgba(0,0,0,.08),0 40px 80px rgba(0,0,0,.06);
            animation:slideUp .5s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes slideUp {
            from{ opacity:0; transform:translateY(24px); }
            to  { opacity:1; transform:translateY(0); }
        }

        /* Icono superior */
        .card-icon {
            width:56px; height:56px;
            background:linear-gradient(135deg,#dbeafe,#bfdbfe);
            border-radius:16px;
            display:flex; align-items:center; justify-content:center;
            margin-bottom:1.5rem;
            box-shadow:0 4px 14px rgba(74,134,181,.15);
        }
        .card-icon svg { color:#3d7ab5; }

        .card-title {
            font-family:'Sora',sans-serif;
            font-size:2rem; font-weight:800;
            color:#0f1f35; letter-spacing:-0.04em;
            margin-bottom:.35rem;
        }
        .card-sub { color:#9ca3af; font-size:.95rem; margin-bottom:2rem; line-height:1.55; }

        .form-group { position:relative; margin-bottom:1rem; }
        .input-icon {
            position:absolute; left:1rem; top:50%;
            transform:translateY(-50%);
            color:#b0b8c4; pointer-events:none; transition:color .2s;
        }
        .form-group:focus-within .input-icon { color:#4a86b5; }

        .form-label {
            display:block;
            font-size:.8rem; font-weight:600;
            color:#374151; letter-spacing:.03em;
            margin-bottom:.5rem;
            text-transform:uppercase;
        }

        .form-input {
            width:100%;
            padding:1.05rem 1.25rem 1.05rem 3.1rem;
            background:#f7f7f7;
            border:1.5px solid #e8e8e8;
            border-radius:14px;
            font-family:'DM Sans',sans-serif;
            font-size:.95rem; color:#374151;
            outline:none; transition:all .2s;
            appearance:none;
            -webkit-appearance:none;
        }
        .form-input::placeholder { color:#b0b8c4; }
        .form-input:focus { background:#fff; border-color:#4a86b5; box-shadow:0 0 0 4px rgba(74,134,181,.1); }
        .form-input.error { border-color:#ef4444; box-shadow:0 0 0 4px rgba(239,68,68,.08); }

        .error-msg {
            display:none;
            font-size:.78rem; color:#ef4444;
            margin-top:.4rem; padding-left:.25rem;
            align-items:center; gap:.3rem;
        }
        .error-msg.show { display:flex; }

        /* Hint de dominio */
        .domain-hint {
            font-size:.75rem; color:#9ca3af;
            margin-top:.45rem; padding-left:.25rem;
            display:flex; align-items:center; gap:.3rem;
        }
        .domain-hint svg { color:#b0b8c4; flex-shrink:0; }

        .btn-submit {
            width:100%; padding:1.1rem;
            background:linear-gradient(135deg,#4a86b5,#3a6e9f);
            color:#fff;
            font-family:'Sora',sans-serif; font-size:1rem; font-weight:700;
            border:none; border-radius:14px; cursor:pointer;
            box-shadow:0 4px 20px rgba(74,134,181,.35);
            transition:all .25s; margin-top:.75rem;
            position:relative; overflow:hidden;
            display:flex; align-items:center; justify-content:center; gap:.5rem;
        }
        .btn-submit::after {
            content:''; position:absolute;
            top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);
            transition:left .4s;
        }
        .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(74,134,181,.45); }
        .btn-submit:hover::after { left:100%; }
        .btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }

        /* Estado de éxito */
        .success-state {
            display:none;
            flex-direction:column; align-items:center;
            text-align:center; padding:.5rem 0;
            animation:slideUp .4s cubic-bezier(.22,1,.36,1) both;
        }
        .success-state.show { display:flex; }
        .form-state { display:block; }
        .form-state.hide { display:none; }

        .success-icon {
            width:64px; height:64px;
            background:linear-gradient(135deg,#d1fae5,#a7f3d0);
            border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            margin-bottom:1.25rem;
            box-shadow:0 4px 20px rgba(16,185,129,.2);
        }
        .success-icon svg { color:#059669; }
        .success-title {
            font-family:'Sora',sans-serif;
            font-size:1.4rem; font-weight:800;
            color:#0f1f35; margin-bottom:.5rem;
        }
        .success-sub { color:#6b7a8d; font-size:.9rem; line-height:1.6; margin-bottom:1.75rem; }
        .success-email {
            font-weight:700; color:#3d7ab5;
            background:#eff6ff; padding:.15rem .5rem;
            border-radius:6px;
        }

        .back-link {
            display:inline-flex; align-items:center; gap:.4rem;
            font-size:.88rem; font-weight:600; color:#6b7a8d;
            text-decoration:none; transition:all .2s;
            margin-top:1.4rem;
        }
        .back-link:hover { color:#4a86b5; gap:.6rem; }

        .divider { height:1px; background:linear-gradient(90deg,transparent,#e8e8e8,transparent); margin:1.6rem 0; }

        .secure {
            display:flex; align-items:center; justify-content:center; gap:.4rem;
            font-size:.72rem; color:#c0c8d4;
        }

        /* Spinner */
        .spinner {
            display:none;
            width:18px; height:18px;
            border:2.5px solid rgba(255,255,255,.3);
            border-top-color:#fff;
            border-radius:50%;
            animation:spin .7s linear infinite;
        }
        .spinner.show { display:block; }
        @keyframes spin { to { transform:rotate(360deg); } }
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
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/>
            </svg>
        </div>
        <span class="logo-text">S.G.A.F.A QR</span>
    </div>

    <div class="left-content">
        <div class="illus-placeholder">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="headline">
            Recupera el acceso a<br>
            tu cuenta de <span class="warm">forma</span><br>
            <span class="cool">segura</span>
        </h1>
        <p class="sub">Ingresa tu correo institucional y te enviaremos un enlace para restablecer tu contraseña en minutos.</p>

        <div class="pills">
            <div class="pill">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Enlace Seguro</span>
            </div>
            <div class="pill">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Expira en 60 min</span>
            </div>
            <div class="pill">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Solo Institucional</span>
            </div>
        </div>
    </div>

    <p class="left-footer">© 2026 S.G.A.F.A QR System. All rights reserved.</p>
</div>

{{-- RIGHT --}}
<div class="panel-right">
    <div class="login-card">

        {{-- Estado inicial: formulario --}}
        <div class="form-state" id="formState">
            <div class="card-icon">
                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>

            <h2 class="card-title">¿Olvidaste tu contraseña?</h2>
            <p class="card-sub">Ingresa tu correo institucional y te enviaremos un enlace de recuperación.</p>

            <form id="recoverForm" onsubmit="handleSubmit(event)">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Correo institucional</label>
                    <span class="input-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        placeholder="usuario@institucion.edu.mx"
                        autocomplete="email"
                        oninput="clearError()"
                    >
                    <div class="domain-hint">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Solo se aceptan correos institucionales
                    </div>
                    <div class="error-msg" id="errorMsg">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="errorText">Ingresa un correo institucional válido.</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <div class="spinner" id="spinner"></div>
                    <span id="btnText">Enviar enlace de recuperación</span>
                </button>
            </form>

            <div style="text-align:center; margin-top:1.4rem;">
                <a href="/login" class="back-link">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </div>

        {{-- Estado de éxito --}}
        <div class="success-state" id="successState">
            <div class="success-icon">
                <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="success-title">¡Correo enviado!</p>
            <p class="success-sub">
                Enviamos un enlace de recuperación a<br>
                <span class="success-email" id="sentEmail"></span><br><br>
                Revisa tu bandeja de entrada. El enlace expira en <strong>60 minutos</strong>.
            </p>

            <div class="divider" style="width:100%;"></div>

            <a href="/login" class="back-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al inicio de sesión
            </a>
        </div>

        <div class="divider"></div>
        <div class="secure">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Conexión segura y encriptada
        </div>

    </div>
</div>

<script>
    // Cambia este dominio por el de tu institución
    const INSTITUTIONAL_DOMAIN = 'institucion.edu.mx';

    function isInstitutionalEmail(email) {
        // Acepta cualquier email válido con dominio que termine en .edu.mx
        // Ajusta la regex según el dominio real de tu institución
        return /^[^\s@]+@[^\s@]+\.(edu\.mx|edu|gob\.mx)$/i.test(email);
    }

    function showError(msg) {
        const input = document.getElementById('email');
        const errorMsg = document.getElementById('errorMsg');
        const errorText = document.getElementById('errorText');
        input.classList.add('error');
        errorText.textContent = msg;
        errorMsg.classList.add('show');
    }

    function clearError() {
        const input = document.getElementById('email');
        const errorMsg = document.getElementById('errorMsg');
        input.classList.remove('error');
        errorMsg.classList.remove('show');
    }

    function handleSubmit(e) {
        e.preventDefault();
        const email = document.getElementById('email').value.trim();

        if (!email) {
            showError('Por favor ingresa tu correo institucional.');
            return;
        }

        if (!isInstitutionalEmail(email)) {
            showError('Solo se aceptan correos institucionales (.edu.mx, .edu, .gob.mx).');
            return;
        }

        // Mostrar spinner
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        btn.disabled = true;
        spinner.classList.add('show');
        btnText.textContent = 'Enviando...';

        // Simular envío (aquí va el submit real al backend)
        setTimeout(() => {
            document.getElementById('sentEmail').textContent = email;
            document.getElementById('formState').classList.add('hide');
            document.getElementById('successState').classList.add('show');
        }, 1500);

        // Para submit real con Laravel, descomenta:
        // e.target.submit();
    }
</script>
</body>
</html>
