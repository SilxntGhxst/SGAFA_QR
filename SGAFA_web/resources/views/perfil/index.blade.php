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
    color: #6b7a8d; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px;
}
.form-field {
    width: 100%; padding: 11px 14px; background: #f7f7f7;
    border: 1.5px solid #e4e8ef; border-radius: 10px;
    font-family: 'DM Sans', sans-serif; font-size: .92rem; color: #374151;
    outline: none; transition: all .2s;
}
.form-field:focus { background: #fff; border-color: #4a86b5; box-shadow: 0 0 0 3px rgba(74,134,181,.1); }
.form-field[readonly] { background: #f0f2f5; color: #9ca3af; cursor: not-allowed; border-color: #e4e8ef; }

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
    padding:16px; background:#fff5f5; border:1px solid #fecaca; border-radius:10px; margin-top:4px;
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
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:#15803d;">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:#fff5f5;border:1px solid #fecaca;border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:#dc2626;">{{ session('error') }}</span>
</div>
@endif

{{-- Header card --}}
<div class="card" style="padding:24px; margin-top:4px;">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div class="avatar-wrapper">
            <div class="avatar-circle">{{ $initials }}</div>
        </div>
        <div>
            <h2 style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:#0f1f35;margin-bottom:6px;">
                {{ $nombre }}
            </h2>
            <span class="badge badge-blue" style="margin-bottom:8px;display:inline-flex;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $rol }}
            </span>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:4px;">
                <span style="font-size:.88rem;color:#374151;">{{ $email }}</span>
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
                    <div style="padding:11px 14px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:6px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                        <span style="font-size:.92rem;font-weight:600;color:#15803d;">Activo</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:16px;padding:14px;background:#f8fafc;border-radius:10px;">
                <p style="font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.04em;margin-bottom:10px;">Cambiar contraseña (opcional)</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-field" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirm" class="form-field" placeholder="••••••••">
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
            <div style="padding:14px;background:#f8fafc;border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">ID de usuario</p>
                <p style="font-size:.78rem;font-family:'DM Mono',monospace;color:#374151;word-break:break-all;">{{ $user['id'] ?? '—' }}</p>
            </div>
            <div style="padding:14px;background:#f8fafc;border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Rol en el sistema</p>
                <span class="badge badge-blue">{{ $rol }}</span>
            </div>
            <div style="padding:14px;background:#f8fafc;border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Estado</p>
                <span class="badge badge-green badge-dot">Activo</span>
            </div>
            @if(!empty($user['creado_en']))
            <div style="padding:14px;background:#f8fafc;border-radius:10px;">
                <p style="font-size:.73rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Registrado el</p>
                <p style="font-size:.88rem;color:#374151;">{{ $user['creado_en'] }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection