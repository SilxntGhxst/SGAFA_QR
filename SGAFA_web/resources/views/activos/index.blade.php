@extends('layouts.app')
@section('title','Activos')
@section('page-title','Activos')

@section('topbar-actions')
<a href="/activos/qr-masivo" class="btn-outline">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
    Generar QR
</a>
<a href="/activos/crear" class="btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nuevo
</a>
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

/* Modal de confirmación eliminar */
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.45); z-index:300;
    align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:#fff; border-radius:16px;
    padding:28px; width:100%; max-width:400px;
    box-shadow:0 20px 60px rgba(0,0,0,0.2);
    animation: modalIn .18s ease;
}
@keyframes modalIn { from { transform:scale(.95); opacity:0; } to { transform:scale(1); opacity:1; } }
.modal-icon {
    width:48px; height:48px; border-radius:12px;
    background:#fee2e2; display:flex; align-items:center; justify-content:center;
    margin-bottom:16px;
}
.modal-title { font-family:'Sora',sans-serif; font-size:1.05rem; font-weight:800; color:#0f1f35; margin-bottom:6px; }
.modal-desc  { font-size:.88rem; color:#6b7a8d; line-height:1.5; margin-bottom:20px; }
.modal-actions { display:flex; gap:10px; justify-content:flex-end; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="filters-bar">
        <div class="search-bar" style="max-width:260px;padding:9px 14px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar...">
        </div>
        <div class="filter-select">
            Categoría
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="filter-select">
            Estado
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="filter-select">
            Ubicación
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
    </div>

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
            @php $activos=[
                ['ACT-001','Escritorio','Bienes muebles','Oficina','Santiago Meneses','Funcional','blue',false],
                ['ACT-002','Ventilador','Bienes muebles','Almacén','Carlos Chavez','Mantenimiento','yellow',false],
                ['ACT-003','Impresora HP Laser','Bienes muebles','Sala IT','Ruth Piña','Mantenimiento','yellow',false],
                ['ACT-004','Silla de escritorio','Bienes muebles','Oficina','Andre Martinez','Mantenimiento','yellow',true],
                ['ACT-006','Monitor Dell 27','Bienes muebles','Oficina 204','Hannia Gonzales','Baja','green',false],
                ['ACT-007','Cámara Sony Alpha','Bienes muebles','Oficina','Valeria Briones','Mantenimiento','yellow',false],
                ['ACT-008','Proyector Epson','Bienes muebles','Aula 5','—','Baja','green',true],
                ['ACT-009','Televisión Samsung 55"','Bienes muebles','Lab 3','—','Baja','green',false],
            ]; @endphp
            @foreach($activos as $i => $a)
            <tr>
                <td><input type="checkbox" {{ $a[7]?'checked':'' }} class="row-check" onchange="updateBulk()"></td>
                <td style="font-weight:700;font-size:.85rem;color:#4a86b5;">{{ $a[0] }}</td>
                <td style="font-weight:600;">{{ $a[1] }}</td>
                <td style="font-size:.88rem;color:#6b7a8d;">{{ $a[2] }}</td>
                <td style="font-size:.88rem;">{{ $a[3] }}</td>
                <td style="font-size:.88rem;">{{ $a[4] }}</td>
                <td><span class="badge badge-{{ $a[6] }} badge-dot">{{ $a[5] }}</span></td>
                <td>
                    <div style="display:flex;gap:6px;align-items:center;">
                        {{-- Ver --}}
                        <a href="/activos/{{ $loop->iteration }}" class="action-btn" title="Ver detalle">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        {{-- Editar --}}
                        <a href="/activos/{{ $loop->iteration }}/edit" class="action-btn" title="Editar">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        {{-- QR --}}
                        <a href="#" class="action-btn" title="Ver QR" style="color:#4a86b5;border-color:#bfdbfe;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                        </a>
                        {{-- Eliminar --}}
                        <button class="action-btn" title="Eliminar"
                            onclick="confirmarEliminar('{{ $a[0] }}','{{ $a[1] }}')"
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
            <button class="btn-primary" style="padding:8px 16px;font-size:.85rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
                Generar QR
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
            <span>Mostrando 1–10 de 34 activos</span>
            <div class="filter-select" style="padding:6px 10px;">
                10
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
        <div class="pagination">
            <a href="#" class="page-btn">‹</a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">2</a>
            <a href="#" class="page-btn">3</a>
            <span style="font-size:.85rem;color:#6b7a8d;padding:0 4px;">...</span>
            <a href="#" class="page-btn">›</a>
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
<div class="modal-overlay" id="deleteModal" onclick="cerrarModal(event)">
    <div class="modal-box">
        <div class="modal-icon">
            <svg width="22" height="22" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
            </svg>
        </div>
        <div class="modal-title">¿Eliminar activo?</div>
        <div class="modal-desc" id="modalDesc">Esta acción no se puede deshacer.</div>
        <div class="modal-actions">
            <button class="btn-outline" style="padding:9px 18px;" onclick="cerrarModalBtn()">Cancelar</button>
            <button class="btn-primary" style="padding:9px 18px;background:#dc2626;" onclick="cerrarModalBtn()">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAll(el){
    document.querySelectorAll('.row-check').forEach(c=>c.checked=el.checked);
    updateBulk();
}
function updateBulk(){
    const checked=document.querySelectorAll('.row-check:checked');
    const bar=document.getElementById('bulkBar');
    if(checked.length>0){
        bar.classList.add('visible');
        document.getElementById('bulkCount').textContent=checked.length+' activo'+(checked.length>1?'s':'')+' seleccionado'+(checked.length>1?'s':'');
    } else { bar.classList.remove('visible'); }
}
function clearBulk(){
    document.querySelectorAll('.row-check').forEach(c=>c.checked=false);
    document.getElementById('bulkBar').classList.remove('visible');
}
function eliminarSeleccionados(){
    const n=document.querySelectorAll('.row-check:checked').length;
    document.getElementById('modalDesc').textContent=`Se eliminarán ${n} activo(s) seleccionados. Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.add('open');
}
function confirmarEliminar(codigo, nombre){
    document.getElementById('modalDesc').textContent=`Estás a punto de eliminar "${nombre}" (${codigo}). Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.add('open');
}
function cerrarModal(e){
    if(e.target===document.getElementById('deleteModal'))
        document.getElementById('deleteModal').classList.remove('open');
}
function cerrarModalBtn(){
    document.getElementById('deleteModal').classList.remove('open');
}
</script>
@endpush