@extends('layouts.app')
@section('title','Buzón de Discrepancia')
@section('page-title','Solicitud/Buzón de Discrepancia')

@push('styles')
<style>
.page-subtitle { font-size:.88rem; color:#6b7a8d; margin-top:-18px; margin-bottom:0; font-weight:500; }

/* Modal overlay */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
.modal-overlay.active {
    display: flex;
}

/* Modal card */
.modal-card {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
    overflow: hidden;
    animation: modalIn .18s ease;
}
@keyframes modalIn {
    from { opacity:0; transform: translateY(18px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid #f0f2f5;
}
.modal-header h3 {
    font-size: .98rem;
    font-weight: 700;
    color: #1a2233;
    margin: 0;
}
.modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7a8d;
    padding: 4px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    transition: background .15s;
}
.modal-close:hover { background: #f0f2f5; }
.modal-body {
    padding: 20px 22px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.modal-body .form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.modal-body .form-group.full {
    grid-column: 1 / -1;
}
.modal-body label {
    font-size: .78rem;
    font-weight: 600;
    color: #6b7a8d;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.modal-body input,
.modal-body select {
    border: 1px solid #e2e6ea;
    border-radius: 7px;
    padding: 8px 11px;
    font-size: .87rem;
    color: #1a2233;
    background: #f8f9fb;
    outline: none;
    transition: border-color .15s;
}
.modal-body input:focus,
.modal-body select:focus {
    border-color: #2563eb;
    background: #fff;
}
.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 22px 18px;
    border-top: 1px solid #f0f2f5;
}
.btn-cancel {
    background: #f0f2f5;
    border: none;
    border-radius: 7px;
    padding: 8px 18px;
    font-size: .86rem;
    font-weight: 600;
    color: #6b7a8d;
    cursor: pointer;
    transition: background .15s;
}
.btn-cancel:hover { background: #e2e6ea; }
.btn-save {
    background: #2563eb;
    border: none;
    border-radius: 7px;
    padding: 8px 20px;
    font-size: .86rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: background .15s;
}
.btn-save:hover { background: #1d4ed8; }

/* Delete confirm modal */
.modal-card.modal-sm {
    max-width: 380px;
}
.modal-delete-body {
    padding: 22px 22px 10px;
    text-align: center;
}
.modal-delete-body .delete-icon {
    width: 48px;
    height: 48px;
    background: #fef2f2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    color: #ef4444;
}
.modal-delete-body h3 {
    font-size: .97rem;
    font-weight: 700;
    color: #1a2233;
    margin: 0 0 6px;
}
.modal-delete-body p {
    font-size: .84rem;
    color: #6b7a8d;
    margin: 0;
}
.btn-delete {
    background: #ef4444;
    border: none;
    border-radius: 7px;
    padding: 8px 20px;
    font-size: .86rem;
    font-weight: 600;
    color: #fff;
    cursor: pointer;
    transition: background .15s;
}
.btn-delete:hover { background: #dc2626; }

/* Delete action button */
.action-btn-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #fef2f2;
    color: #ef4444;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s;
}
.action-btn-danger:hover { background: #fee2e2; }
</style>
@endpush

@section('content')
<p class="page-subtitle">(Auditoria Móvil)</p>
<br>

{{-- Filters --}}
<div class="card" style="margin-bottom:16px;">
    <div style="display:flex;align-items:center;gap:10px;padding:14px 16px;flex-wrap:wrap;">
        <div class="search-bar" style="max-width:220px;padding:9px 14px;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Buscar...">
        </div>
        <div class="filter-select">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Fecha
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="filter-select">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            Todos
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="filter-select">
            Estado
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Código</th>
                <th>Activo</th>
                <th>Tipo</th>
                <th>Reportado por</th>
                <th>Área</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php $buzon=[
                ['COD-001','Laptop Lenovo','Activo Faltante','orange','Kevin Aguilar','Laboratorio 3','11/02/2026','Pendiente','yellow',true],
                ['COD-002','Proyector Epson','Reporte de Daño','red','Ana Ruiz','Aula 5','10/02/2026','En revisión','blue',false],
                ['COD-003','Monitor Dell 27"','Activo Fantasma','purple','Carlos Gómez','Oficina Administrativa','09/02/2026','Resuelto','green',false],
                ['COD-004','Monitor Lenovo','Activo Fantasma','purple','Gabriela Torres','Biblioteca','08/02/2026','Rechazado','red',false],
                ['COD-005','Proyector Epson','Reporte de Daño','red','Santiago Meneses','Almacén','07/02/2026','Pendiente','yellow',false],
                ['COD-006','Monitor Dell 27"','Activo Faltante','orange','Claudia Martinez','Sala IT','05/02/2026','En revisión','blue',false],
                ['COD-007','Monitor Dell 27"','Activo Fantasma','purple','Carlos Chavez','Laboratorio 3','05/02/2026','Resuelto','green',false],
                ['COD-008','Laptop Faltante','Activo Faltante','orange','Pedro Navarro','Laboratorio 3','05/02/2026','Resuelto','green',false],
            ]; @endphp
            @foreach($buzon as $i => $b)
            <tr>
                <td><input type="checkbox" {{ $b[9]?'checked':'' }}></td>
                <td style="font-weight:700;font-size:.85rem;">{{ $b[0] }}</td>
                <td style="font-weight:600;font-size:.88rem;">{{ $b[1] }}</td>
                <td>
                    <span class="badge badge-{{ $b[3] }}" style="font-size:.75rem;padding:3px 9px;">{{ $b[2] }}</span>
                </td>
                <td style="font-size:.88rem;">{{ $b[4] }}</td>
                <td style="font-size:.83rem;color:#6b7a8d;">{{ $b[5] }}</td>
                <td style="font-size:.83rem;color:#6b7a8d;">{{ $b[6] }}</td>
                <td><span class="badge badge-{{ $b[8] }}" style="font-size:.78rem;">{{ $b[7] }}</span></td>
                <td style="display:flex;gap:4px;">
                    {{-- View --}}
                    <a href="/solicitudes/buzon/{{ $i+1 }}" class="action-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    {{-- Edit → opens modal --}}
                    <button type="button" class="action-btn"
                        onclick="openEditModal({
                            codigo: '{{ $b[0] }}',
                            activo: '{{ $b[1] }}',
                            tipo: '{{ $b[2] }}',
                            reportado: '{{ $b[4] }}',
                            area: '{{ $b[5] }}',
                            fecha: '{{ $b[6] }}',
                            estado: '{{ $b[7] }}'
                        })">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    {{-- Delete → opens confirm modal --}}
                    <button type="button" class="action-btn-danger"
                        onclick="openDeleteModal('{{ $b[0] }}', '{{ $b[1] }}')">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;flex-wrap:wrap;gap:12px;">
        <span style="font-size:.85rem;color:#6b7a8d;">Mostrando 1 – 7 de 7 resultados</span>
        <div style="display:flex;align-items:center;gap:8px;">
            <div class="filter-select" style="padding:6px 10px;font-size:.82rem;">
                10 <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <div class="pagination">
                <a href="#" class="page-btn">‹</a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <a href="#" class="page-btn">›</a>
            </div>
        </div>
    </div>
</div>

{{-- ===================== EDIT MODAL ===================== --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Editar Discrepancia</h3>
            <button class="modal-close" onclick="closeModal('editModal')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Código</label>
                <input type="text" id="edit-codigo" readonly style="background:#f0f2f5;color:#6b7a8d;">
            </div>
            <div class="form-group">
                <label>Fecha</label>
                <input type="text" id="edit-fecha">
            </div>
            <div class="form-group full">
                <label>Activo</label>
                <input type="text" id="edit-activo">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select id="edit-tipo">
                    <option>Activo Faltante</option>
                    <option>Reporte de Daño</option>
                    <option>Activo Fantasma</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select id="edit-estado">
                    <option>Pendiente</option>
                    <option>En revisión</option>
                    <option>Resuelto</option>
                    <option>Rechazado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Reportado por</label>
                <input type="text" id="edit-reportado">
            </div>
            <div class="form-group">
                <label>Área</label>
                <input type="text" id="edit-area">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('editModal')">Cancelar</button>
            <button class="btn-save" onclick="saveEdit()">Guardar cambios</button>
        </div>
    </div>
</div>

{{-- ===================== DELETE CONFIRM MODAL ===================== --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-card modal-sm">
        <div class="modal-header">
            <h3>Eliminar registro</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-delete-body">
            <div class="delete-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
            </div>
            <h3>¿Eliminar esta discrepancia?</h3>
            <p id="delete-description">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('deleteModal')">Cancelar</button>
            <button class="btn-delete" onclick="confirmDelete()">Eliminar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentDeleteCodigo = null;

function openEditModal(data) {
    document.getElementById('edit-codigo').value    = data.codigo;
    document.getElementById('edit-activo').value    = data.activo;
    document.getElementById('edit-fecha').value     = data.fecha;
    document.getElementById('edit-reportado').value = data.reportado;
    document.getElementById('edit-area').value      = data.area;

    const tipoSel = document.getElementById('edit-tipo');
    for (let opt of tipoSel.options) {
        opt.selected = opt.value === data.tipo;
    }
    const estadoSel = document.getElementById('edit-estado');
    for (let opt of estadoSel.options) {
        opt.selected = opt.value === data.estado;
    }

    document.getElementById('editModal').classList.add('active');
}

function openDeleteModal(codigo, activo) {
    currentDeleteCodigo = codigo;
    document.getElementById('delete-description').textContent =
        `Se eliminará "${activo}" (${codigo}). Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function saveEdit() {
    // Aquí conectas con tu endpoint AJAX / form submit
    console.log('Guardando cambios para:', document.getElementById('edit-codigo').value);
    closeModal('editModal');
}

function confirmDelete() {
    // Aquí conectas con tu endpoint de eliminación
    console.log('Eliminando:', currentDeleteCodigo);
    closeModal('deleteModal');
}

// Cerrar al hacer clic en el overlay
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});
</script>
@endpush
@endsection
