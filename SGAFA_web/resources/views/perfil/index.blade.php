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
    display: block;
    font-size: .78rem;
    font-weight: 600;
    color: #6b7a8d;
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 6px;
}
.form-field {
    width: 100%;
    padding: 11px 14px;
    background: #f7f7f7;
    border: 1.5px solid #e4e8ef;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: .92rem;
    color: #374151;
    outline: none;
    transition: all .2s;
}
.form-field:focus { background: #fff; border-color: #4a86b5; box-shadow: 0 0 0 3px rgba(74,134,181,.1); }
.form-field[readonly] {
    background: #f0f2f5;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e4e8ef;
}

/* Avatar upload hover */
.avatar-wrapper {
    position: relative;
    width: 80px; height: 80px;
    flex-shrink: 0;
    cursor: pointer;
}
.avatar-wrapper .avatar-circle {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4a86b5, #2d5a8e);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Sora', sans-serif;
    font-size: 1.6rem; font-weight: 800; color: #fff;
    transition: filter .2s;
    overflow: hidden;
}
.avatar-wrapper:hover .avatar-circle { filter: brightness(0.75); }
.avatar-overlay {
    position: absolute; inset: 0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s;
    pointer-events: none;
}
.avatar-wrapper:hover .avatar-overlay { opacity: 1; }
.avatar-overlay svg { color: #fff; }
.avatar-input { display: none; }

/* Activity items */
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f2f5;
}
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* Divider */
.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e4e8ef, transparent);
    margin: 24px 0;
}

/* Danger zone */
.danger-zone {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px;
    background: #fff5f5;
    border: 1px solid #fecaca;
    border-radius: 10px;
    margin-top: 4px;
}
.btn-danger-outline {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px;
    background: transparent; color: #dc2626;
    font-family: 'Sora', sans-serif; font-size: .88rem; font-weight: 700;
    border: 1.5px solid #fca5a5; border-radius: 10px;
    cursor: pointer; text-decoration: none; transition: all .2s;
}
.btn-danger-outline:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
</style>
@endpush

@section('content')

{{-- Header card --}}
<div class="card" style="padding:24px; margin-top:4px;">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">

        {{-- Avatar con opción de cambio de foto --}}
        <label class="avatar-wrapper" title="Cambiar foto">
            <div class="avatar-circle">AP</div>
            <div class="avatar-overlay">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <input type="file" class="avatar-input" accept="image/*">
        </label>

        <div>
            <h2 style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:#0f1f35;margin-bottom:6px;">
                Adán Piña
            </h2>
            <span class="badge badge-blue" style="margin-bottom:8px;display:inline-flex;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Admin
            </span>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:4px;">
                <span style="font-size:.88rem;color:#374151;">adan.pina@upq.edu.mx</span>
                <span class="badge badge-green badge-dot" style="font-size:.78rem;">Activo</span>
            </div>
        </div>

        {{-- Botón editar en header --}}
        <div style="margin-left:auto;">
            <a href="/perfil/edit" class="btn-outline">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Editar Perfil
            </a>
        </div>
    </div>
</div>

<div class="perfil-grid">

    {{-- Información personal --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;margin-bottom:20px;">
            Información personal
        </h3>

        <div style="margin-bottom:16px;">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-field" value="Adán Piña">
        </div>

        <div style="margin-bottom:16px;">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-field" value="adan.pina@upq.edu.mx">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div>
                <label class="form-label">Rol</label>
                {{-- Solo lectura: no es editable por el usuario --}}
                <input type="text" class="form-field" value="Admin" readonly>
            </div>
            <div>
                <label class="form-label">Estado</label>
                <div style="padding:11px 14px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:6px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-size:.92rem;font-weight:600;color:#15803d;">Activo</span>
                </div>
            </div>
        </div>

        {{-- Un solo botón guardar --}}
        <button class="btn-primary" style="padding:10px 24px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Guardar cambios
        </button>

        <div class="divider"></div>

        {{-- Zona de peligro: cerrar sesión con contexto visual --}}
        <div class="danger-zone">
            <div>
                <p style="font-size:.88rem;font-weight:600;color:#7f1d1d;margin-bottom:2px;">Cerrar sesión</p>
                <p style="font-size:.8rem;color:#b91c1c;">Finaliza tu sesión actual en este dispositivo.</p>
            </div>
            <a href="/login" class="btn-danger-outline">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Cerrar sesión
            </a>
        </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;">
                Actividad reciente
            </h3>
            <a href="#" style="font-size:.82rem;font-weight:600;color:#4a86b5;text-decoration:none;">Ver todo</a>
        </div>

        {{-- Leyenda de colores --}}
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;padding:10px 12px;background:#f8fafc;border-radius:8px;">
            <span style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:#6b7a8d;">
                <span style="width:8px;height:8px;border-radius:50%;background:#4a86b5;display:inline-block;"></span> Solicitud
            </span>
            <span style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:#6b7a8d;">
                <span style="width:8px;height:8px;border-radius:50%;background:#7c3aed;display:inline-block;"></span> Actualización
            </span>
            <span style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:#6b7a8d;">
                <span style="width:8px;height:8px;border-radius:50%;background:#15803d;display:inline-block;"></span> Registro
            </span>
            <span style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:#6b7a8d;">
                <span style="width:8px;height:8px;border-radius:50%;background:#a16207;display:inline-block;"></span> Otro
            </span>
        </div>

        @php
        $actividad = [
            ['Solicitó',   'la asignación de un Monitor Dell 27.',    '15 Feb 2026', 'blue',   '#eff6ff', '#4a86b5'],
            ['Actualizó',  'la información del Escritorio ACT-001.',   '12 Feb 2026', 'purple', '#faf5ff', '#7c3aed'],
            ['Registró',   'un nuevo activo: Cámara Sony Alpha.',      '09 Feb 2026', 'green',  '#f0fdf4', '#15803d'],
            ['Registró',   'Dispensador de agua en Pasillo 2.',        '05 Feb 2026', 'yellow', '#fef9c3', '#a16207'],
        ];
        @endphp

        @foreach($actividad as $a)
        <div class="activity-item">
            <div class="activity-icon" style="background:{{ $a[4] }};color:{{ $a[5] }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <div>
                <p style="font-size:.85rem;color:#374151;line-height:1.4;">
                    <strong>{{ $a[0] }}</strong> {{ $a[1] }}
                </p>
                <span style="font-size:.76rem;color:#9ca3af;">{{ $a[2] }}</span>
            </div>
        </div>
        @endforeach

        {{-- Ver más --}}
        <div style="text-align:center;margin-top:12px;">
            <a href="#" style="font-size:.82rem;font-weight:600;color:#4a86b5;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                Ver historial completo
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>

</div>
@endsection