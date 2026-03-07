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
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php $usuarios=[
                ['Juan Pérez','juan.perez@empresa.com','Administrador','blue','+51 987 654 321','Activo',true],
                ['Hannia Gonzales','hannia.gonzales@empresa.com','Capturista','purple','+51 912 345 678','Activo',true],
                ['Carlos Ruiz','carlos.ruiz@empresa.com','Resguardante','orange','+51 934 567 890','Inactivo',false],
                ['María González','maria.gonzalez@empresa.com','Resguardante','orange','+51 900 123 456','Activo',true],
                ['Ruth Piña','ruth.pina@empresa.com','Capturista','purple','+51 923 456 789','Activo',true],
                ['Andre Martinez','andre.martinez@empresa.com','Capturista','purple','+51 956 789 123','Activo',true],
                ['Valeria Briones','valeria.briones@empresa.com','Resguardante','orange','+51 980 654 321','Inactivo',false],
                ['Santiago Meneses','santiago.meneses@empresa.com','Administrador','blue','+51 995 321 654','Activo',true],
                ['Diego Ramirez','diego.ramirez@empresa.com','Capturista','purple','+51 902 111 222','Activo',true],
                ['Ana López','ana.lopez@empresa.com','Resguardante','orange','+51 945 778 899','Activo',true],
            ]; @endphp
            @foreach($usuarios as $i => $u)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($u[0],0,1)) }}{{ strtoupper(substr(explode(' ',$u[0])[1]??'',0,1)) }}
                        </div>
                        <span style="font-weight:600;font-size:.9rem;">{{ $u[0] }}</span>
                    </div>
                </td>
                <td style="font-size:.85rem;color:#6b7a8d;">{{ $u[1] }}</td>
                <td><span class="badge badge-{{ $u[3] }}">{{ $u[2] }}</span></td>
                <td style="font-size:.85rem;">{{ $u[4] }}</td>
                <td>
                    <span class="badge {{ $u[6] ? 'badge-green badge-dot' : 'badge-gray badge-dot' }}">
                        {{ $u[5] }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="#" class="action-btn" onclick="openEdit({{ $i }});return false;" title="Editar">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <a href="/usuarios/{{ $i+1 }}" class="action-btn" title="Ver">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="#" class="action-btn" title="Más opciones">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;">
        <span style="font-size:.85rem;color:#6b7a8d;">1 – 10 of 14</span>
        <div class="pagination">
            <a href="#" class="page-btn">‹</a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">2</a>
            <a href="#" class="page-btn">›</a>
        </div>
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
        <div class="modal-body">
            <div class="modal-section-label">Información personal</div>
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label>Nombre</label>
                    <input class="modal-input" id="create-nombre" placeholder="Ej. Juan" type="text">
                </div>
                <div class="modal-form-group">
                    <label>Apellido</label>
                    <input class="modal-input" id="create-apellido" placeholder="Ej. Pérez" type="text">
                </div>
            </div>
            <div class="modal-form-row modal-full">
                <div class="modal-form-group">
                    <label>Correo electrónico</label>
                    <input class="modal-input" id="create-email" placeholder="correo@empresa.com" type="email">
                </div>
            </div>
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label>Teléfono</label>
                    <input class="modal-input" id="create-telefono" placeholder="+51 900 000 000" type="tel">
                </div>
                <div class="modal-form-group">
                    <label>Rol</label>
                    <select class="modal-input modal-select" id="create-rol">
                        <option value="">Seleccionar rol</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Resguardante">Resguardante</option>
                        <option value="Capturista">Capturista</option>
                    </select>
                </div>
            </div>
            <div class="modal-section-label" style="margin-top:20px">Seguridad</div>
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label>Contraseña</label>
                    <input class="modal-input" id="create-password" placeholder="••••••••" type="password">
                </div>
                <div class="modal-form-group">
                    <label>Confirmar contraseña</label>
                    <input class="modal-input" id="create-password2" placeholder="••••••••" type="password">
                </div>
            </div>
            <div class="modal-form-row modal-full" style="margin-bottom:0">
                <div class="modal-form-group">
                    <label>Estado</label>
                    <div class="modal-toggle-wrap">
                        <label class="modal-toggle">
                            <input type="checkbox" checked id="create-status" onchange="document.getElementById('create-status-label').textContent=this.checked?'Activo':'Inactivo'">
                            <div class="modal-toggle-track"></div>
                            <div class="modal-toggle-thumb"></div>
                        </label>
                        <span id="create-status-label">Activo</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
            <button class="modal-btn-primary" onclick="closeModal('modal-create')">Guardar Usuario</button>
        </div>
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
        <div class="modal-body">
            <div class="modal-section-label">Información personal</div>
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label>Nombre</label>
                    <input class="modal-input" id="edit-nombre" type="text">
                </div>
                <div class="modal-form-group">
                    <label>Apellido</label>
                    <input class="modal-input" id="edit-apellido" type="text">
                </div>
            </div>
            <div class="modal-form-row modal-full">
                <div class="modal-form-group">
                    <label>Correo electrónico</label>
                    <input class="modal-input" id="edit-email" type="email">
                </div>
            </div>
            <div class="modal-form-row">
                <div class="modal-form-group">
                    <label>Teléfono</label>
                    <input class="modal-input" id="edit-telefono" type="tel">
                </div>
                <div class="modal-form-group">
                    <label>Rol</label>
                    <select class="modal-input modal-select" id="edit-rol">
                        <option value="Administrador">Administrador</option>
                        <option value="Resguardante">Resguardante</option>
                        <option value="Capturista">Capturista</option>
                    </select>
                </div>
            </div>
            <div class="modal-section-label" style="margin-top:20px">Seguridad</div>
            <div class="modal-form-row modal-full">
                <div class="modal-form-group">
                    <label>Nueva contraseña <span style="color:#b0bac9;font-weight:400">(dejar en blanco para no cambiar)</span></label>
                    <input class="modal-input" placeholder="••••••••" type="password">
                </div>
            </div>
            <div class="modal-form-row modal-full" style="margin-bottom:0">
                <div class="modal-form-group">
                    <label>Estado</label>
                    <div class="modal-toggle-wrap">
                        <label class="modal-toggle">
                            <input type="checkbox" id="edit-status" onchange="document.getElementById('edit-status-label').textContent=this.checked?'Activo':'Inactivo'">
                            <div class="modal-toggle-track"></div>
                            <div class="modal-toggle-thumb"></div>
                        </label>
                        <span id="edit-status-label">Activo</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
            <button class="modal-btn-primary" onclick="closeModal('modal-edit')">Guardar Cambios</button>
        </div>
    </div>
</div>

{{-- ===== ESTILOS MODALES ===== --}}
<style>

    /* ─── Overlay / fondo oscuro ─── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 30, 55, 0.45);
        backdrop-filter: blur(3px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    /* ─── Contenedor del modal ─── */
    .modal {
        background: #fff;
        border-radius: 16px;
        width: 520px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        opacity: 0;
        transform: translateY(16px) scale(0.98);
        transition: transform 0.22s, opacity 0.22s;
    }

    .modal-overlay.open .modal {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    /* ─── Header ─── */
    .modal-header {
        padding: 22px 24px 18px;
        border-bottom: 1px solid #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .modal-header h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a2535;
        margin: 0;
    }

    .modal-subtitle {
        font-size: 0.8rem;
        color: #9aa5b4;
        margin-top: 2px;
    }

    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e8ecf0;
        background: #fff;
        display: grid;
        place-items: center;
        cursor: pointer;
        color: #6b7a8d;
        flex-shrink: 0;
        transition: background 0.15s, color 0.15s;
    }

    .modal-close:hover {
        background: #f7f9fc;
        color: #2d3748;
    }

    /* ─── Body y Footer ─── */
    .modal-body {
        padding: 22px 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f0f2f5;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* ─── Etiqueta de sección ─── */
    .modal-section-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #9aa5b4;
        margin-bottom: 14px;
    }

    /* ─── Grid de campos ─── */
    .modal-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .modal-form-row.modal-full {
        grid-template-columns: 1fr;
    }

    .modal-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .modal-form-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #4a5568;
        letter-spacing: 0.2px;
    }

    /* ─── Inputs ─── */
    .modal-input {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.875rem;
        font-family: inherit;
        color: #2d3748;
        background: #fff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .modal-input:focus {
        border-color: #3a7bd5;
        box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.12);
    }

    .modal-input::placeholder {
        color: #b0bac9;
    }

    /* ─── Select ─── */
    .modal-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7a8d' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }

    /* ─── Botones del footer ─── */
    .modal-btn-secondary {
        background: #fff;
        color: #4a5568;
        border: 1.5px solid #e2e8f0;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: background 0.15s;
    }

    .modal-btn-secondary:hover {
        background: #f7f9fc;
    }

    .modal-btn-primary {
        background: linear-gradient(135deg, #3a7bd5, #2d5a8e);
        color: #fff;
        border: none;
        padding: 9px 22px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: opacity 0.15s;
    }

    .modal-btn-primary:hover {
        opacity: 0.9;
    }

    /* ─── Toggle de estado ─── */
    .modal-toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.875rem;
        color: #4a5568;
        margin-top: 4px;
    }

    .modal-toggle {
        position: relative;
        width: 42px;
        height: 24px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .modal-toggle input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .modal-toggle-track {
        position: absolute;
        inset: 0;
        background: #cbd5e0;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .modal-toggle input:checked + .modal-toggle-track {
        background: #3a7bd5;
    }

    .modal-toggle-thumb {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: left 0.2s;
    }

    .modal-toggle input:checked ~ .modal-toggle-thumb {
        left: 21px;
    }

    /* ─── Avatar en modal de edición ─── */
    .modal-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4a86b5, #2d5a8e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

</style>

{{-- ===== SCRIPTS MODALES ===== --}}
<script>
const _usuarios = @json(array_values($usuarios ?? []));

const _editData = [
    {nombre:'Juan',apellido:'Pérez',email:'juan.perez@empresa.com',rol:'Administrador',telefono:'+51 987 654 321',activo:true},
    {nombre:'Hannia',apellido:'Gonzales',email:'hannia.gonzales@empresa.com',rol:'Capturista',telefono:'+51 912 345 678',activo:true},
    {nombre:'Carlos',apellido:'Ruiz',email:'carlos.ruiz@empresa.com',rol:'Resguardante',telefono:'+51 934 567 890',activo:false},
    {nombre:'María',apellido:'González',email:'maria.gonzalez@empresa.com',rol:'Resguardante',telefono:'+51 900 123 456',activo:true},
    {nombre:'Ruth',apellido:'Piña',email:'ruth.pina@empresa.com',rol:'Capturista',telefono:'+51 923 456 789',activo:true},
    {nombre:'Andre',apellido:'Martinez',email:'andre.martinez@empresa.com',rol:'Capturista',telefono:'+51 956 789 123',activo:true},
    {nombre:'Valeria',apellido:'Briones',email:'valeria.briones@empresa.com',rol:'Resguardante',telefono:'+51 980 654 321',activo:false},
    {nombre:'Santiago',apellido:'Meneses',email:'santiago.meneses@empresa.com',rol:'Administrador',telefono:'+51 995 321 654',activo:true},
    {nombre:'Diego',apellido:'Ramirez',email:'diego.ramirez@empresa.com',rol:'Capturista',telefono:'+51 902 111 222',activo:true},
    {nombre:'Ana',apellido:'López',email:'ana.lopez@empresa.com',rol:'Resguardante',telefono:'+51 945 778 899',activo:true},
];

function openModal(id){
    document.getElementById(id).classList.add('open');
    document.body.style.overflow='hidden';
}
function closeModal(id){
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow='';
}

function initials(nombre, apellido){
    return (nombre[0]||'').toUpperCase() + (apellido[0]||'').toUpperCase();
}

function openEdit(i){
    const u = _editData[i];
    if(!u) return;
    document.getElementById('edit-nombre').value    = u.nombre;
    document.getElementById('edit-apellido').value  = u.apellido;
    document.getElementById('edit-email').value     = u.email;
    document.getElementById('edit-telefono').value  = u.telefono;
    document.getElementById('edit-rol').value       = u.rol;
    document.getElementById('edit-status').checked  = u.activo;
    document.getElementById('edit-avatar').textContent         = initials(u.nombre, u.apellido);
    document.getElementById('edit-modal-title').textContent    = u.nombre + ' ' + u.apellido;
    document.getElementById('edit-modal-sub').textContent      = u.email;
    document.getElementById('edit-status-label').textContent   = u.activo ? 'Activo' : 'Inactivo';
    openModal('modal-edit');
}

// Cerrar al hacer clic en el fondo
document.querySelectorAll('.modal-overlay').forEach(function(el){
    el.addEventListener('click', function(e){
        if(e.target === el) closeModal(el.id);
    });
});
</script>
@endsection
