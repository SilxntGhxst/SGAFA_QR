@extends('layouts.app')
@section('title','Nuevo Activo')
@section('page-title','Nuevo Activo')

@section('topbar-actions')
<a href="/activos" class="btn-outline">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
    Volver
</a>
@endsection

@push('styles')
<style>
.form-section { margin-bottom:28px; }
.form-section-title { font-family:'Sora',sans-serif; font-size:.85rem; font-weight:700; color:#0f1f35; text-transform:uppercase; letter-spacing:.06em; margin-bottom:16px; padding-bottom:8px; border-bottom:1px solid #f0f2f5; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; }
@media(max-width:768px){ .form-grid,.form-grid-3 { grid-template-columns:1fr; } }
.form-label { display:block; font-size:.78rem; font-weight:600; color:#6b7a8d; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
.form-field { width:100%; padding:11px 14px; background:#f7f7f7; border:1.5px solid #e4e8ef; border-radius:10px; font-family:'DM Sans',sans-serif; font-size:.92rem; color:#374151; outline:none; transition:all .2s; }
.form-field:focus { background:#fff; border-color:#4a86b5; box-shadow:0 0 0 3px rgba(74,134,181,.1); }
select.form-field { cursor:pointer; }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;">
    <div class="card" style="padding:28px;">

        <div class="form-section">
            <p class="form-section-title">Información general</p>
            <div class="form-grid" style="margin-bottom:16px;">
                <div>
                    <label class="form-label">Nombre del activo</label>
                    <input type="text" class="form-field" placeholder="Ej. Laptop Dell">
                </div>
                <div>
                    <label class="form-label">Código</label>
                    <input type="text" class="form-field" placeholder="ACT-000">
                </div>
            </div>
            <div class="form-grid-3">
                <div>
                    <label class="form-label">Categoría</label>
                    <select class="form-field">
                        <option>Seleccionar...</option>
                        <option>Equipos</option>
                        <option>Mobiliario</option>
                        <option>Audio-Video</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Ubicación</label>
                    <input type="text" class="form-field" placeholder="Oficina / Área">
                </div>
                <div>
                    <label class="form-label">Estado</label>
                    <select class="form-field">
                        <option>Disponible</option>
                        <option>Asignado</option>
                        <option>Mantenimiento</option>
                        <option>Prestado</option>
                        <option>Obsoleto</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <p class="form-section-title">Detalles económicos</p>
            <div class="form-grid">
                <div>
                    <label class="form-label">Valor de adquisición (S/)</label>
                    <input type="number" class="form-field" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Fecha de adquisición</label>
                    <input type="date" class="form-field">
                </div>
            </div>
        </div>

        <div class="form-section">
            <p class="form-section-title">Asignación</p>
            <div class="form-grid">
                <div>
                    <label class="form-label">Usuario asignado</label>
                    <select class="form-field">
                        <option>Sin asignar</option>
                        <option>Santiago Meneses</option>
                        <option>Carlos Chavez</option>
                        <option>Ruth Piña</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Notas</label>
                    <input type="text" class="form-field" placeholder="Observaciones...">
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px;">
            <a href="/activos" class="btn-outline">Cancelar</a>
            <button class="btn-primary">Guardar Activo</button>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card" style="padding:20px;">
            <p class="form-section-title" style="margin-bottom:12px;">Foto del activo</p>
            <div style="border:2px dashed #e4e8ef;border-radius:12px;padding:32px 20px;text-align:center;cursor:pointer;transition:border-color .2s;" onmouseover="this.style.borderColor='#4a86b5'" onmouseout="this.style.borderColor='#e4e8ef'">
                <svg width="36" height="36" fill="none" stroke="#b0bac6" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p style="font-size:.82rem;color:#9ca3af;">Arrastra o haz clic para subir imagen</p>
            </div>
        </div>
        <div class="card" style="padding:20px;">
            <p class="form-section-title" style="margin-bottom:12px;">Código QR</p>
            <div style="border:1.5px solid #e4e8ef;border-radius:12px;padding:24px;text-align:center;">
                <div style="width:80px;height:80px;background:#f0f2f5;border-radius:8px;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;">
                    <svg width="40" height="40" fill="none" stroke="#b0bac6" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                </div>
                <p style="font-size:.78rem;color:#9ca3af;">Se generará al guardar</p>
            </div>
        </div>
    </div>
</div>
@endsection