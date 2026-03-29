<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — S.G.A.F.A QR</title>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"></noscript>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; display: flex; min-height: 100vh; background: #ebebeb; }
        .panel-left { display: none; width: 58%; background: #ffffff; flex-direction: column; justify-content: space-between; padding: 2.5rem 4rem; position: relative; overflow: hidden; }
        @media(min-width:1024px) { .panel-left { display: flex; } }
        .panel-left::before { content: ''; position: absolute; inset: 0; background-image: linear-gradient(rgba(226, 176, 138, 0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(226, 176, 138, 0.06) 1px, transparent 1px); background-size: 48px 48px; pointer-events: none; }
        .blob { position: absolute; border-radius: 50%; pointer-events: none; }
        .blob-1 { top: -120px; right: -120px; width: 380px; height: 380px; background: radial-gradient(circle, rgba(226, 176, 138, 0.13) 0%, transparent 70%); }
        .blob-2 { bottom: -80px; left: -60px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(74, 134, 181, 0.09) 0%, transparent 70%); }
        .logo-area { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
        .logo-box { width: 42px; height: 42px; background: linear-gradient(135deg, #1e3a5f, #2d5a8e); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(30, 58, 95, .25); }
        .logo-box svg { color: #fff; }
        .logo-text { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 800; color: #1a2e4a; letter-spacing: -0.03em; }
        .left-content { max-width: 500px; position: relative; z-index: 1; }
        h1.headline { font-family: 'Sora', sans-serif; font-size: clamp(1.8rem, 2.8vw, 2.6rem); font-weight: 800; line-height: 1.1; letter-spacing: -0.04em; color: #0f1f35; margin-bottom: 1.1rem; }
        h1.headline .warm { color: #e2834a; }
        .sub { color: #6b7a8d; font-size: .98rem; line-height: 1.65; margin-bottom: 1.8rem; }
        .panel-right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .login-card { width: 100%; max-width: 480px; background: #fff; border-radius: 28px; padding: 2.5rem 3rem; box-shadow: 0 12px 32px rgba(0, 0, 0, .08); animation: slideUp .5s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .card-title { font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: #0f1f35; letter-spacing: -0.04em; margin-bottom: .35rem; }
        .card-sub { color: #9ca3af; font-size: .92rem; margin-bottom: 1.6rem; }
        .form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }
        .form-group { position: relative; margin-bottom: 1rem; flex: 1; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #b0b8c4; }
        .form-input { width: 100%; padding: 0.9rem 1.25rem 0.9rem 2.8rem; background: #f7f7f7; border: 1.5px solid #e8e8e8; border-radius: 12px; font-family: 'DM Sans', sans-serif; outline: none; transition: all .2s; }
        .form-input:focus { background: #fff; border-color: #4a86b5; box-shadow: 0 0 0 4px rgba(74, 134, 181, .1); }
        .btn-submit { width: 100%; padding: 1rem; background: linear-gradient(135deg, #e2834a, #d17135); color: #fff; font-family: 'Sora', sans-serif; font-weight: 700; border: none; border-radius: 12px; cursor: pointer; box-shadow: 0 4px 16px rgba(226, 131, 74, .3); transition: all .2s; margin-top: 1rem; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(226, 131, 74, .4); }
        .footer-link { text-align: center; margin-top: 1.4rem; font-size: .88rem; color: #6b7a8d; }
        .footer-link a { color: #4a86b5; font-weight: 600; text-decoration: none; }
        .footer-link a:hover { text-decoration: underline; }

        /* Strength Meter & Eye Toggle Styles */
        .input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
        .form-input-eye { padding-right: 3rem !important; }
        .eye-btn { position: absolute; right: 1rem; background: none; border: none; cursor: pointer; color: #b0b8c4; padding: 0.5rem; display: flex; align-items: center; transition: color 0.2s; z-index: 5; }
        .eye-btn:hover { color: #4a86b5; }
        
        .strength-meter { height: 5px; background: #e5e7eb; border-radius: 10px; margin-top: 8px; overflow: hidden; position: relative; width: 100%; }
        .strength-bar { height: 100%; width: 0; transition: all 0.4s ease; border-radius: 10px; }
        .strength-text { font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .weak { width: 33%; background: #ef4444; }
        .medium { width: 66%; background: #f59e0b; }
        .strong { width: 100%; background: #10b981; }

        .requirement-list { font-size: 0.7rem; color: #9ca3af; margin-top: 6px; list-style: none; padding: 0; display: flex; flex-wrap: wrap; gap: 10px; }
        .requirement-list li { display: flex; align-items: center; gap: 4px; transition: color 0.2s; }
        .requirement-list li.met { color: #10b981; }
        .requirement-list li svg { width: 10px; height: 10px; }
        
        .btn-submit:disabled { background: #d1d5db; box-shadow: none; cursor: not-allowed; transform: none; }
    </style>
</head>

<body>
    <div class="panel-left">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="logo-area">
            <div class="logo-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                    <path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4" />
                </svg>
            </div>
            <span class="logo-text">S.G.A.F.A QR</span>
        </div>
        <div class="left-content">
            <h1 class="headline">Únete al equipo de <span class="warm">Auditores</span></h1>
            <p class="sub">Regístrate para comenzar a gestionar el inventario y realizar auditorías precisas mediante QR.</p>
        </div>
        <p style="font-size: .7rem; color: #9ca3af; letter-spacing: .1em;">© 2026 S.G.A.F.A QR System</p>
    </div>

    <div class="panel-right">
        <div class="login-card">
            <h2 class="card-title">Crear cuenta</h2>
            <p class="card-sub">Registro exclusivo para personal de Auditoría</p>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" />
                            </svg>
                        </span>
                        <input type="text" name="nombre" class="form-input" placeholder="Nombre" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="apellidos" class="form-input" placeholder="Apellidos" value="{{ old('apellidos') }}" style="padding-left: 1.25rem;" required>
                    </div>
                </div>

                <div class="form-group">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input type="email" name="email" class="form-input" placeholder="Correo institucional" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <div class="input-wrapper">
                        <input type="password" id="reg_pass" name="password" class="form-input form-input-eye" placeholder="Contraseña" required>
                        <button type="button" class="eye-btn" onclick="togglePass('reg_pass', this)">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    
                    <div class="strength-meter">
                        <div id="strengthBar" class="strength-bar"></div>
                    </div>
                    <span id="strengthText" class="strength-text" style="color: #9ca3af;">Fuerza: ———</span>

                    <ul class="requirement-list">
                        <li id="req-len"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> 8+ letras</li>
                        <li id="req-up"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Mayúscula</li>
                        <li id="req-num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Un número</li>
                    </ul>
                </div>

                <div class="form-group">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <div class="input-wrapper">
                        <input type="password" id="reg_conf" name="password_confirmation" class="form-input form-input-eye" placeholder="Confirmar contraseña" required>
                        <button type="button" class="eye-btn" onclick="togglePass('reg_conf', this)">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                <div style="background:#fff0f0; border:1px solid #fca5a5; border-radius:10px; padding:0.75rem; margin-bottom:1rem;">
                    <p style="color:#dc2626; font-size:.82rem; font-weight:600;">{{ $errors->first() }}</p>
                </div>
                @endif

                <button type="submit" class="btn-submit" disabled>Crear cuenta</button>
            </form>

    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }

        const pInp = document.getElementById('reg_pass');
        const cInp = document.getElementById('reg_conf');
        const sBtn = document.querySelector('.btn-submit');
        const sBar = document.getElementById('strengthBar');
        const sTxt = document.getElementById('strengthText');

        const rL = document.getElementById('req-len');
        const rU = document.getElementById('req-up');
        const rN = document.getElementById('req-num');

        const validate = () => {
            const v = pInp.value;
            const matches = cInp.value === v && v !== '';
            
            let score = 0;
            const mLen = v.length >= 8;
            const mUp = /[A-Z]/.test(v);
            const mNum = /\d/.test(v);

            if(mLen){ score++; rL.classList.add('met'); } else { rL.classList.remove('met'); }
            if(mUp){ score++; rU.classList.add('met'); } else { rU.classList.remove('met'); }
            if(mNum){ score++; rN.classList.add('met'); } else { rN.classList.remove('met'); }

            sBar.className = 'strength-bar';
            if(score === 0){ sBar.style.width='0%'; sTxt.innerText='Fuerza: ———'; sTxt.style.color='#9ca3af'; }
            else if(score === 1){ sBar.classList.add('weak'); sTxt.innerText='Fuerza: Débil'; sTxt.style.color='#ef4444'; }
            else if(score === 2){ sBar.classList.add('medium'); sTxt.innerText='Fuerza: Media'; sTxt.style.color='#f59e0b'; }
            else if(score === 3){ sBar.classList.add('strong'); sTxt.innerText='Fuerza: Fuerte'; sTxt.style.color='#10b981'; }

            sBtn.disabled = !(score === 3 && matches);
        };

        pInp.addEventListener('input', validate);
        cInp.addEventListener('input', validate);
    </script>
</body>
</html>
