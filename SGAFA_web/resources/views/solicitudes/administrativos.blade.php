@extends('layouts.app')
@section('title','Solicitudes / Movimientos Administrativos')
@section('page-title','Auditorías en Sitio')

@section('topbar-actions')
<a href="#" onclick="document.getElementById('modalNuevaAuditoria').style.display='flex'; return false;" class="btn-primary" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16,185,129,.3);">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Programar Auditoría
</a>
@endsection

@section('content')

@if(session('success'))
<div style="background:#dcfce7;color:#15803d;padding:12px 20px;border-radius:8px;margin-bottom:16px;font-weight:600;font-size:0.9rem;">
    {{ session('success') }}
</div>
@endif

<form class="card" style="margin-bottom:16px;padding:14px 20px;display:flex;gap:12px;align-items:center;" method="GET" action="{{ route('solicitudes.administrativos') }}">
    <div class="search-bar" style="flex:1;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
    </div>
    
    <div class="filter-select" style="padding:0; position:relative; display:flex; align-items:center; border: 1px solid #e2e8f0; border-radius:8px;">
        <select name="estado" onchange="this.form.submit()" style="border:none; background:transparent; padding:9px 34px 9px 14px; appearance:none; outline:none; cursor:pointer;">
            <option value="">Cualquier estado</option>
            <option value="Pendiente" {{ request('estado')=='Pendiente'?'selected':'' }}>Pendientes</option>
            <option value="En Progreso" {{ request('estado')=='En Progreso'?'selected':'' }}>En Progreso</option>
            <option value="Completada" {{ request('estado')=='Completada'?'selected':'' }}>Completadas</option>
        </select>
        <svg style="position:absolute; right:12px; pointer-events:none; color:#64748b;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
    </div>
    <noscript><button type="submit">Buscar</button></noscript>
</form>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>FOLIO</th>
                <th>AUDITOR ASIGNADO</th>
                <th>UBICACIÓN</th>
                <th>VIGENCIA</th>
                <th>PROGRESO</th>
                <th>ESTADO</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($auditorias as $index => $aud)
            @php
                $color = match(strtolower($aud['estado'] ?? '')) {
                    'completada' => 'green',
                    'en progreso' => 'blue',
                    'pendiente' => 'yellow',
                    default => 'gray'
                };
            @endphp
            <tr>
                <td style="font-weight:700;font-size:.88rem;color:#0f1f35;">{{ $aud['folio'] }}</td>
                <td style="font-size:.88rem;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:26px;height:26px;border-radius:13px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#64748b;">
                            {{ substr($aud['usuario_nombre'], 0, 1) }}
                        </div>
                        {{ $aud['usuario_nombre'] }}
                    </div>
                </td>
                <td style="font-weight:600;color:#475569;font-size:.88rem;">{{ $aud['ubicacion_nombre'] }}</td>
                <td style="font-size:.82rem;color:#6b7a8d;">
                    Del {{ \Carbon\Carbon::parse($aud['fecha_inicio'])->format('d M') }}<br/>
                    Al {{ \Carbon\Carbon::parse($aud['fecha_fin'])->format('d M Y') }}
                </td>
                <td style="font-size:.85rem;font-weight:600;font-family:'DM Mono',monospace;color:#4a86b5;">
                    {{ $aud['progreso'] }} activos
                    @if($aud['total_esperados'] > 0)
                        <div style="width:100%;height:4px;background:#f1f5f9;border-radius:2px;margin-top:4px;overflow:hidden;">
                            <div style="height:100%;background:#4a86b5;width:{{ min(100, ($aud['escaneados'] / max(1, $aud['total_esperados'])) * 100) }}%;"></div>
                        </div>
                    @endif
                </td>
                <td><span class="badge badge-{{ $color }}">{{ $aud['estado'] }}</span></td>
                <td>
                    <div style="display:flex;gap:12px;align-items:center;justify-content:flex-end;">
                        <button onclick="toggleDetails({{ $index }})" style="background:none;border:none;color:#64748b;cursor:pointer;font-weight:600;font-size:.78rem;display:flex;align-items:center;gap:4px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Detalles
                        </button>
                        <button onclick="abrirEditarAuditoria({{ json_encode($aud) }})" style="background:none;border:none;color:#4a86b5;cursor:pointer;display:flex;align-items:center;padding:4px;" title="Editar">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button onclick="confirmarEliminarAuditoria({{ $aud['id'] }}, '{{ $aud['folio'] }}')" style="background:none;border:none;color:#ef4444;cursor:pointer;display:flex;align-items:center;padding:4px;" title="Eliminar">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            <tr id="details-{{ $index }}" style="display:none; background:#f8fafc;">
                <td colspan="7" style="padding:20px; border-bottom:1px solid #e2e8f0;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <h4 style="margin:0 0 10px; color:#0f1f35; font-size:0.9rem;">Resumen de Auditoría</h4>
                            <p style="color:#475569; font-size:0.85rem; line-height:1.5; background:#fff; padding:12px; border:1px solid #e2e8f0; border-radius:8px; min-height:60px;">
                                @if(!empty($aud['resumen_final']))
                                    {{ $aud['resumen_final'] }}
                                @else
                                    <span style="font-style:italic; color:#94a3b8;">El operador aún no ha redactado el sumario final.</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h4 style="margin:0 0 10px; color:#0f1f35; font-size:0.9rem;">Activos Recolectados ({{ count($aud['activos_list']) }})</h4>
                            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:12px; max-height:140px; overflow-y:auto;">
                                @forelse($aud['activos_list'] as $activo)
                                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; border-bottom:1px dashed #e2e8f0; padding-bottom:8px;">
                                        <span style="font-size:0.85rem; color:#334155; font-weight:600;">{{ $activo['nombre'] }}</span>
                                        <span style="font-size:0.75rem; color:#64748b; font-family:'DM Mono',monospace;">{{ $activo['codigo'] }}</span>
                                    </div>
                                @empty
                                    <span style="font-style:italic; color:#94a3b8; font-size:0.8rem;">Ningún activo verificado aún.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div style="padding:40px;text-align:center;color:#94a3b8;font-size:0.9rem;">
                        No hay auditorías registradas en este momento.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Nueva Auditoría --}}
