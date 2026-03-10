@extends('layouts.app')
@section('title','Buzón de Discrepancia')
@section('page-title','Solicitud/Buzón de Discrepancia')

@push('styles')
<style>
.page-subtitle { font-size:.88rem; color:#6b7a8d; margin-top:-18px; margin-bottom:0; font-weight:500; }

.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 1000;
    align-items: center; justify-content: center;
}
.modal-overlay.active { display: flex; }
.modal-card {
    background: #fff; border-radius: 12px; width: 100%; max-width: 520px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18); overflow: hidden;
    animation: modalIn .18s ease;
}
@keyframes modalIn {
    from { opacity:0; transform: translateY(18px) scale(.98); }
    to   { opacity:1; transform: translateY(0) scale(1); }
}
.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px; border-bottom: 1px solid #f0f2f5;
}
.modal-header h3 { font-size: .98rem; font-weight: 700; color: #1a2233; margin: 0; }
.modal-close {
    background: none; border: none; cursor: pointer; color: #6b7a8d;
    padding: 4px; border-radius: 6px; display: flex; align-items: center; transition: background .15s;
}
.modal-close:hover { background: #f0f2f5; }
.modal-body {
    padding: 20px 22px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
}
.modal-body .form-group { display: flex; flex-direction: column; gap: 5px; }
.modal-body .form-group.full { grid-column: 1 / -1; }
.modal-body label {
    font-size: .78rem; font-weight: 600; color: #6b7a8d;
    text-transform: uppercase; letter-spacing: .03em;
}
.modal-body input, .modal-body select {
    border: 1px solid #e2e6ea; border-radius: 7px; padding: 8px 11px;
    font-size: .87rem; color: #1a2233; background: #f8f9fb; outline: none; transition: border-color .15s;
}
.modal-body input:focus, .modal-body select:focus { border-color: #2563eb; background: #fff; }
.modal-footer {
    display: flex; align-items: center; justify-content: flex-end;
    gap: 10px; padding: 14px 22px 18px; border-top: 1px solid #f0f2f5;
}
.btn-cancel {
    background: #f0f2f5; border: none; border-radius: 7px; padding: 8px 18px;
    font-size: .86rem; font-weight: 600; color: #6b7a8d; cursor: pointer; transition: background .15s;
}
.btn-cancel:hover { background: #e2e6ea; }
.btn-save {
    background: #2563eb; border: none; border-radius: 7px; padding: 8px 20px;
    font-size: .86rem; font-weight: 600; color: #fff; cursor: pointer; transition: background .15s;
}
.btn-save:hover { background: #1d4ed8; }
.modal-card.modal-sm { max-width: 380px; }
.modal-delete-body { padding: 22px 22px 10px; text-align: center; }
.modal-delete-body .delete-icon {
    width: 48px; height: 48px; background: #fef2f2; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px; color: #ef4444;
}
.modal-delete-body h3 { font-size: .97rem; font-weight: 700; color: #1a2233; margin: 0 0 6px; }
.modal-delete-body p  { font-size: .84rem; color: #6b7a8d; margin: 0; }
.btn-delete {
    background: #ef4444; border: none; border-radius: 7px; padding: 8px 20px;
    font-size: .86rem; font-weight: 600; color: #fff; cursor: pointer; transition: background .15s;
}
.btn-delete:hover { background: #dc2626; }
.action-btn-danger {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 6px; background: #fef2f2;
    color: #ef4444; border: none; cursor: pointer; text-decoration: none; transition: background .15s;
}
.action-btn-danger:hover { background: #fee2e2; }

/* Toast */
.toast {
    position: fixed; bottom: 28px; right: 28px; z-index: 9999;
    background: #1a2233; color: #fff; padding: 12px 20px;
    border-radius: 8px; font-size: .87rem; font-weight: 600;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    opacity: 0; transform: translateY(10px);
    transition: opacity .25s, transform .25s; pointer-events: none;
}
.toast.show   { opacity: 1; transform: translateY(0); }
.toast.success { border-left: 4px solid #10b981; }
.toast.error   { border-left: 4px solid #ef4444; }
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
            <input type="text" id="searchInput" placeholder="Buscar..." oninput="filtrarTabla()">
        </div>
        <div class="filter-select">
            <select id="filtroTipo" onchange="filtrarTabla()" style="border:none;background:none;font-size:.85rem;cursor:pointer;outline:none;">
                <option value="">Todos los tipos</option>
                <option value="Activo Faltante">Activo Faltante</option>
                <option value="Reporte de Daño">Reporte de Daño</option>
                <option value="Activo Fantasma">Activo Fantasma</option>
            </select>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="filter-select">
            <select id="filtroEstado" onchange="filtrarTabla()" style="border:none;background:none;font-size:.85rem;cursor:pointer;outline:none;">
                <option value="">Todos los estados</option>
                <option value="Pendiente">Pendiente</option>
                <option value="En revisión">En revisión</option>
                <option value="Resuelto">Resuelto</option>
                <option value="Rechazado">Rechazado</option>
            </select>
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <button onclick="cargarBuzon()" style="margin-left:auto;background:#2563eb;color:#fff;border:none;border-radius:7px;padding:8px 14px;font-size:.84rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            Recargar
        </button>
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Reportado por</th>
                <th>Área</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="buzonTableBody">
            <tr><td colspan="9" style="text-align:center;padding:30px;color:#6b7a8d;">Cargando registros...</td></tr>
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;flex-wrap:wrap;gap:12px;">
        <span id="totalLabel" style="font-size:.85rem;color:#6b7a8d;">–</span>
        <div style="display:flex;align-items:center;gap:8px;">
            <div class="pagination">
                <a href="#" class="page-btn">‹</a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">›</a>
            </div>
        </div>
    </div>
</div>

{{-- ===== EDIT MODAL ===== --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Editar Discrepancia</h3>
            <button class="modal-close" onclick="closeModal('editModal')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit-id">
            <div class="form-group">
                <label>Código</label>
                <input type="text" id="edit-codigo" readonly style="background:#f0f2f5;color:#6b7a8d;">
            </div>
            <div class="form-group">
                <label>Fecha</label>
                <input type="text" id="edit-fecha" readonly style="background:#f0f2f5;color:#6b7a8d;">
            </div>
            <div class="form-group full">
                <label>Activo</label>
                <input type="text" id="edit-activo">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select id="edit-tipo">
                    <option>Activo sin registro</option>
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
            <button class="btn-save" id="btnGuardar" onclick="saveEdit()">Guardar cambios</button>
        </div>
    </div>
</div>

{{-- ===== DELETE MODAL ===== --}}
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
            <button class="btn-delete" id="btnEliminar" onclick="confirmDelete()">Eliminar</button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="toast" id="toast"></div>

@push('scripts')
<script>
const API_URL = "http://localhost:8080/api";
let todosLosRegistros = [];
let currentDeleteId   = null;

const coloresTipo = {
    'Activo Faltante': 'orange',
    'Reporte de Daño': 'red',
    'Activo Fantasma': 'purple',
};
const coloresEstado = {
    'Pendiente':   'yellow',
    'En revisión': 'blue',
    'Resuelto':    'green',
    'Rechazado':   'red',
};

// ── Toast ────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = `toast ${type} show`;
    setTimeout(() => t.classList.remove('show'), 3200);
}

// ── Render tabla ─────────────────────────────────────────────────
function renderTabla(data) {
    const tbody = document.getElementById('buzonTableBody');
    document.getElementById('totalLabel').textContent = `Mostrando ${data.length} resultado(s)`;

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:30px;color:#6b7a8d;">No hay registros.</td></tr>`;
        return;
    }

    tbody.innerHTML = data.map(b => {
        const colorTipo   = coloresTipo[b.tipo]    ?? 'gray';
        const colorEstado = coloresEstado[b.estado] ?? 'gray';
        const fecha = b.created_at ? b.created_at.split('T')[0] : '–';

        return `
        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:700;font-size:.85rem;">${b.codigo}</td>
            <td style="font-weight:600;font-size:.88rem;">${b.activo}</td>
            <td><span class="badge badge-${colorTipo}" style="font-size:.75rem;padding:3px 9px;">${b.tipo}</span></td>
            <td style="font-size:.88rem;">${b.reportado_por}</td>
            <td style="font-size:.83rem;color:#6b7a8d;">${b.area}</td>
            <td style="font-size:.83rem;color:#6b7a8d;">${fecha}</td>
            <td><span class="badge badge-${colorEstado}" style="font-size:.78rem;">${b.estado}</span></td>
            <td style="display:flex;gap:4px;">
                <button type="button" class="action-btn" onclick="verDetalle(${b.id})" title="Ver detalle">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button type="button" class="action-btn" onclick="openEditModal(${b.id})" title="Editar">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button type="button" class="action-btn-danger" onclick="openDeleteModal(${b.id}, '${b.activo}', '${b.codigo}')" title="Eliminar">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </button>
            </td>
        </tr>`;
    }).join('');
}

async function cargarBuzon() {
    document.getElementById('buzonTableBody').innerHTML =
        `<tr><td colspan="9" style="text-align:center;padding:30px;color:#6b7a8d;">Cargando...</td></tr>`;
    try {
        const res  = await fetch(`${API_URL}/buzon`);
        const json = await res.json();
        todosLosRegistros = json.data ?? [];
        renderTabla(todosLosRegistros);
    } catch (e) {
        document.getElementById('buzonTableBody').innerHTML =
            `<tr><td colspan="9" style="text-align:center;padding:30px;color:#ef4444;">
                ⚠️ Error al conectar con la API. Verifica que Docker esté corriendo en el puerto 5000.
             </td></tr>`;
    }
}

async function verDetalle(id) {
    try {
        const res  = await fetch(`${API_URL}/buzon/${id}`);
        const json = await res.json();
        const b    = json.data;
        alert(`Detalle\n\nCódigo: ${b.codigo}\nActivo: ${b.activo}\nTipo: ${b.tipo}\nReportado por: ${b.reportado_por}\nÁrea: ${b.area}\nEstado: ${b.estado}\nDescripción: ${b.descripcion ?? '–'}`);
    } catch (e) {
        showToast('No se pudo obtener el detalle.', 'error');
    }
}

function filtrarTabla() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const tipo   = document.getElementById('filtroTipo').value;
    const estado = document.getElementById('filtroEstado').value;

    const filtrados = todosLosRegistros.filter(b => {
        const matchSearch = !search ||
            b.activo.toLowerCase().includes(search) ||
            b.codigo.toLowerCase().includes(search) ||
            b.reportado_por.toLowerCase().includes(search);
        const matchTipo   = !tipo   || b.tipo   === tipo;
        const matchEstado = !estado || b.estado === estado;
        return matchSearch && matchTipo && matchEstado;
    });

    renderTabla(filtrados);
}

function openEditModal(id) {
    const b = todosLosRegistros.find(r => r.id === id);
    if (!b) return;

    document.getElementById('edit-id').value        = b.id;
    document.getElementById('edit-codigo').value    = b.codigo;
    document.getElementById('edit-activo').value    = b.activo;
    document.getElementById('edit-fecha').value     = b.created_at ? b.created_at.split('T')[0] : '–';
    document.getElementById('edit-reportado').value = b.reportado_por;
    document.getElementById('edit-area').value      = b.area;

    const tipoSel = document.getElementById('edit-tipo');
    for (let opt of tipoSel.options) opt.selected = opt.value === b.tipo;

    const estadoSel = document.getElementById('edit-estado');
    for (let opt of estadoSel.options) opt.selected = opt.value === b.estado;

    document.getElementById('editModal').classList.add('active');
}

async function saveEdit() {
    const id  = document.getElementById('edit-id').value;
    const btn = document.getElementById('btnGuardar');
    btn.textContent = 'Guardando...';
    btn.disabled = true;

    const payload = {
        activo:        document.getElementById('edit-activo').value,
        tipo:          document.getElementById('edit-tipo').value,
        reportado_por: document.getElementById('edit-reportado').value,
        area:          document.getElementById('edit-area').value,
        estado:        document.getElementById('edit-estado').value,
    };

    try {
        const res  = await fetch(`${API_URL}/buzon/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.detail ?? 'Error al actualizar.');

        showToast('Registro actualizado correctamente ✓', 'success');
        closeModal('editModal');
        await cargarBuzon();
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.textContent = 'Guardar cambios';
        btn.disabled = false;
    }
}

function openDeleteModal(id, activo, codigo) {
    currentDeleteId = id;
    document.getElementById('delete-description').textContent =
        `Se eliminará "${activo}" (${codigo}). Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.add('active');
}

async function confirmDelete() {
    const btn = document.getElementById('btnEliminar');
    btn.textContent = 'Eliminando...';
    btn.disabled = true;

    try {
        const res  = await fetch(`${API_URL}/buzon/${currentDeleteId}`, { method: 'DELETE' });
        const json = await res.json();
        if (!res.ok) throw new Error(json.detail ?? 'Error al eliminar.');

        showToast('Registro eliminado correctamente ✓', 'success');
        closeModal('deleteModal');
        await cargarBuzon();
    } catch (e) {
        showToast(e.message, 'error');
    } finally {
        btn.textContent = 'Eliminar';
        btn.disabled = false;
    }
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

document.addEventListener('DOMContentLoaded', cargarBuzon);
</script>
@endpush
@endsection
