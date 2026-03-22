@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@push('styles')
<style>
/* ── STATS ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media(max-width:900px){ .stats-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:500px){ .stats-grid { grid-template-columns: 1fr; } }

.stat-card {
    background: #fff;
    border: 1px solid #e4e8ef;
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .2s, box-shadow .2s;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

.stat-card-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.stat-card-body { display: flex; align-items: flex-end; justify-content: space-between; }

.stat-value {
    font-family: 'Sora', sans-serif;
    font-size: 1.9rem; font-weight: 800;
    color: #0f1f35; letter-spacing: -0.04em;
    line-height: 1;
}
.stat-label {
    font-size: .8rem; font-weight: 500;
    color: #6b7a8d; margin-top: 4px;
}

.stat-trend {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: .74rem; font-weight: 700;
    padding: 3px 8px; border-radius: 20px;
}
.stat-trend.up   { background: #dcfce7; color: #15803d; }
.stat-trend.down { background: #fee2e2; color: #dc2626; }
.stat-trend.warn { background: #fef9c3; color: #a16207; }

/* ── LAYOUT PRINCIPAL ── */
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
}
@media(max-width:1100px){ .dash-grid { grid-template-columns: 1fr; } }

.section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px 14px;
    border-bottom: 1px solid #f0f2f5;
}
.section-title { font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 700; color: #0f1f35; }
.see-all { font-size: .82rem; font-weight: 600; color: #4a86b5; text-decoration: none; }
.see-all:hover { text-decoration: underline; }

/* ── ACCIONES RÁPIDAS ── */
.quick-action {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 20px;
    border-bottom: 1px solid #f0f2f5;
    text-decoration: none;
    transition: background .18s;
    color: #0f1f35;
}
.quick-action:last-child { border-bottom: none; }
.quick-action:hover { background: #f8fafc; }
.quick-action:hover .qa-arrow { opacity: 1; transform: translateX(0); }

.qa-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: #eff6ff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #4a86b5;
}
.qa-label { font-size: .88rem; font-weight: 500; flex: 1; }
.qa-arrow {
    color: #4a86b5; opacity: 0;
    transform: translateX(-4px);
    transition: all .2s;
}

/* ── DONUT ── */
.donut-wrap { padding: 20px; }
.donut-svg  { display: block; margin: 0 auto 16px; }
.legend     { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; padding: 0 4px; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: .78rem; color: #374151; }
.legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

/* ── TABLA INVENTARIO ── */
.inv-filters {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 20px; flex-wrap: wrap;
    border-bottom: 1px solid #f0f2f5;
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
    color: #9ca3af;
    font-size: .88rem;
}
</style>
@endpush

@section('content')

{{-- ── STATS ── --}}
<div class="stats-grid">

    {{-- Total Activos --}}
    <div class="stat-card" style="border-top: 3px solid #4a86b5;">
        <div class="stat-card-icon" style="background:#eff6ff; color:#4a86b5;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" id="stat-total-activos">{{ $stats['total_activos'] ?? 0 }}</div>
            <div class="stat-label">Total Activos</div>
        </div>
        <div class="stat-card-body">
            <span class="stat-trend up">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                +3 este mes
            </span>
        </div>
    </div>

    {{-- Activos Faltantes --}}
    <div class="stat-card" style="border-top: 3px solid #ef4444;">
        <div class="stat-card-icon" style="background:#fee2e2; color:#dc2626;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#dc2626;" id="stat-faltantes">{{ $stats['activos_faltantes'] ?? 0 }}</div>
            <div class="stat-label">Activos Faltantes</div>
        </div>
        <div class="stat-card-body">
            <span class="stat-trend down">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 9 12 15 6 9"/></svg>
                +2 esta semana
            </span>
        </div>
    </div>

    {{-- Solicitudes Pendientes --}}
    <div class="stat-card" style="border-top: 3px solid #f59e0b;">
        <div class="stat-card-icon" style="background:#fef9c3; color:#a16207;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="1"/>
                <line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="color:#a16207;" id="stat-pendientes">{{ $stats['solicitudes_pendientes'] ?? 0 }}</div>
            <div class="stat-label">Solicitudes Pendientes</div>
        </div>
        <div class="stat-card-body">
            <span class="stat-trend warn">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="18 9 12 15 6 9"/></svg>
                5 sin revisar
            </span>
        </div>
    </div>

</div>

{{-- ── MAIN GRID ── --}}
<div class="dash-grid">

    {{-- Inventario reciente --}}
    <div class="card">
        <div class="section-header">
            <span class="section-title">Inventario reciente</span>
            <a href="/activos" class="see-all">Ver todo →</a>
        </div>
        <form class="inv-filters" method="GET" action="{{ route('dashboard') }}">
            <div class="search-bar" style="max-width:220px;padding:9px 14px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Buscar activo..." value="{{ request('search') }}">
            </div>
            <div class="filter-select" style="padding:0; position:relative; display:flex; align-items:center;">
                <select name="categoria_id" onchange="this.form.submit()" style="border:none; background:transparent; padding:9px 34px 9px 14px; width:100%; appearance:none; outline:none; cursor:pointer; font-family:inherit; font-size:inherit; color:inherit;">
                    <option value="">Categoría</option>
                    @foreach($catalogos['categorias'] ?? [] as $cat)
                        <option value="{{ $cat['id'] }}" {{ request('categoria_id') == $cat['id'] ? 'selected' : '' }}>{{ $cat['categoria'] }}</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:10px; pointer-events:none;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="filter-select" style="padding:0; position:relative; display:flex; align-items:center;">
                <select name="estado" onchange="this.form.submit()" style="border:none; background:transparent; padding:9px 34px 9px 14px; width:100%; appearance:none; outline:none; cursor:pointer; font-family:inherit; font-size:inherit; color:inherit;">
                    <option value="">Estado</option>
                    @foreach(['Disponible', 'Asignado', 'Mantenimiento', 'Prestado', 'Faltante', 'Funcional'] as $est)
                        <option value="{{ $est }}" {{ request('estado') == $est ? 'selected' : '' }}>{{ $est }}</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:10px; pointer-events:none;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <noscript><button type="submit">Filtrar</button></noscript>
        </form>

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
                @forelse($activos as $it)
                @php
                    $color = match(strtolower($it['estado'] ?? '')) {
                        'disponible' => 'green',
                        'asignado' => 'blue',
                        'mantenimiento' => 'yellow',
                        'prestado' => 'purple',
                        'faltante' => 'red',
                        'funcional' => 'cyan',
                        default => 'gray'
                    };
                    $fecha = isset($it['fecha']) ? \Carbon\Carbon::parse($it['fecha'])->format('d M Y') : 'N/A';
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.88rem;">{{ $it['nombre'] ?? 'N/A' }}</div>
                        <div style="font-size:.76rem;color:#9ca3af;font-family:'DM Mono',monospace;">{{ $it['codigo'] ?? 'N/A' }}</div>
                    </td>
                    <td style="font-size:.88rem;">{{ $it['usuario'] ?? 'N/A' }}</td>
                    <td style="font-size:.88rem;color:#6b7a8d;">{{ $it['ubicacion'] ?? 'N/A' }}</td>
                    <td style="font-size:.83rem;color:#9ca3af;white-space:nowrap;">{{ $fecha }}</td>
                    <td><span class="badge badge-{{ $color }} badge-dot">{{ $it['estado'] ?? 'N/A' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="/activos/{{ $it['id'] ?? 1 }}" class="action-btn" title="Ver detalle">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </a>
                            <a href="/activos/{{ $it['id'] ?? 1 }}/edit" class="action-btn" title="Editar">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">No se encontraron activos.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="padding:12px 20px;border-top:1px solid #f0f2f5;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:.8rem;color:#9ca3af;">Mostrando 5 de 42 activos</span>
            <a href="/activos" class="see-all" style="font-size:.82rem;">Ver todos los activos →</a>
        </div>
    </div>

    {{-- Columna derecha --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Donut --}}
        <div class="card">
            <div class="section-header">
                <span class="section-title">Estado de Activos</span>
                <span style="font-size:.78rem;color:#9ca3af;">{{ $stats['total_activos'] ?? 0 }} total</span>
            </div>
            <div class="donut-wrap">
                @php
                    $total_grafico = max($stats['total_activos'] ?? 1, 1);
                    $circunferencia = 2 * pi() * 54; // aprox 339.292
                    $estado_counts = $stats['estado_counts'] ?? [];
                    
                    // Colores sincronizados con los badges de la tabla
                    $colores = [
                        'Asignado' => '#3b82f6',      // badge-blue
                        'Mantenimiento' => '#eab308', // badge-yellow
                        'Disponible' => '#10b981',    // badge-green
                        'Prestado' => '#8b5cf6',      // badge-purple
                        'Faltante' => '#ef4444',      // badge-red
                        'Funcional' => '#06b6d4'      // badge-cyan
                    ];
                    
                    $offset_actual = 0;
                @endphp
                <svg class="donut-svg" width="140" height="140" viewBox="0 0 140 140">
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#f0f2f5" stroke-width="22"/>
                    
                    @if(isset($stats['estado_counts']))
                        @foreach($colores as $estado => $color)
                            @php
                                $valor = $estado_counts[$estado] ?? 0;
                                if ($valor == 0) continue;
                                
                                $longitud_arco = ($valor / $total_grafico) * $circunferencia;
                                $espacio_restante = $circunferencia - $longitud_arco;
                                
                                $dasharray = "{$longitud_arco} {$espacio_restante}";
                                $dashoffset = -$offset_actual;
                                
                                $offset_actual += $longitud_arco;
                            @endphp
                            <circle cx="70" cy="70" r="54" fill="none" stroke="{{ $color }}" stroke-width="22"
                                stroke-dasharray="{{ $dasharray }}" stroke-dashoffset="{{ $dashoffset }}"
                                stroke-linecap="butt" transform="rotate(-90 70 70)"/>
                        @endforeach
                    @endif
                    
                    <text x="70" y="66" text-anchor="middle" font-family="Sora" font-size="20" font-weight="800" fill="#0f1f35">{{ $stats['total_activos'] ?? 0 }}</text>
                    <text x="70" y="82" text-anchor="middle" font-family="DM Sans" font-size="9" fill="#9ca3af">activos</text>
                </svg>
                <div class="legend">
                    @if(isset($stats['estado_counts']))
                        @foreach($colores as $estado => $color)
                            @php $v = $estado_counts[$estado] ?? 0; @endphp
                            @if($v > 0)
                            <div class="legend-item">
                                <div class="legend-dot" style="background:{{ $color }}"></div>
                                <span>{{ $estado == 'Mantenimiento' ? 'Mant.' : $estado }} <strong style="color:#0f1f35;">{{ $v }}</strong></span>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="card">
            <div class="section-header">
                <span class="section-title">Acciones rápidas</span>
            </div>

            <a href="/activos/crear" class="quick-action">
                <div class="qa-icon" style="background:#eff6ff;color:#4a86b5;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                </div>
                <span class="qa-label">Nuevo Activo</span>
                <svg class="qa-arrow" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="/solicitudes/administrativos" class="quick-action">
                <div class="qa-icon" style="background:#f0fdf4;color:#15803d;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <rect x="8" y="2" width="8" height="4" rx="1"/>
                        <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/>
                    </svg>
                </div>
                <span class="qa-label">Planificar Auditoría</span>
                <svg class="qa-arrow" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="/solicitudes/administrativos" class="quick-action">
                <div class="qa-icon" style="background:#fef9c3;color:#a16207;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                    </svg>
                </div>
                <span class="qa-label">Ver Solicitudes</span>
                <svg class="qa-arrow" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

            <a href="/reportes" class="quick-action">
                <div class="qa-icon" style="background:#faf5ff;color:#7c3aed;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/>
                        <path d="M14 17h6M17 14v6"/>
                    </svg>
                </div>
                <span class="qa-label">Generar Reporte</span>
                <svg class="qa-arrow" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>

        </div>

    </div>
</div>
@endsection