<div id="modalNuevaAuditoria" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(4px);">
    <form action="{{ route('solicitudes.administrativos.store') }}" method="POST" style="background:#fff;border-radius:16px;padding:32px;width:500px;box-shadow:0 12px 40px rgba(0,0,0,0.25);" onclick="event.stopPropagation()">
        @csrf

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
            <h2 style="margin:0;font-size:1.2rem;font-weight:800;color:#0f1f35;font-family:'Sora',sans-serif;">Programar Auditoría</h2>
            <button type="button" onclick="document.getElementById('modalNuevaAuditoria').style.display='none'" style="border:none;background:#f1f5f9;border-radius:50%;width:32px;height:32px;cursor:pointer;color:#64748b;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Auditor / Resguardante Asignado</label>
            <div style="position:relative;">
                <select name="usuario_id" required style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;appearance:none;outline:none;background:#fff;box-sizing:border-box;">
                    <option value="">Seleccione a la persona encargada...</option>
                    @foreach($catalogos['usuarios'] ?? [] as $user)
                        <option value="{{ $user['id'] }}">{{ $user['nombre'] }} {{ $user['apellidos'] }}</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:14px; top:12px; pointer-events:none; color:#64748b;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <p style="font-size:0.75rem;color:#94a3b8;margin-top:6px;">La app móvil de esta persona recibirá la tarea.</p>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Ubicación a Evaluar</label>
            <div style="position:relative;">
                <select name="ubicacion_id" required style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;appearance:none;outline:none;background:#fff;box-sizing:border-box;">
                    <option value="">Seleccione el espacio físico...</option>
                    @foreach($catalogos['ubicaciones'] ?? [] as $ubi)
                        <option value="{{ $ubi['id'] }}">{{ $ubi['nombre'] }} ({{ $ubi['edificio'] }})</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:14px; top:12px; pointer-events:none; color:#64748b;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-bottom:32px;">
            <div style="flex:1;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" required style="width:100%;padding:11px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;box-sizing:border-box;font-family:inherit;">
            </div>
            <div style="flex:1;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Fecha Límite</label>
                <input type="date" name="fecha_fin" required style="width:100%;padding:11px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;box-sizing:border-box;font-family:inherit;">
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('modalNuevaAuditoria').style.display='none'"
                style="padding:10px 20px;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-weight:700;font-size:.9rem;cursor:pointer;">
                Cancelar
            </button>
            <button type="submit"
                style="padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#059669);color:#fff;font-weight:700;font-size:.9rem;cursor:pointer;box-shadow:0 4px 12px rgba(16,185,129,.3);">
                Desplegar Auditoría
            </button>
        </div>

    </form>
</div>

