@extends('layouts.app')
@section('title','Perfil')
@section('page-title','Perfil')

@push('styles')
<style>
.perfil-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    margin-top: 20px;
}
@media(max-width:1000px){ .perfil-grid { grid-template-columns: 1fr; } }

.form-label {
    display: block; font-size: .78rem; font-weight: 600;
    color: var(--text-secondary, #6b7a8d); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px;
}
.form-field {
    width: 100%; padding: 11px 14px; background: var(--sk-bg, #f7f7f7);
    border: 1.5px solid var(--border, #e4e8ef); border-radius: 10px;
    font-family: 'DM Sans', sans-serif; font-size: .92rem; color: var(--text-primary, #374151);
    outline: none; transition: all .2s;
}
.form-field:focus { background: var(--body-bg, #fff); border-color: #4a86b5; box-shadow: 0 0 0 3px rgba(74,134,181,.1); }
.form-field[readonly] { background: var(--border, #f0f2f5); color: var(--text-secondary, #9ca3af); cursor: not-allowed; border-color: var(--border, #e4e8ef); }

.avatar-wrapper { position:relative; width:80px; height:80px; flex-shrink:0; }
.avatar-circle {
    width:80px; height:80px; border-radius:50%;
    background: linear-gradient(135deg, #4a86b5, #2d5a8e);
    display:flex; align-items:center; justify-content:center;
    font-family:'Sora',sans-serif; font-size:1.6rem; font-weight:800; color:#fff;
    overflow:hidden;
}
.divider { height:1px; background: linear-gradient(90deg, transparent, #e4e8ef, transparent); margin:24px 0; }
.danger-zone {
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
    padding:16px; background:var(--sk-bg, #fff5f5); border:1px solid var(--border, #fecaca); border-radius:10px; margin-top:4px;
}
.btn-danger-outline {
    display:inline-flex; align-items:center; gap:7px; padding:9px 18px;
    background:transparent; color:#dc2626; font-family:'Sora',sans-serif;
    font-size:.88rem; font-weight:700; border:1.5px solid #fca5a5; border-radius:10px;
    cursor:pointer; text-decoration:none; transition:all .2s;
}
.btn-danger-outline:hover { background:#dc2626; color:#fff; border-color:#dc2626; }

.activity-item {
    display:flex; align-items:flex-start; gap:10px;
    padding:12px 0; border-bottom:1px solid #f0f2f5;
}
.activity-item:last-child { border-bottom:none; }
.activity-icon {
    width:32px; height:32px; border-radius:8px;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
}

/* Multi-use Password UX Styles */
.input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
.eye-btn { position: absolute; right: 0.8rem; background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0.4rem; display: flex; align-items: center; transition: color 0.2s; z-index: 5; }
.eye-btn:hover { color: #4a86b5; }

.strength-meter { height: 4px; background: #e5e7eb; border-radius: 10px; margin-top: 8px; overflow: hidden; position: relative; width: 100%; display: none; }
.strength-bar { height: 100%; width: 0; transition: all 0.4s ease; border-radius: 10px; }
.strength-text { font-size: 0.65rem; font-weight: 700; margin-top: 4px; display: none; text-transform: uppercase; letter-spacing: 0.5px; }

.weak { width: 33%; background: #ef4444; }
.medium { width: 66%; background: #f59e0b; }
.strong { width: 100%; background: #10b981; }

.requirement-list { font-size: 0.65rem; color: #9ca3af; margin-top: 6px; list-style: none; padding: 0; display: none; flex-wrap: wrap; gap: 8px; }
.requirement-list li { display: flex; align-items: center; gap: 4px; transition: color 0.2s; }
.requirement-list li.met { color: #10b981; }
.requirement-list li svg { width: 10px; height: 10px; }

.btn-primary:disabled { background: #cbd5e1; cursor: not-allowed; box-shadow: none; border-color: #e2e8f0; color: #94a3b8; }
</style>
@endpush

@section('content')

@php
    $nombre   = ($user['nombre']   ?? '') . ' ' . ($user['apellidos'] ?? '');
    $nombre   = trim($nombre) ?: 'Usuario';
    $email    = $user['email']    ?? '';
    $rol      = $user['rol']      ?? $user['rol_nombre'] ?? 'Sin rol';
    $initials = strtoupper(substr($user['nombre'] ?? 'U', 0, 1)) . strtoupper(substr($user['apellidos'] ?? '', 0, 1));
@endphp

{{-- Alertas de sesión --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--sk-bg, #f0fdf4);border:1px solid var(--border, #bbf7d0);border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:var(--text-primary, #15803d);">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--sk-bg, #fff5f5);border:1px solid var(--border, #fecaca);border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:var(--text-primary, #dc2626);">{{ session('error') }}</span>
</div>
@endif

{{-- Header card --}}
<div class="card" style="padding:24px; margin-top:4px;">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div class="avatar-wrapper">
            <div class="avatar-circle">{{ $initials }}</div>
        </div>
        <div style="flex:1;min-width:0;overflow:hidden;">
            <h2 style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text-primary,#0f1f35);margin-bottom:6px;white-space:normal;word-break:break-word;">
                {{ $nombre }}
            </h2>
            <span class="badge badge-blue" style="margin-bottom:8px;display:inline-flex;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $rol }}
            </span>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:4px;">
                <span style="font-size:.88rem;color:var(--text-secondary,#374151);">{{ $email }}</span>
                <span class="badge badge-green badge-dot" style="font-size:.78rem;">Activo</span>
            </div>
        </div>
    </div>
</div>

<div class="perfil-grid">
    {{-- Información personal --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;margin-bottom:20px;">
            Información personal
        </h3>

        <form method="POST" action="{{ route('perfil.update') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-field" value="{{ $user['nombre'] ?? '' }}" required>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-field" value="{{ $user['apellidos'] ?? '' }}" required>
            </div>
            <div style="margin-bottom:16px;">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-field" value="{{ $email }}" readonly>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label">Rol</label>
                    <input type="text" class="form-field" value="{{ $rol }}" readonly>
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <div style="padding:11px 14px;background:var(--sk-bg, #f0fdf4);border:1.5px solid var(--border, #bbf7d0);border-radius:10px;display:flex;align-items:center;gap:6px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                        <span style="font-size:.92rem;font-weight:600;color:var(--text-primary, #15803d);">Activo</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:16px;padding:14px;background:var(--sk-bg, #f8fafc);border-radius:10px;">
                <p style="font-size:.78rem;font-weight:600;color:var(--text-secondary, #6b7a8d);text-transform:uppercase;letter-spacing:.04em;margin-bottom:10px;">Cambiar contraseña (opcional)</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="form-label">Nueva contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="p_new" name="password" class="form-field" placeholder="••••••••" style="padding-right:2.8rem;">
                            <button type="button" class="eye-btn" onclick="toggleP('p_new', this)">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        
                        <div id="p_meter" class="strength-meter">
                            <div id="p_bar" class="strength-bar"></div>
                        </div>
                        <span id="p_text" class="strength-text" style="color: #9ca3af;">Fuerza: —</span>

                        <ul id="p_reqs" class="requirement-list">
                            <li id="r_len"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> 8+ letras</li>
                            <li id="r_up"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Mayúscula</li>
                            <li id="r_num"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg> Un número</li>
                        </ul>
                    </div>
                    <div>
                        <label class="form-label">Confirmar contraseña</label>
                        <div class="input-wrapper">
                            <input type="password" id="p_conf" name="password_confirm" class="form-field" placeholder="••••••••" style="padding-right:2.8rem;">
                            <button type="button" class="eye-btn" onclick="toggleP('p_conf', this)">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="padding:10px 24px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                Guardar cambios
            </button>
        </form>

        <div class="divider"></div>
        
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px; background:var(--sk-bg, #f8fafc); border:1px solid var(--border, #e4e8ef); border-radius:10px; margin-bottom: 24px;">
            <div>
                <p style="font-size:.88rem;font-weight:700;color:var(--text-primary);margin-bottom:2px;">Modo Oscuro</p>
                <p style="font-size:.8rem;color:var(--text-secondary);">Activa la interfaz oscura para descansar la vista.</p>
            </div>
            <label style="position:relative; display:inline-block; width:50px; height:26px;">
                <input type="checkbox" id="themeToggle" style="opacity:0; width:0; height:0;" onchange="toggleTheme(this)">
                <span style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#cbd5e1; transition:.4s; border-radius:34px;" id="themeToggleSlider">
                    <span style="position:absolute; content:''; height:20px; width:20px; left:3px; bottom:3px; background-color:white; transition:.4s; border-radius:50%;" id="themeToggleCircle"></span>
                </span>
            </label>
        </div>

        <div class="divider"></div>

        <div class="danger-zone">
            <div>
                <p style="font-size:.88rem;font-weight:600;color:#7f1d1d;margin-bottom:2px;">Cerrar sesión</p>
                <p style="font-size:.8rem;color:#b91c1c;">Finaliza tu sesión actual en este dispositivo.</p>
            </div>
            <a href="{{ route('logout') }}" class="btn-danger-outline">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Cerrar sesión
            </a>
        </div>
    </div>

    {{-- Actividad e información de cuenta --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;margin-bottom:16px;">
            Información de cuenta
        </h3>

        <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="padding:14px;background:var(--sk-bg, #f8fafc);border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:var(--text-secondary, #9ca3af);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">ID de usuario</p>
                <p style="font-size:.78rem;font-family:'DM Mono',monospace;color:var(--text-primary, #374151);word-break:break-all;">{{ $user['id'] ?? '—' }}</p>
            </div>
            <div style="padding:14px;background:var(--sk-bg, #f8fafc);border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:var(--text-secondary, #9ca3af);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Rol en el sistema</p>
                <span class="badge badge-blue">{{ $rol }}</span>
            </div>
            <div style="padding:14px;background:var(--sk-bg, #f8fafc);border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:var(--text-secondary, #9ca3af);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Estado</p>
                <span class="badge badge-green badge-dot">Activo</span>
            </div>
            @if(!empty($user['creado_en']))
            <div style="padding:14px;background:var(--sk-bg, #f8fafc);border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:var(--text-secondary, #9ca3af);text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Registrado el</p>
                <p style="font-size:.88rem;color:var(--text-primary, #374151);">{{ $user['creado_en'] }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const theme = localStorage.getItem('theme') || 'light';
    const toggle = document.getElementById('themeToggle');
    const slider = document.getElementById('themeToggleSlider');
    const circle = document.getElementById('themeToggleCircle');
    
    if (theme === 'dark') {
        toggle.checked = true;
        circle.style.transform = 'translateX(24px)';
        slider.style.backgroundColor = '#3d73a4';
    }
});

function toggleTheme(el) {
    const slider = document.getElementById('themeToggleSlider');
    const circle = document.getElementById('themeToggleCircle');
    
    if(el.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        circle.style.transform = 'translateX(24px)';
        slider.style.backgroundColor = '#3d73a4';
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        circle.style.transform = 'translateX(0)';
        slider.style.backgroundColor = '#cbd5e1';
    }
}

function toggleP(id, btn) {
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

const nIn = document.getElementById('p_new');
const cIn = document.getElementById('p_conf');
const sBt = document.querySelector('button[type="submit"]');
const sBa = document.getElementById('p_bar');
const sTx = document.getElementById('p_text');
const mtr = document.getElementById('p_meter');
const rqs = document.getElementById('p_reqs');

const rLn = document.getElementById('r_len');
const rUp = document.getElementById('r_up');
const rNu = document.getElementById('r_num');

const valP = () => {
    const v = nIn.value;
    if(!v) { 
        mtr.style.display='none'; sTx.style.display='none'; rqs.style.display='none';
        sBt.disabled = false; return; 
    }
    
    mtr.style.display='block'; sTx.style.display='block'; rqs.style.display='flex';
    const matches = (cIn.value === v && v !== '');
    
    let sc = 0;
    const mL = v.length >= 8;
    const mU = /[A-Z]/.test(v);
    const mN = /\d/.test(v);

    if(mL){ sc++; rLn.classList.add('met'); } else { rLn.classList.remove('met'); }
    if(mU){ sc++; rUp.classList.add('met'); } else { rUp.classList.remove('met'); }
    if(mN){ sc++; rNu.classList.add('met'); } else { rNu.classList.remove('met'); }

    sBa.className = 'strength-bar';
    if(sc === 0){ sBa.style.width='0%'; sTx.innerText='Fuerza: —'; sTx.style.color='#9ca3af'; }
    else if(sc === 1){ sBa.style.width='33%'; sBa.classList.add('weak'); sTx.innerText='Fuerza: Débil'; sTx.style.color='#ef4444'; }
    else if(sc === 2){ sBa.style.width='66%'; sBa.classList.add('medium'); sTx.innerText='Fuerza: Media'; sTx.style.color='#f59e0b'; }
    else if(sc === 3){ sBa.style.width='100%'; sBa.classList.add('strong'); sTx.innerText='Fuerza: Fuerte'; sTx.style.color='#10b981'; }

    sBt.disabled = !(sc === 3 && matches);
};

nIn.addEventListener('input', valP);
cIn.addEventListener('input', valP);
</script>
@endpush
