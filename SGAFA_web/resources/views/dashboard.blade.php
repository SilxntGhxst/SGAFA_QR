@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('topbar-actions')
<a href="#" class="topbar-icon-btn" title="Notificaciones">
    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
</a>
@endsection

@push('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: #fff;
    border: 1px solid #e4e8ef;
    border-radius: 14px;
    padding: 20px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }
.stat-card.accent { background: linear-gradient(135deg,#4a86b5,#2d5a8e); border-color:transparent; }
.stat-card.accent .stat-value, .stat-card.accent .stat-label { color:#fff !important; }
.stat-value { font-family:'Sora',sans-serif; font-size:2rem; font-weight:800; color:#0f1f35; letter-spacing:-0.04em; }
.stat-value.green { color:#15803d; }
.stat-label { font-size:.82rem; font-weight:500; color:#6b7a8d; margin-top:2px; }

.dash-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
}
@media(max-width:1100px){ .dash-grid { grid-template-columns: 1fr; } }

.section-header {
    display:flex; align-items:center; justify-content:space-between;
    padding: 18px 20px 14px;
    border-bottom: 1px solid #f0f2f5;
}
.section-title { font-family:'Sora',sans-serif; font-size:1rem; font-weight:700; color:#0f1f35; }
.see-all { font-size:.82rem; font-weight:600; color:#4a86b5; text-decoration:none; }
.see-all:hover { text-decoration:underline; }

.quick-action {
    display:flex; align-items:center; gap:12px;
    padding:14px 20px;
    border-bottom:1px solid #f0f2f5;
    text-decoration:none;
    transition:background .18s;
    color:#0f1f35;
}
.quick-action:last-child { border-bottom:none; }
.quick-action:hover { background:#f8fafc; }
.quick-action .qa-icon {
    width:34px; height:34px; border-radius:9px;
    background:#eff6ff;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
    color:#4a86b5;
}
.quick-action span { font-size:.9rem; font-weight:500; }
.quick-action .qa-plus { margin-left:auto; color:#4a86b5; font-size:1.2rem; font-weight:300; }

/* mini donut */
.donut-wrap { padding:20px; }
.donut-svg { display:block; margin:0 auto 16px; }
.legend { display:grid; grid-template-columns:1fr 1fr; gap:8px 16px; padding:0 4px; }
.legend-item { display:flex; align-items:center; gap:6px; font-size:.78rem; color:#374151; }
.legend-dot { width:10px;height:10px;border-radius:50%;flex-shrink:0; }

/* inv filters */
.inv-filters {
    display:flex; align-items:center; gap:10px;
    padding:14px 20px; flex-wrap:wrap;
    border-bottom:1px solid #f0f2f5;
}
</style>
@endpush

@section('content')

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card accent">
        <div class="stat-value">42</div>
        <div class="stat-label">Total Activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">8</div>
        <div class="stat-label">Activos Faltantes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">10</div>
        <div class="stat-label">Solicitudes Pendientes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value green">S/240,500</div>
        <div class="stat-label">Valor Total</div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="dash-grid">

    {{-- Inventario reciente --}}
    <div class="card">
        <div class="section-header">
            <span class="section-title">Inventario</span>
            <a href="/activos" class="see-all">Ver todo</a>
        </div>
        <div class="inv-filters">
            <div class="search-bar" style="max-width:220px;padding:9px 14px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" placeholder="Buscar">
            </div>
            <div class="filter-select">
                Categoría
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="filter-select">
                Estado
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ACTIVO</th>
                    <th>USUARIO</th>
                    <th>UBICACIÓN</th>
                    <th>FECHA</th>
                    <th>ESTADO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @php $items=[
                    ['ACT-001','Escritorio','Santiago Meneses','Oficina','16 Feb 2026','Asignado','blue'],
                    ['ACT-002','Ventilador','Carlos Chavez','Almacén','18 Feb 2026','Disponible','green'],
                    ['ACT-003','Impresora HP Laser','Ruth Piña','Sala IT','17 Feb 2026','Mantenimiento','yellow'],
                    ['ACT-004','Silla de escritorio','Andre Martinez','Oficina','19 Feb 2026','Asignado','blue'],
                    ['ACT-005','Proyector','Valeria Briones','Sala Reuniones','16 Feb 2026','Prestado','purple'],
                ]; @endphp
                @foreach($items as $it)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.88rem;">{{ $it[1] }}</div>
                        <div style="font-size:.77rem;color:#9ca3af;">{{ $it[0] }}</div>
                    </td>
                    <td style="font-size:.88rem;">{{ $it[2] }}</td>
                    <td style="font-size:.88rem;">{{ $it[3] }}</td>
                    <td style="font-size:.85rem;color:#6b7a8d;">{{ $it[4] }}</td>
                    <td><span class="badge badge-{{ $it[6] }}">{{ $it[5] }}</span></td>
                    <td>
                        <a href="/activos/1" class="action-btn" title="Ver">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Sidebar cards --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Donut --}}
        <div class="card">
            <div class="section-header">
                <span class="section-title">Estado de Activos</span>
            </div>
            <div class="donut-wrap">
                <svg class="donut-svg" width="140" height="140" viewBox="0 0 140 140">
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#f0f2f5" stroke-width="22"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#4a86b5" stroke-width="22"
                        stroke-dasharray="339.3" stroke-dashoffset="0" stroke-linecap="round" transform="rotate(-90 70 70)"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#34d399" stroke-width="22"
                        stroke-dasharray="101.8 237.5" stroke-dashoffset="-169.6" transform="rotate(-90 70 70)"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#f59e0b" stroke-width="22"
                        stroke-dasharray="67.9 271.4" stroke-dashoffset="-271.4" transform="rotate(-90 70 70)"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#a78bfa" stroke-width="22"
                        stroke-dasharray="50 289.3" stroke-dashoffset="-339.3" transform="rotate(-90 70 70)"/>
                    <text x="70" y="75" text-anchor="middle" font-family="Sora" font-size="18" font-weight="800" fill="#0f1f35">42</text>
                </svg>
                <div class="legend">
                    <div class="legend-item"><div class="legend-dot" style="background:#4a86b5"></div>Asignado</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#06b6d4"></div>Mantenimiento</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#34d399"></div>Disponible</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div>Prestado</div>
                </div>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="card">
            <div class="section-header">
                <span class="section-title">Nuevo Activo</span>
            </div>
            <a href="/activos/crear" class="quick-action">
                <div class="qa-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <span>Nuevo Activo</span>
                <span class="qa-plus">+</span>
            </a>
            <a href="/solicitudes/administrativos" class="quick-action">
                <div class="qa-icon" style="background:#f0fdf4;color:#15803d;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/></svg>
                </div>
                <span>Platificar Auditoría</span>
                <span class="qa-plus">+</span>
            </a>
            <a href="/solicitudes/administrativos" class="quick-action">
                <div class="qa-icon" style="background:#fef9c3;color:#a16207;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                </div>
                <span>Ver Solicitudes</span>
                <span class="qa-plus">+</span>
            </a>
        </div>
    </div>
</div>
@endsection