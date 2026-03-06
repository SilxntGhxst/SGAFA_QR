@extends('layouts.app')
@section('title','Perfil')
@section('page-title','Perfil')

@section('topbar-actions')
<a href="/perfil/edit" class="btn-outline">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
    Editar Perfil
</a>
@endsection

@push('styles')
<style>
.perfil-grid {
    display:grid;
    grid-template-columns:1fr 340px;
    gap:20px;
    margin-top:20px;
}
@media(max-width:1000px){ .perfil-grid { grid-template-columns:1fr; } }

.form-label {
    display:block;
    font-size:.78rem;
    font-weight:600;
    color:#6b7a8d;
    text-transform:uppercase;
    letter-spacing:.05em;
    margin-bottom:6px;
}
.form-field {
    width:100%;
    padding:11px 14px;
    background:#f7f7f7;
    border:1.5px solid #e4e8ef;
    border-radius:10px;
    font-family:'DM Sans',sans-serif;
    font-size:.92rem;
    color:#374151;
    outline:none;
    transition:all .2s;
}
.form-field:focus { background:#fff; border-color:#4a86b5; box-shadow:0 0 0 3px rgba(74,134,181,.1); }

.activity-item {
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:12px 0;
    border-bottom:1px solid #f0f2f5;
}
.activity-item:last-child { border-bottom:none; }
.activity-icon {
    width:30px;height:30px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
    background:#eff6ff;color:#4a86b5;
}
</style>
@endpush

@section('content')

{{-- Header card --}}
<div class="card" style="padding:24px;">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-family:'Sora',sans-serif;font-size:1.6rem;font-weight:800;color:#fff;flex-shrink:0;">AP</div>
        <div>
            <h2 style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:#0f1f35;margin-bottom:6px;">Adan Piña</h2>
            <span class="badge badge-blue" style="margin-bottom:8px;display:inline-flex;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Admin
            </span>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:4px;">
                <span style="font-size:.88rem;color:#374151;">adan.pina@upq.edu.mx</span>
                <span class="badge badge-green badge-dot" style="font-size:.78rem;">Activo</span>
            </div>
        </div>
    </div>
</div>

<div class="perfil-grid">

    {{-- Info personal --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;margin-bottom:20px;">Información personal</h3>

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
                <div class="filter-select form-field" style="background:#f7f7f7;">
                    Admin
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left:auto;"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <div>
                <label class="form-label">Estado</label>
                <div style="padding:11px 14px;background:#f7f7f7;border:1.5px solid #e4e8ef;border-radius:10px;display:flex;align-items:center;gap:6px;">
                    <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                    <span style="font-size:.92rem;font-weight:600;color:#15803d;">Activo</span>
                </div>
            </div>
        </div>

        <button class="btn-primary" style="padding:10px 24px;">Guardar cambios</button>

        <div style="height:1px;background:linear-gradient(90deg,transparent,#e4e8ef,transparent);margin:24px 0;"></div>

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <button class="btn-primary" style="padding:10px 24px;">Guardar Cambios</button>
            <a href="/login" style="font-size:.9rem;font-weight:700;color:#dc2626;text-decoration:none;transition:opacity .2s;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                Cerrar Sesión
            </a>
        </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
            <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;">Registro de actividad reciente</h3>
            <a href="#" style="font-size:.82rem;font-weight:600;color:#4a86b5;text-decoration:none;">Ver todo</a>
        </div>
        @php $actividad=[
            ['Solicitó','la asignación de un Monitor Dell 27.','15 Feb 2026','blue'],
            ['Actualizó','la información del Escritorio ACT-001.','12 Feb 2026','purple'],
            ['Registró','un nuevo activo: Cámara Sony Alpha.','09 Feb 2026','green'],
            ['Dispensador de agua.','Pasillo 2','05 Feb 2026','yellow'],
        ]; @endphp
        @foreach($actividad as $a)
        <div class="activity-item">
            <div class="activity-icon" style="background:{{ ['#eff6ff','#faf5ff','#f0fdf4','#fef9c3'][$loop->index] }};color:{{ ['#4a86b5','#7c3aed','#15803d','#a16207'][$loop->index] }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            </div>
            <div>
                <p style="font-size:.85rem;color:#374151;line-height:1.4;">
                    <strong>{{ $a[0] }}</strong> {{ $a[1] }}
                </p>
                <span style="font-size:.76rem;color:#9ca3af;">{{ $a[2] }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
