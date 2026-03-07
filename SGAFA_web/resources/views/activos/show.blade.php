@extends('layouts.app')
@section('title','Detalle de Activo')
@section('page-title','Detalle de Activo')

@section('topbar-actions')
<a href="/activos/{{ $id }}/edit" class="btn-outline">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
    Editar
</a>
<a href="/activos" class="btn-primary">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Volver
</a>
@endsection

@push('styles')
<style>
.show-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    margin-top: 4px;
}
@media(max-width:1000px){ .show-grid { grid-template-columns:1fr; } }

.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}
@media(max-width:600px){ .detail-row { grid-template-columns:1fr; } }

.detail-label {
    font-size: .75rem; font-weight: 600;
    color: #6b7a8d; text-transform: uppercase;
    letter-spacing: .05em; margin-bottom: 4px;
}
.detail-value {
    font-size: .92rem; font-weight: 500; color: #0f1f35;
}

.timeline-item {
    display: flex; gap: 12px;
    padding-bottom: 16px;
    position: relative;
}
.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 15px; top: 32px; bottom: 0;
    width: 2px; background: #f0f2f5;
}
.timeline-dot {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 2px;
}
</style>
@endpush

@section('content')

{{-- Encabezado del activo --}}
<div class="card" style="padding:24px;margin-bottom:0;">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
        <div style="width:60px;height:60px;border-radius:14px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="28" height="28" fill="none" stroke="#4a86b5" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
                <h2 style="font-family:'Sora',sans-serif;font-size:1.3rem;font-weight:800;color:#0f1f35;">Escritorio</h2>
                <span class="badge badge-blue badge-dot">Asignado</span>
            </div>
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:#9ca3af;font-weight:500;">ACT-001</span>
                <span style="font-size:.85rem;color:#6b7a8d;">Mobiliario</span>
                <span style="font-size:.85rem;color:#6b7a8d;">Oficina Principal</span>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="action-btn" title="Generar QR" style="width:36px;height:36px;color:#4a86b5;border-color:#bfdbfe;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
            </button>
            <button class="action-btn" title="Eliminar" style="width:36px;height:36px;color:#dc2626;border-color:#fecaca;" onclick="document.getElementById('deleteModal').classList.add('open')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            </button>
        </div>
    </div>
</div>

<div class="show-grid">

    {{-- Información detallada --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        <div class="card" style="padding:24px;">
            <h3 style="font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:#0f1f35;margin-bottom:20px;">Información general</h3>

            <div class="detail-row">
                <div>
                    <div class="detail-label">Código</div>
                    <div class="detail-value" style="font-family:'DM Mono',monospace;color:#4a86b5;">ACT-001</div>
                </div>
                <div>
                    <div class="detail-label">Nombre</div>
                    <div class="detail-value">Escritorio</div>
                </div>
            </div>
            <div class="detail-row">
                <div>
                    <div class="detail-label">Categoría</div>
                    <div class="detail-value">Mobiliario</div>
                </div>
                <div>
                    <div class="detail-label">Ubicación</div>
                    <div class="detail-value">Oficina Principal</div>
                </div>
            </div>
            <div class="detail-row">
                <div>
                    <div class="detail-label">Usuario asignado</div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:2px;">
                        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;color:#fff;">SM</div>
                        <span class="detail-value">Santiago Meneses</span>
                    </div>
                </div>
                <div>
                    <div class="detail-label">Estado</div>
                    <div style="margin-top:4px;"><span class="badge badge-blue badge-dot">Asignado</span></div>
                </div>
            </div>
            <div class="detail-row">
                <div>
                    <div class="detail-label">Valor</div>
                    <div class="detail-value" style="color:#15803d;font-weight:700;">S/ 1,200.00</div>
                </div>
                <div>
                    <div class="detail-label">Fecha de registro</div>
                    <div class="detail-value">16 Feb 2026</div>
                </div>
            </div>

            <div style="margin-top:4px;">
                <div class="detail-label">Descripción</div>
                <div class="detail-value" style="color:#6b7a8d;font-weight:400;line-height:1.6;margin-top:4px;">
                    Escritorio de madera con superficie amplia, cajones laterales y soporte para monitor. En buen estado general.
                </div>
            </div>
        </div>

        {{-- QR --}}
        <div class="card" style="padding:24px;">
            <h3 style="font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:#0f1f35;margin-bottom:16px;">Código QR</h3>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <div style="width:100px;height:100px;background:#f0f2f5;border-radius:12px;display:flex;align-items:center;justify-content:center;border:2px dashed #e4e8ef;">
                    <svg width="40" height="40" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                </div>
                <div>
                    <p style="font-size:.85rem;color:#6b7a8d;margin-bottom:12px;">Genera el código QR para identificar este activo físicamente.</p>
                    <button class="btn-primary" style="padding:9px 18px;font-size:.85rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                        Generar QR
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial --}}
    <div class="card" style="padding:24px;">
        <h3 style="font-family:'Sora',sans-serif;font-size:.95rem;font-weight:700;color:#0f1f35;margin-bottom:20px;">Historial de movimientos</h3>

        @php $historial = [
            ['Asignado a Santiago Meneses', 'Movimiento Admin.', '16 Feb 2026', 'blue', '#dbeafe', '#1d4ed8'],
            ['Traslado a Oficina Principal', 'Traslado', '10 Feb 2026', 'purple', '#ede9fe', '#7c3aed'],
            ['En mantenimiento preventivo', 'Mantenimiento', '02 Feb 2026', 'yellow', '#fef9c3', '#a16207'],
            ['Registrado en el sistema', 'Registro', '15 Ene 2026', 'green', '#dcfce7', '#15803d'],
        ]; @endphp

        @foreach($historial as $h)
        <div class="timeline-item">
            <div class="timeline-dot" style="background:{{ $h[4] }};color:{{ $h[5] }};">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <div>
                <div style="font-size:.85rem;font-weight:600;color:#0f1f35;">{{ $h[0] }}</div>
                <div style="font-size:.76rem;color:#9ca3af;margin-top:2px;">{{ $h[1] }} · {{ $h[2] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Modal eliminar --}}
<div class="modal-overlay" id="deleteModal" onclick="if(event.target===this)this.classList.remove('open')">
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

@push('styles')
<style>
.modal-overlay {
    display:none;position:fixed;inset:0;
    background:rgba(0,0,0,0.45);z-index:300;
    align-items:center;justify-content:center;
}
.modal-overlay.open { display:flex; }
</style>
@endpush
@endsection