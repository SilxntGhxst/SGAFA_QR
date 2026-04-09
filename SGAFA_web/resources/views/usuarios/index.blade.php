@extends('layouts.app')
@section('title','Usuarios')
@section('page-title','Usuarios')

@section('topbar-actions')
<a href="#" class="btn-primary" onclick="openModal('modal-create');return false;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nuevo Usuario
</a>
@endsection

@section('content')

{{-- Alertas --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--sk-bg,#f0fdf4);border:1px solid var(--border,#bbf7d0);border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:var(--text-primary,#15803d);">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:var(--sk-bg,#fff5f5);border:1px solid var(--border,#fecaca);border-radius:10px;margin-bottom:16px;">
    <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <span style="font-size:.88rem;font-weight:600;color:var(--text-primary,#dc2626);">{{ session('error') }}</span>
</div>
@endif

<div class="card" style="margin-bottom:16px;padding:14px 20px;">
    <div class="search-bar">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchUsuarios" placeholder="Buscar por nombre o correo..." oninput="filtrarUsuarios(this.value)">
    </div>
</div>

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="usuariosBody">
            @forelse($usuarios as $u)
            <tr class="usuario-row" data-nombre="{{ strtolower(($u['nombre']??'').' '.($u['apellidos']??'')) }}" data-email="{{ strtolower($u['email']??'') }}">
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($u['nombre']??'U',0,1)) }}{{ strtoupper(substr($u['apellidos']??'',0,1)) }}
                        </div>
                        <span style="font-weight:600;font-size:.9rem;">{{ $u['nombre'] }} {{ $u['apellidos'] }}</span>
                    </div>
                </td>
                <td style="font-size:.85rem;color:var(--text-secondary,#6b7a8d);">{{ $u['email'] }}</td>
                <td><span class="badge badge-blue">{{ $u['rol'] ?? 'Sin rol' }}</span></td>
                <td><span class="badge badge-green badge-dot">Activo</span></td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <button class="action-btn" title="Editar"
                            onclick="openEdit('{{ $u['id'] }}','{{ addslashes($u['nombre']) }}','{{ addslashes($u['apellidos']) }}','{{ $u['email'] }}',{{ $u['rol_id'] ?? 'null' }})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button class="action-btn" title="Eliminar" style="color:#dc2626;border-color:#fecaca;"
                            onclick="confirmarEliminar('{{ $u['id'] }}','{{ addslashes($u['nombre'].' '.$u['apellidos']) }}')">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-secondary,#9ca3af);font-size:.9rem;">
                No hay usuarios registrados aún.
            </td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid var(--border,#f0f2f5);">
        <span style="font-size:.85rem;color:var(--text-secondary,#6b7a8d);">{{ $total ?? count($usuarios) }} usuario(s) registrado(s)</span>
    </div>
</div>

{{-- ===== MODAL CREAR ===== --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h2>Nuevo Usuario</h2>
                <div class="modal-subtitle">Completa los datos para agregar un usuario</div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-create')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('usuarios.store') }}" id="formCrear">
            @csrf
            <div class="modal-body">
                <div class="modal-section-label">Información personal</div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Nombre</label>
                        <input class="modal-input" name="nombre" placeholder="Ej. Juan" type="text" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Apellidos</label>
                        <input class="modal-input" name="apellidos" placeholder="Ej. Pérez García" type="text" required>
                    </div>
                </div>
                <div class="modal-form-row modal-full">
                    <div class="modal-form-group">
                        <label>Correo electrónico</label>
                        <input class="modal-input" name="email" placeholder="correo@empresa.com" type="email" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Contraseña</label>
                        <input class="modal-input" name="password" placeholder="••••••••" type="password" required minlength="6">
                    </div>
                    <div class="modal-form-group">
                        <label>Rol</label>
                        <select class="modal-input modal-select" name="rol_id" required>
                            <option value="">Seleccionar rol</option>
                            <option value="1">Administrador</option>
                            <option value="2">Resguardante</option>
                            <option value="3">Auditor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="modal-btn-primary">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL EDITAR ===== --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal">
        <div class="modal-header">
            <div style="display:flex;align-items:center;gap:12px">
                <div class="modal-avatar" id="edit-avatar">JP</div>
                <div>
                    <h2 id="edit-modal-title">Editar Usuario</h2>
                    <div class="modal-subtitle" id="edit-modal-sub">Modifica los datos del usuario</div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-edit')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="formEditar" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="modal-section-label">Información personal</div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Nombre</label>
                        <input class="modal-input" id="edit-nombre" name="nombre" type="text" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Apellidos</label>
                        <input class="modal-input" id="edit-apellidos" name="apellidos" type="text" required>
                    </div>
                </div>
                <div class="modal-form-row modal-full">
                    <div class="modal-form-group">
                        <label>Correo electrónico</label>
                        <input class="modal-input" id="edit-email" name="email" type="email" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label>Rol</label>
                        <select class="modal-input modal-select" id="edit-rol" name="rol_id">
                            <option value="1">Administrador</option>
                            <option value="2">Resguardante</option>
                            <option value="3">Auditor</option>
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label>Nueva contraseña <span style="color:#b0bac9;font-weight:400">(opcional)</span></label>
                        <input class="modal-input" name="password" placeholder="••••••••" type="password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                <button type="submit" class="modal-btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL ELIMINAR ===== --}}
<div class="modal-overlay" id="modal-delete" style="z-index:1001;">
    <div class="modal" style="max-width:400px;">
        <div style="padding:28px;">
            <div style="width:48px;height:48px;border-radius:12px;background:var(--sk-bg,#fee2e2);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg width="22" height="22" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </div>
            <h3 style="font-family:'Sora',sans-serif;font-size:1.05rem;font-weight:800;color:var(--text-primary,#0f1f35);margin-bottom:6px;">¿Eliminar usuario?</h3>
            <p id="deleteDesc" style="font-size:.88rem;color:var(--text-secondary,#6b7a8d);line-height:1.5;margin-bottom:20px;">Esta acción no se puede deshacer.</p>
            <form method="POST" id="formEliminar" action="">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" class="modal-btn-secondary" onclick="closeModal('modal-delete')">Cancelar</button>
                    <button type="submit" class="modal-btn-primary" style="background:#dc2626;">Sí, eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== ESTILOS ===== --}}