{{-- Modal Editar Auditoría --}}
<div id="modalEditarAuditoria" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(4px);">
    <form id="formEditarAuditoria" method="POST" style="background:#fff;border-radius:16px;padding:32px;width:500px;box-shadow:0 12px 40px rgba(0,0,0,0.25);" onclick="event.stopPropagation()">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
            <div>
                <h2 style="margin:0;font-size:1.2rem;font-weight:800;color:#0f1f35;font-family:'Sora',sans-serif;">Editar Auditoría</h2>
                <p id="editFolio" style="margin:4px 0 0;font-size:0.8rem;color:#4a86b5;font-weight:700;"></p>
            </div>
            <button type="button" onclick="document.getElementById('modalEditarAuditoria').style.display='none'" style="border:none;background:#f1f5f9;border-radius:50%;width:32px;height:32px;cursor:pointer;color:#64748b;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Auditor / Resguardante Asignado</label>
            <div style="position:relative;">
                <select name="usuario_id" id="editUsuarioId" required style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;appearance:none;outline:none;background:#fff;box-sizing:border-box;">
                    <option value="">Seleccione a la persona encargada...</option>
                    @foreach($catalogos['usuarios'] ?? [] as $user)
                        <option value="{{ $user['id'] }}">{{ $user['nombre'] }} {{ $user['apellidos'] }}</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:14px; top:12px; pointer-events:none; color:#64748b;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Ubicación a Evaluar</label>
            <div style="position:relative;">
                <select name="ubicacion_id" id="editUbicacionId" required style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;appearance:none;outline:none;background:#fff;box-sizing:border-box;">
                    <option value="">Seleccione el espacio físico...</option>
                    @foreach($catalogos['ubicaciones'] ?? [] as $ubi)
                        <option value="{{ $ubi['id'] }}">{{ $ubi['nombre'] }} ({{ $ubi['edificio'] }})</option>
                    @endforeach
                </select>
                <svg style="position:absolute; right:14px; top:12px; pointer-events:none; color:#64748b;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>

        <div style="display:flex; gap:12px; margin-bottom:32px;">
            <div style="flex:1;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="editFechaInicio" required style="width:100%;padding:11px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;box-sizing:border-box;font-family:inherit;">
            </div>
            <div style="flex:1;">
                <label style="display:block;font-size:.85rem;font-weight:700;color:#334155;margin-bottom:8px;">Fecha Límite</label>
                <input type="date" name="fecha_fin" id="editFechaFin" required style="width:100%;padding:11px 14px;border-radius:10px;border:1px solid #cbd5e1;font-size:.9rem;color:#0f1f35;box-sizing:border-box;font-family:inherit;">
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('modalEditarAuditoria').style.display='none'"
                style="padding:10px 20px;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-weight:700;font-size:.9rem;cursor:pointer;">
                Cancelar
            </button>
            <button type="submit"
                style="padding:10px 24px;border-radius:10px;border:none;background:linear-gradient(135deg,#4f46e5,#3730a3);color:#fff;font-weight:700;font-size:.9rem;cursor:pointer;box-shadow:0 4px 12px rgba(79,70,229,.3);">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

{{-- Modal Confirmación Eliminar --}}
<div id="modalEliminarAuditoria" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);align-items:center;justify-content:center;z-index:9999;backdrop-filter:blur(4px);">
    <div style="background:#fff;border-radius:16px;padding:32px;width:400px;box-shadow:0 12px 40px rgba(0,0,0,0.25);text-align:center;">
        <div style="width:60px;height:60px;background:#fee2e2;color:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
        </div>
        <h2 style="margin:0 0 12px;font-size:1.1rem;font-weight:800;color:#0f1f35;">¿Eliminar Auditoría?</h2>
        <p style="margin:0 0 28px;font-size:0.9rem;color:#64748b;line-height:1.5;">Estás a punto de eliminar la auditoría <strong id="deleteFolio" style="color:#0f1f35;"></strong>. Esta acción no se puede deshacer.</p>
        
        <form id="formEliminarAuditoria" method="POST">
            @csrf
            @method('DELETE')
            <div style="display:flex;gap:12px;justify-content:center;">
                <button type="button" onclick="document.getElementById('modalEliminarAuditoria').style.display='none'"
                    style="padding:10px 20px;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-weight:700;font-size:.9rem;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit"
                    style="padding:10px 24px;border-radius:10px;border:none;background:#ef4444;color:#fff;font-weight:700;font-size:.9rem;cursor:pointer;box-shadow:0 4px 12px rgba(239,68,68,.3);">
                    Sí, Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleDetails(index) {
        var el = document.getElementById('details-' + index);
        if(el.style.display === 'none') {
            el.style.display = 'table-row';
        } else {
            el.style.display = 'none';
        }
    }

    function abrirEditarAuditoria(aud) {
        document.getElementById('editFolio').textContent = aud.folio;
        document.getElementById('editUsuarioId').value = aud.usuario_id;
        document.getElementById('editUbicacionId').value = aud.ubicacion_id;
        document.getElementById('editFechaInicio').value = aud.fecha_inicio;
        document.getElementById('editFechaFin').value = aud.fecha_fin;
        
        document.getElementById('formEditarAuditoria').action = `/solicitudes/administrativos/${aud.id}`;
        document.getElementById('modalEditarAuditoria').style.display = 'flex';
    }

    function confirmarEliminarAuditoria(id, folio) {
        document.getElementById('deleteFolio').textContent = folio;
        document.getElementById('formEliminarAuditoria').action = `/solicitudes/administrativos/${id}`;
        document.getElementById('modalEliminarAuditoria').style.display = 'flex';
    }
</script>
@endsection
