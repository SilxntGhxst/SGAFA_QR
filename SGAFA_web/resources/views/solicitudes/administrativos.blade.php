@extends('layouts.app')
@section('title','Solicitudes / Movimientos Administrativos')
@section('page-title','Solicitud/Movimientos Administrativos')

@section('topbar-actions')
<a href="#" onclick="document.getElementById('modalNuevaAuditoria').style.display='flex'; return false;" class="btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nueva auditoria
</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:16px;padding:14px 20px;">
    <div class="search-bar">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Buscar">
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Solicitante</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @php $sols=[
                ['SOL-025','Santiago Meneses','Asignación','19 Feb 2026','Pendiente','yellow'],
                ['SOL-026','Carlos Chavez','Traslado','18 Feb 2026','Aprobado','green'],
                ['SOL-027','Ruth Piña','Mantenimiento','17 Feb 2026','Aprobado','teal'],
                ['SOL-028','Andre Martinez','Asignación','17 Feb 2026','Rechazado','red'],
                ['SOL-029','Varia Gonzalez','Asignación','16 Feb 2026','En proceso','blue'],
                ['SOL-030','Yaleria Brionez','Devolución','16 Feb 2026','Aprobado','green'],
            ]; @endphp
            @foreach($sols as $s)
            <tr>
                <td style="font-weight:700;font-size:.88rem;">{{ $s[0] }}</td>
                <td style="font-size:.88rem;">{{ $s[1] }}</td>
                <td style="font-weight:600;">{{ $s[2] }}</td>
                <td style="font-size:.85rem;color:#6b7a8d;">{{ $s[3] }}</td>
                <td><span class="badge badge-{{ $s[5] }}">{{ $s[4] }}</span></td>
                <td>
                    <a href="/solicitudes/1" class="action-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;">
        <span style="font-size:.85rem;color:#6b7a8d;">1 – 5 of 18</span>
        <div class="pagination">
            <a href="#" class="page-btn">‹</a>
            <a href="#" class="page-btn">4</a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">›</a>
        </div>
    </div>
</div>

{{-- Modal Nueva Auditoría --}}
<div id="modalNuevaAuditoria" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.35);align-items:center;justify-content:center;z-index:1000;">
    <div style="background:#fff;border-radius:14px;padding:28px 28px 24px;width:440px;box-shadow:0 8px 32px rgba(0,0,0,0.18);" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;">
            <h2 style="margin:0;font-size:1.05rem;font-weight:700;color:#1a2e45;">Nueva Auditoría</h2>
            <button onclick="cerrarModal()" style="border:none;background:none;cursor:pointer;padding:4px;color:#94a3b8;display:flex;align-items:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:6px;">Solicitante</label>
            <input type="text" placeholder="Nombre del solicitante"
                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:.875rem;color:#334155;outline:none;box-sizing:border-box;">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:6px;">Fecha</label>
            <input type="date"
                style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:.875rem;color:#334155;outline:none;box-sizing:border-box;">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:6px;">Tipo</label>
            <select style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:.875rem;color:#334155;outline:none;background:#fff;box-sizing:border-box;">
                <option value="">Seleccionar tipo</option>
                <option>Asignación</option>
                <option>Traslado</option>
                <option>Mantenimiento</option>
                <option>Devolución</option>
            </select>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:6px;">Estado</label>
            <select style="width:100%;padding:9px 12px;border-radius:8px;border:1px solid #e2e8f0;font-size:.875rem;color:#334155;outline:none;background:#fff;box-sizing:border-box;">
                <option>Pendiente</option>
                <option>Aprobado</option>
                <option>Rechazado</option>
                <option>En proceso</option>
            </select>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" onclick="cerrarModal()"
                style="padding:8px 18px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;font-size:.875rem;cursor:pointer;">
                Cancelar
            </button>
            <button type="button" onclick="cerrarModal()"
                style="padding:8px 18px;border-radius:8px;border:none;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;font-weight:600;font-size:.875rem;cursor:pointer;box-shadow:0 2px 8px rgba(37,99,235,0.3);">
                Crear
            </button>
        </div>

    </div>
</div>

<script>
    // Cerrar modal con botón o clickeando fuera
    function cerrarModal() {
        document.getElementById('modalNuevaAuditoria').style.display = 'none';
    }
    document.getElementById('modalNuevaAuditoria').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
</script>
@endsection