<style>
.modal-overlay { position:fixed;inset:0;background:rgba(15,30,55,0.45);backdrop-filter:blur(3px);z-index:1000;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .2s; }
.modal-overlay.open { opacity:1;pointer-events:all; }
.modal { background:var(--card-bg,#fff);border-radius:16px;width:520px;max-width:95vw;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.18);opacity:0;transform:translateY(16px) scale(.98);transition:transform .22s,opacity .22s; }
.modal-overlay.open .modal { opacity:1;transform:translateY(0) scale(1); }
.modal-header { padding:22px 24px 18px;border-bottom:1px solid var(--border,#f0f2f5);display:flex;align-items:center;justify-content:space-between;gap:12px; }
.modal-header h2 { font-size:1.05rem;font-weight:700;color:var(--text-primary,#1a2535);margin:0; }
.modal-subtitle { font-size:.8rem;color:var(--text-secondary,#9aa5b4);margin-top:2px; }
.modal-close { width:32px;height:32px;border-radius:8px;border:1px solid var(--border,#e8ecf0);background:var(--card-bg,#fff);display:grid;place-items:center;cursor:pointer;color:var(--text-secondary,#6b7a8d);flex-shrink:0;transition:background .15s; }
.modal-close:hover { background:var(--sk-bg,#f7f9fc);color:var(--text-primary,#2d3748); }
.modal-body { padding:22px 24px; }
.modal-footer { padding:16px 24px;border-top:1px solid var(--border,#f0f2f5);display:flex;justify-content:flex-end;gap:10px; }
.modal-section-label { font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-secondary,#9aa5b4);margin-bottom:14px; }
.modal-form-row { display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px; }
.modal-form-row.modal-full { grid-template-columns:1fr; }
.modal-form-group { display:flex;flex-direction:column;gap:6px; }
.modal-form-group label { font-size:.8rem;font-weight:600;color:var(--text-secondary,#4a5568);letter-spacing:.2px; }
.modal-input { width:100%;border:1.5px solid var(--border,#e2e8f0);border-radius:8px;padding:9px 12px;font-size:.875rem;font-family:inherit;color:var(--text-primary,#2d3748);background:var(--sk-bg,#fff);outline:none;transition:border-color .15s,box-shadow .15s;box-sizing:border-box; }
.modal-input:focus { border-color:#3a7bd5;box-shadow:0 0 0 3px rgba(58,123,213,.12); }
.modal-input::placeholder { color:#b0bac9; }
.modal-select { cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7a8d' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center; }
.modal-btn-secondary { background:var(--sk-bg,#fff);color:var(--text-primary,#4a5568);border:1.5px solid var(--border,#e2e8f0);padding:9px 18px;border-radius:8px;font-size:.85rem;font-weight:600;font-family:inherit;cursor:pointer;transition:background .15s; }
.modal-btn-secondary:hover { background:var(--sk-bg,#f7f9fc); }
.modal-btn-primary { background:linear-gradient(135deg,#3a7bd5,#2d5a8e);color:#fff;border:none;padding:9px 22px;border-radius:8px;font-size:.85rem;font-weight:600;font-family:inherit;cursor:pointer;transition:opacity .15s; }
.modal-btn-primary:hover { opacity:.9; }
.modal-avatar { width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-size:.95rem;font-weight:700;color:#fff;flex-shrink:0; }
</style>

{{-- ===== SCRIPTS ===== --}}
<script>
function openModal(id){
    document.getElementById(id).classList.add('open');
    document.body.style.overflow='hidden';
}
function closeModal(id){
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow='';
}

function initials(nombre, apellidos){
    return ((nombre||'')[0]||'').toUpperCase() + ((apellidos||'')[0]||'').toUpperCase();
}

function openEdit(id, nombre, apellidos, email, rolId){
    document.getElementById('edit-nombre').value   = nombre;
    document.getElementById('edit-apellidos').value = apellidos;
    document.getElementById('edit-email').value    = email;
    document.getElementById('edit-rol').value      = rolId || '';
    document.getElementById('edit-avatar').textContent      = initials(nombre, apellidos);
    document.getElementById('edit-modal-title').textContent = nombre + ' ' + apellidos;
    document.getElementById('edit-modal-sub').textContent   = email;

    // Actualizar URL del form
    document.getElementById('formEditar').action = '/usuarios/' + id;

    openModal('modal-edit');
}

function confirmarEliminar(id, nombre){
    document.getElementById('deleteDesc').textContent = `Estás a punto de eliminar a "${nombre}". Esta acción no se puede deshacer.`;
    document.getElementById('formEliminar').action = '/usuarios/' + id;
    openModal('modal-delete');
}

// Filtro de búsqueda en tiempo real
function filtrarUsuarios(valor){
    const q = valor.toLowerCase();
    document.querySelectorAll('.usuario-row').forEach(row => {
        const nombre = row.dataset.nombre || '';
        const email  = row.dataset.email  || '';
        row.style.display = (nombre.includes(q) || email.includes(q)) ? '' : 'none';
    });
}

// Cerrar al hacer clic en el fondo
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if(e.target === el) closeModal(el.id); });
});
</script>
@endsection
