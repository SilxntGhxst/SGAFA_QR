@extends('layouts.app')
@section('title','Editar Activo')
@section('page-title','Editar Activo')

@section('topbar-actions')
<a href="/activos" class="btn-outline">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Cancelar
</a>
@endsection

@push('styles')
<style>
.form-label {
    display:block;font-size:.78rem;font-weight:600;
    color:#6b7a8d;text-transform:uppercase;
    letter-spacing:.05em;margin-bottom:6px;
}
.form-field {
    width:100%;padding:11px 14px;
    background:#f7f7f7;border:1.5px solid #e4e8ef;
    border-radius:10px;font-family:'DM Sans',sans-serif;
    font-size:.92rem;color:#374151;outline:none;transition:all .2s;
}
.form-field:focus { background:#fff;border-color:#4a86b5;box-shadow:0 0 0 3px rgba(74,134,181,.1); }
.form-select {
    width:100%;padding:11px 14px;
    background:#f7f7f7;border:1.5px solid #e4e8ef;
    border-radius:10px;font-family:'DM Sans',sans-serif;
    font-size:.92rem;color:#374151;outline:none;
    appearance:none;cursor:pointer;transition:all .2s;
}
.form-select:focus { background:#fff;border-color:#4a86b5;box-shadow:0 0 0 3px rgba(74,134,181,.1); }
.form-group { margin-bottom:18px; }
.form-row { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
@media(max-width:600px){ .form-row { grid-template-columns:1fr; } }
.divider { height:1px;background:linear-gradient(90deg,transparent,#e4e8ef,transparent);margin:24px 0; }
</style>
@endpush

@section('content')
<div style="max-width:800px;">

    <div class="card" style="padding:28px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:700;color:#0f1f35;margin-bottom:24px;display:flex;align-items:center;gap:8px;">
            <div style="width:32px;height:32px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                <svg width="15" height="15" fill="none" stroke="#4a86b5" stroke-width="2.2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </div>
            Información del activo
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Código</label>
                <input type="text" class="form-field" value="ACT-001" readonly style="background:#f0f2f5;color:#9ca3af;cursor:not-allowed;">
            </div>
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" class="form-field" value="Escritorio" placeholder="Nombre del activo">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <div style="position:relative;">
                    <select class="form-select">
                        <option selected>Mobiliario</option>
                        <option>Equipos</option>
                        <option>Audio-Video</option>
                        <option>Tecnología</option>
                        <option>Otro</option>
                    </select>
                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;" width="14" height="14" fill="none" stroke="#6b7a8d" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Ubicación *</label>
                <div style="position:relative;">
                    <select class="form-select">
                        <option selected>Oficina Principal</option>
                        <option>Almacén</option>
                        <option>Sala IT</option>
                        <option>Sala Reuniones</option>
                        <option>Aula 5</option>
                        <option>Lab 3</option>
                        <option>Oficina 204</option>
                    </select>
                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;" width="14" height="14" fill="none" stroke="#6b7a8d" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Usuario asignado</label>
                <div style="position:relative;">
                    <select class="form-select">
                        <option selected>Santiago Meneses</option>
                        <option>Carlos Chavez</option>
                        <option>Ruth Piña</option>
                        <option>Andre Martinez</option>
                        <option>Hannia Gonzales</option>
                        <option>Valeria Briones</option>
                        <option>— Sin asignar</option>
                    </select>
                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;" width="14" height="14" fill="none" stroke="#6b7a8d" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Estado *</label>
                <div style="position:relative;">
                    <select class="form-select">
                        <option selected>Asignado</option>
                        <option>Disponible</option>
                        <option>Mantenimiento</option>
                        <option>Prestado</option>
                        <option>Obsoleto</option>
                    </select>
                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);pointer-events:none;" width="14" height="14" fill="none" stroke="#6b7a8d" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Valor (S/)</label>
            <input type="number" class="form-field" value="1200" placeholder="0.00">
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea class="form-field" rows="3" placeholder="Descripción del activo..." style="resize:vertical;">Escritorio de madera con superficie amplia, cajones laterales y soporte para monitor. En buen estado general.</textarea>
        </div>

        <div class="divider"></div>

        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <button type="button" onclick="document.getElementById('deleteModal').classList.add('open')"
                style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:none;border:1.5px solid #fecaca;border-radius:10px;color:#dc2626;font-family:'DM Sans',sans-serif;font-size:.88rem;font-weight:600;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='none'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                Eliminar activo
            </button>
            <div style="display:flex;gap:10px;">
                <a href="/activos" class="btn-outline" style="padding:10px 20px;">Cancelar</a>
                <button class="btn-primary" style="padding:10px 24px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Guardar cambios
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
<div class="modal-overlay" id="deleteModal" onclick="if(event.target===this)this.classList.remove('open')" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:300;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:28px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div style="width:48px;height:48px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
        </div>
        <div style="font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:800;color:#0f1f35;margin-bottom:6px;">¿Eliminar activo?</div>
        <div style="font-size:.88rem;color:#6b7a8d;line-height:1.5;margin-bottom:20px;">Estás a punto de eliminar "Escritorio" (ACT-001). Esta acción no se puede deshacer.</div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button class="btn-outline" style="padding:9px 18px;" onclick="document.getElementById('deleteModal').classList.remove('open')">Cancelar</button>
            <a href="/activos" class="btn-primary" style="padding:9px 18px;background:#dc2626;">Sí, eliminar</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('deleteModal').style.display = 'none';
document.getElementById('deleteModal').addEventListener('click', function(e){
    if(e.target === this) this.style.display = 'none';
});
document.querySelectorAll('[onclick*="deleteModal"]').forEach(btn => {
    btn.addEventListener('click', function(){
        document.getElementById('deleteModal').style.display = 'flex';
    });
});
</script>
@endpush
@endsection