@extends('layouts.app')
@section('title','Activos')
@section('page-title','Activos')

@section('topbar-actions')
<button class="btn-outline" onclick="abrirExportarQR('todos')">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
    Descargar todos los QR
</button>
<button class="btn-primary" onclick="abrirPanel('nuevo')">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nuevo
</button>
@endsection

@push('styles')
<style>
.filters-bar {
    display:flex; align-items:center; gap:10px; padding:16px 20px;
    border-bottom:1px solid #f0f2f5; flex-wrap:wrap;
}
.bulk-bar {
    display:none; align-items:center; justify-content:space-between;
    padding:14px 20px;
    background:#eff6ff; border-top:1px solid #bfdbfe;
}
.bulk-bar.visible { display:flex; }

</style>
@endpush

@section('content')
<div class="card">
    <form method="GET" action="{{ route('activos.index') }}" class="filters-bar" id="filtersForm">
        <div class="search-bar" style="max-width:260px;padding:9px 14px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." onchange="this.form.submit()">
        </div>
        <select name="categoria_id" class="filter-select" onchange="this.form.submit()" style="appearance:none; padding-right:32px; background:transparent url('data:image/svg+xml;utf8,<svg width=\'14\' height=\'14\' fill=\'none\' stroke=\'%236b7a8d\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><polyline points=\'6 9 12 15 18 9\'></polyline></svg>') no-repeat right 12px center; cursor:pointer;">
            <option value="">Categoría</option>
            @foreach($catalogos['categorias'] ?? [] as $cat)
                <option value="{{ $cat['id'] }}" {{ request('categoria_id') == $cat['id'] ? 'selected' : '' }}>{{ $cat['categoria'] }}</option>
            @endforeach
        </select>
        <select name="estado" class="filter-select" onchange="this.form.submit()" style="appearance:none; padding-right:32px; background:transparent url('data:image/svg+xml;utf8,<svg width=\'14\' height=\'14\' fill=\'none\' stroke=\'%236b7a8d\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><polyline points=\'6 9 12 15 18 9\'></polyline></svg>') no-repeat right 12px center; cursor:pointer;">
            <option value="">Estado</option>
            <option value="funcional"     {{ request('estado') == 'funcional'     ? 'selected' : '' }}>Funcional</option>
            <option value="mantenimiento" {{ request('estado') == 'mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
            <option value="baja"          {{ request('estado') == 'baja'          ? 'selected' : '' }}>Baja</option>
        </select>
        <select name="ubicacion_id" class="filter-select" onchange="this.form.submit()" style="appearance:none; padding-right:32px; background:transparent url('data:image/svg+xml;utf8,<svg width=\'14\' height=\'14\' fill=\'none\' stroke=\'%236b7a8d\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><polyline points=\'6 9 12 15 18 9\'></polyline></svg>') no-repeat right 12px center; cursor:pointer;">
            <option value="">Ubicación</option>
            @foreach($catalogos['ubicaciones'] ?? [] as $ubi)
                <option value="{{ $ubi['id'] }}" {{ request('ubicacion_id') == $ubi['id'] ? 'selected' : '' }}>{{ $ubi['nombre'] }}</option>
            @endforeach
        </select>
        <a href="{{ route('activos.index') }}" class="btn-outline" style="padding: 9px 14px; font-size:0.85rem; display:flex; align-items:center; gap:6px;">Limpiar</a>
    </form>

    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox" onchange="toggleAll(this)"></th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Ubicación</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activos as $i => $a)
            <tr>
                <td><input type="checkbox" class="row-check" data-id="{{ $a['id'] }}" onchange="updateBulk()"></td>
                <td style="font-weight:700;font-size:.85rem;color:#4a86b5;">{{ $a['codigo'] }}</td>
                <td style="font-weight:600;">{{ $a['nombre'] }}</td>
                <td style="font-size:.88rem;color:#6b7a8d;">{{ $a['categoria'] }}</td>
                <td style="font-size:.88rem;">{{ $a['ubicacion'] }}</td>
                <td style="font-size:.88rem;">{{ $a['usuario'] }}</td>
                <td><span class="badge badge-{{ $a['color_badge'] }} badge-dot">{{ $a['estado'] }}</span></td>
                <td>
                    <div style="display:flex;gap:6px;align-items:center;">
                        {{-- Ver --}}
                        <button class="action-btn" title="Ver detalle" onclick="abrirPanel('ver',{{ $loop->index }})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        {{-- Editar --}}
                        <button class="action-btn" title="Editar" onclick="abrirPanel('editar',{{ $loop->index }})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        {{-- QR --}}
                        <button class="action-btn" title="Ver QR" style="color:#4a86b5;border-color:#bfdbfe;"
                            onclick="mostrarQR('{{ $a['codigo'] }}','{{ $a['nombre'] }}')">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                        </button>
                        {{-- Eliminar --}}
                        <button class="action-btn" title="Eliminar"
                            onclick="confirmarEliminar('{{ $a['id'] }}','{{ $a['nombre'] }}')"
                            style="color:#dc2626;border-color:#fecaca;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Bulk bar --}}
    <div class="bulk-bar" id="bulkBar">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:.88rem;font-weight:600;color:#1d4ed8;" id="bulkCount">0 activos seleccionados</span>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="btn-primary" style="padding:8px 16px;font-size:.85rem;" onclick="abrirExportarQR('seleccionados')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                Descargar Qr
            </button>
            <button class="btn-outline" style="padding:8px 16px;font-size:.85rem;color:#dc2626;border-color:#fecaca;" onclick="eliminarSeleccionados()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                Eliminar seleccionados
            </button>
            <button class="btn-outline" style="padding:8px 16px;font-size:.85rem;" onclick="clearBulk()">Cancelar</button>
        </div>
    </div>

    {{-- Footer --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;">
        <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:#6b7a8d;">
            <span>Mostrando @if($total == 0) 0 @else {{ min(($page-1)*$limit + 1, $total) }}–{{ min($page*$limit, $total) }} @endif de {{ $total }} activos</span>
            <form method="GET" action="{{ route('activos.index') }}" style="margin:0;">
                @foreach(request()->except('limit', 'page') as $key => $value)
                    @if($value) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                @endforeach
                <select name="limit" class="filter-select" onchange="this.form.submit()" style="padding:5px 26px 5px 10px; appearance:none; background:transparent url('data:image/svg+xml;utf8,<svg width=\'12\' height=\'12\' fill=\'none\' stroke=\'%236b7a8d\' stroke-width=\'2.5\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><polyline points=\'6 9 12 15 18 9\'></polyline></svg>') no-repeat right 8px center; cursor:pointer;">
                    <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $limit == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form>
        </div>
        @if($total > $limit)
        <div class="pagination">
            @php $totalPages = ceil($total / $limit); @endphp
            <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $page-1)]) }}" class="page-btn">‹</a>
            @for($p=1; $p<=$totalPages; $p++)
                <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" class="page-btn {{ $p == $page ? 'active' : '' }}">{{ $p }}</a>
            @endfor
            <a href="{{ request()->fullUrlWithQuery(['page' => min($totalPages, $page+1)]) }}" class="page-btn">›</a>
        </div>
        @endif
    </div>
</div>

@include('components.activos-panel')
@endsection

