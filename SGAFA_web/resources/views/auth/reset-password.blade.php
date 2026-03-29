<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña — S.G.A.F.A QR</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #ebebeb; margin: 0; }
        .card { width: 100%; max-width: 440px; background: #fff; border-radius: 28px; padding: 2.5rem; box-shadow: 0 40px 80px rgba(0,0,0,.06); text-align: center; }
        .title { font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: #0f1f35; margin-bottom: 0.5rem; }
        .sub { color: #6b7a8d; font-size: 0.95rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.2rem; text-align: left; position: relative; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 700; color: #4b5563; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .form-input { width: 100%; padding: 1.1rem; padding-right: 3.5rem; background: #f7f7f7; border: 1.5px solid #e8e8e8; border-radius: 14px; font-size: 0.95rem; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #4a86b5; background: #fff; box-shadow: 0 0 0 4px rgba(74,134,181,0.1); }
        .btn-submit { width: 100%; padding: 1.1rem; background: linear-gradient(135deg, #4a86b5, #3a6e9f); color: #fff; font-weight: 700; border: none; border-radius: 14px; cursor: pointer; box-shadow: 0 4px 20px rgba(74,134,181,.35); margin-top: 1rem; transition: all 0.2s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(74,134,181,.45); }
        .btn-submit:disabled { background: #9ca3af; cursor: not-allowed; transform: none; box-shadow: none; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; text-align: left; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
        .code-input { letter-spacing: 4px; text-align: center; font-weight: bold; font-size: 1.2rem; }
        
        .eye-btn { position: absolute; right: 1rem; background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0.5rem; display: flex; align-items: center; transition: color 0.2s; }
        .eye-btn:hover { color: #4a86b5; }
        
        /* Strength Meter */
        .strength-meter { height: 6px; background: #e5e7eb; border-radius: 10px; margin-top: 8px; overflow: hidden; position: relative; }
        .strength-bar { height: 100%; width: 0; transition: all 0.4s ease; border-radius: 10px; }
        .strength-text { font-size: 0.75rem; font-weight: 600; margin-top: 5px; display: block; transition: all 0.2s; }
        
        .weak { width: 33%; background: #ef4444; }
        .medium { width: 66%; background: #f59e0b; }
        .strong { width: 100%; background: #10b981; }

        .requirement-list { font-size: 0.75rem; color: #6b7280; margin-top: 8px; list-style: none; padding: 0; }
        .requirement-list li { display: flex; align-items: center; gap: 5px; margin-bottom: 3px; transition: color 0.2s; }
        .requirement-list li.met { color: #10b981; }
        .requirement-list li svg { width: 12px; height: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="title">Restablecer contraseña</h2>
        <p class="sub">Ingresa el código que enviamos a tu correo y tu nueva contraseña.</p>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <form id="resetForm" action="{{ route('password.reset.post') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') ?? request('email') ?? old('email') }}">

            <div class="form-group">
                <label class="form-label">Código de Seguridad</label>
                <input type="text" name="codigo" class="form-input code-input" placeholder="000000" maxlength="6" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nueva Contraseña</label>
                <div class="input-wrapper">
                    <input type="password" id="new_password" name="nueva_password" class="form-input" placeholder="········" required>
                    <button type="button" class="eye-btn" onclick="togglePassword('new_password', this)">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                
                <div class="strength-meter">
                    <div id="strengthBar" class="strength-bar"></div>
                </div>
                <span id="strengthText" class="strength-text" style="color: #9ca3af;">Seguridad: ——</span>

                <ul class="requirement-list">
                    <li id="req-length"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Mínimo 8 caracteres</li>
                    <li id="req-upper"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Una letra mayúscula</li>
                    <li id="req-number"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg> Un número</li>
                </ul>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label">Confirmar Contraseña</label>
                <div class="input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="········" required>
                    <button type="button" class="eye-btn" onclick="togglePassword('confirm_password', this)">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn-submit" disabled>Cambiar Contraseña</button>
        </form>
    </div>

    <script>
        function togglePassword(id, btn) {
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

        const passInput = document.getElementById('new_password');
        const confInput = document.getElementById('confirm_password');
        const submitBtn = document.getElementById('submitBtn');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        const reqs = {
            length: document.getElementById('req-length'),
            upper: document.getElementById('req-upper'),
            number: document.getElementById('req-number')
        };

        passInput.addEventListener('input', updateStrength);
        confInput.addEventListener('input', updateStrength);

        function updateStrength() {
            const val = passInput.value;
            const matches = confInput.value === val && val !== '';
            
            let score = 0;
            
            // Requerimientos individuales
            const metLength = val.length >= 8;
            const metUpper = /[A-Z]/.test(val);
            const metNumber = /\d/.test(val);

            if (metLength) { score++; reqs.length.classList.add('met'); } else { reqs.length.classList.remove('met'); }
            if (metUpper) { score++; reqs.upper.classList.add('met'); } else { reqs.upper.classList.remove('met'); }
            if (metNumber) { score++; reqs.number.classList.add('met'); } else { reqs.number.classList.remove('met'); }

            // UI del medidor
            strengthBar.className = 'strength-bar';
            if (score === 0) {
                strengthBar.style.width = '0%';
                strengthText.innerText = 'Seguridad: ——';
                strengthText.style.color = '#9ca3af';
            } else if (score === 1) {
                strengthBar.classList.add('weak');
                strengthText.innerText = 'Seguridad: Débil';
                strengthText.style.color = '#ef4444';
            } else if (score === 2) {
                strengthBar.classList.add('medium');
                strengthText.innerText = 'Seguridad: Media';
                strengthText.style.color = '#f59e0b';
            } else if (score === 3) {
                strengthBar.classList.add('strong');
                strengthText.innerText = 'Seguridad: Fuerte';
                strengthText.style.color = '#10b981';
            }

            // Habilitar botón si todo OK
            submitBtn.disabled = !(score === 3 && matches);
        }
    </script>
</body>
</html>
