<style>
/* Panel lateral (Nuevo / Ver / Editar) */
.panel-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(15,31,53,0.35); z-index:500;
    backdrop-filter:blur(2px);
}
.panel-overlay.open { display:block; }
.panel {
    position:fixed; top:0; right:0; bottom:0;
    width:100%; max-width:580px;
    background:var(--card-bg, #fff); box-shadow:-8px 0 40px rgba(0,0,0,0.15);
    display:flex; flex-direction:column;
    transform:translateX(100%); transition:transform .28s cubic-bezier(.4,0,.2,1);
    z-index:501;
}
.panel.open { transform:translateX(0); }
.panel-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:20px 24px; border-bottom:1px solid var(--border, #f0f2f5); flex-shrink:0;
}
.panel-title { font-family:'Sora',sans-serif; font-size:1rem; font-weight:800; color:var(--text-primary, #0f1f35); }
.panel-close {
    width:32px; height:32px; border-radius:8px; border:none;
    background:var(--sk-bg, #f1f5f9); cursor:pointer; display:flex;
    align-items:center; justify-content:center; transition:background .15s;
}
.panel-close:hover { background:var(--border, #e2e8f0); }
.panel-body { flex:1; overflow-y:auto; padding:24px; }
.panel-footer {
    padding:16px 24px; border-top:1px solid var(--border, #f0f2f5);
    display:flex; gap:10px; justify-content:flex-end; flex-shrink:0;
    background:var(--card-bg, #fff);
}
/* Campos del formulario dentro del panel */
.pf-label { display:block; font-size:.75rem; font-weight:600; color:var(--text-secondary, #6b7a8d); text-transform:uppercase; letter-spacing:.05em; margin-bottom:5px; }
.pf-field {
    width:100%; padding:10px 13px; background:var(--sk-bg, #f7f7f7);
    border:1.5px solid var(--border, #e4e8ef); border-radius:10px;
    font-family:'DM Sans',sans-serif; font-size:.9rem; color:var(--text-primary, #374151);
    outline:none; transition:all .2s; box-sizing:border-box;
}
.pf-field:focus { background:var(--body-bg, #fff); border-color:#4a86b5; box-shadow:0 0 0 3px rgba(74,134,181,.1); }
.pf-field[readonly] { background:var(--border, #f0f2f5); color:var(--text-secondary, #9ca3af); cursor:not-allowed; }
select.pf-field { cursor:pointer; appearance:none; }
select.pf-field option { background: var(--card-bg, #fff); color: var(--text-primary, #0f1f35); }
textarea.pf-field { resize:vertical; }
.pf-group { margin-bottom:16px; }
.pf-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px; }
.pf-section { font-family:'Sora',sans-serif; font-size:.8rem; font-weight:700; color:var(--text-primary, #0f1f35); text-transform:uppercase; letter-spacing:.06em; padding-bottom:8px; border-bottom:1px solid var(--border, #f0f2f5); margin-bottom:16px; }
/* Detalles en ver */
.detail-label { font-size:.73rem; font-weight:600; color:var(--text-secondary, #9ca3af); text-transform:uppercase; letter-spacing:.05em; margin-bottom:3px; }
.detail-value { font-size:.9rem; font-weight:500; color:var(--text-primary, #0f1f35); }

/* Modal exportar QR masivo */
.export-modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.5); z-index:400;
    align-items:center; justify-content:center;
}
.export-modal-overlay.open { display:flex; }
.export-modal-box {
    background:var(--card-bg, #fff); border-radius:16px;
    padding:28px; width:100%; max-width:460px;
    box-shadow:0 20px 60px rgba(0,0,0,0.25);
    animation: modalIn .18s ease;
}
.export-format-cards {
    display:grid; grid-template-columns:1fr 1fr; gap:12px; margin:20px 0;
}
.export-card {
    border:2px solid var(--border, #e4e8ef); border-radius:12px;
    padding:20px 16px; cursor:pointer; text-align:center;
    transition:border-color .15s, background .15s;
}
.export-card:hover { border-color:#4a86b5; background:var(--sk-bg, #f0f7ff); }
.export-card.selected { border-color:#4a86b5; background:var(--sk-bg, #eff6ff); }
.export-card-icon {
    width:44px; height:44px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 10px;
}
.export-card-title { font-family:'Sora',sans-serif; font-size:.9rem; font-weight:700; color:var(--text-primary, #0f1f35); margin-bottom:4px; }
.export-card-desc  { font-size:.78rem; color:var(--text-secondary, #6b7a8d); line-height:1.4; }
.export-progress {
    display:none; align-items:center; gap:10px;
    padding:12px 14px; background:var(--sk-bg, #f8fafc);
    border-radius:10px; margin-top:16px;
    font-size:.85rem; color:var(--text-secondary, #6b7a8d);
}
.export-progress.visible { display:flex; }
.export-spinner {
    width:18px; height:18px; border:2px solid #bfdbfe;
    border-top-color:#4a86b5; border-radius:50%;
    animation: spin .7s linear infinite; flex-shrink:0;
}
@keyframes spin { to { transform:rotate(360deg); } }

/* Modal QR */
.qr-modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.5); z-index:300;
    align-items:center; justify-content:center;
}
.qr-modal-overlay.open { display:flex; }
.qr-modal-box {
    background:var(--card-bg, #fff); border-radius:16px;
    padding:32px; width:100%; max-width:340px;
    box-shadow:0 20px 60px rgba(0,0,0,0.25);
    text-align:center;
    animation: modalIn .18s ease;
}
.qr-modal-box img {
    width:200px; height:200px;
    border-radius:8px; border:1px solid var(--border, #e4e8ef);
    margin:16px auto;
    display:block;
}
.qr-modal-title { font-family:'Sora',sans-serif; font-size:1rem; font-weight:800; color:var(--text-primary, #0f1f35); margin-bottom:2px; }
.qr-modal-code  { font-size:.82rem; color:var(--text-secondary, #9ca3af); font-family:'DM Mono',monospace; margin-bottom:4px; }

/* Lightbox foto */
.lightbox-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.88); z-index:9999;
    align-items:center; justify-content:center;
    padding:24px;
}
.lightbox-overlay.open { display:flex; }
.lightbox-overlay img {
    max-width:100%; max-height:90vh;
    border-radius:12px; object-fit:contain;
    box-shadow:0 24px 60px rgba(0,0,0,.6);
    animation:modalIn .18s ease;
}
.lightbox-close {
    position:absolute; top:16px; right:16px;
    background:rgba(255,255,255,.15); border:none; border-radius:50%;
    width:38px; height:38px; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    transition:background .15s;
}
.lightbox-close:hover { background:rgba(255,255,255,.28); }
/* Modal de confirmación eliminar */
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.45); z-index:900;
    align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:var(--card-bg, #fff); border-radius:16px;
    padding:28px; width:100%; max-width:400px;
    box-shadow:0 20px 60px rgba(0,0,0,0.2);
    animation: modalIn .18s ease;
}
@keyframes modalIn { from { transform:scale(.95); opacity:0; } to { transform:scale(1); opacity:1; } }
.modal-icon {
    width:48px; height:48px; border-radius:12px;
    background:var(--sk-bg, #fee2e2); display:flex; align-items:center; justify-content:center;
    margin-bottom:16px;
}
.modal-title { font-family:'Sora',sans-serif; font-size:1.05rem; font-weight:800; color:var(--text-primary, #0f1f35); margin-bottom:6px; }
.modal-desc  { font-size:.88rem; color:var(--text-secondary, #6b7a8d); line-height:1.5; margin-bottom:20px; }
.modal-actions { display:flex; gap:10px; justify-content:flex-end; }
</style>
{{-- Panel lateral (Nuevo / Ver / Editar) --}}
<div class="panel-overlay" id="panelOverlay" onclick="cerrarPanel()"></div>
<div class="panel" id="panel">

    <div class="panel-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <div id="panelIconWrap" style="width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;"></div>
            <span class="panel-title" id="panelTitle"></span>
        </div>
        <button class="panel-close" onclick="cerrarPanel()">
            <svg width="15" height="15" fill="none" stroke="#6b7a8d" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div class="panel-body" id="panelBody"></div>

    <div class="panel-footer" id="panelFooter"></div>
</div>

{{-- Modal exportar QR masivo --}}
<div class="export-modal-overlay" id="exportModal" onclick="if(event.target===this)cerrarExportModal()">
    <div class="export-modal-box">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:4px;">
            <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="#4a86b5" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/></svg>
            </div>
            <div>
                <div style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:800;color:#0f1f35;" id="exportModalTitle">Generar QR</div>
                <div style="font-size:.82rem;color:#6b7a8d;" id="exportModalDesc">Elige el formato de descarga</div>
            </div>
        </div>

        <div class="export-format-cards">
            <div class="export-card" id="cardPDF" onclick="seleccionarFormato('pdf')">
                <div class="export-card-icon" style="background:#fee2e2;">
                    <svg width="22" height="22" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
                </div>
                <div class="export-card-title">PDF</div>
                <div class="export-card-desc">Cuadrícula imprimible con todos los QR y nombres de activos</div>
            </div>
            <div class="export-card" id="cardZIP" onclick="seleccionarFormato('zip')">
                <div class="export-card-icon" style="background:#fef9c3;">
                    <svg width="22" height="22" fill="none" stroke="#a16207" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <div class="export-card-title">ZIP</div>
                <div class="export-card-desc">Imágenes PNG individuales por activo dentro de un archivo ZIP</div>
            </div>
        </div>

        <div class="export-progress" id="exportProgress">
            <div class="export-spinner"></div>
            <span id="exportProgressText">Generando...</span>
        </div>

        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;">
            <button class="btn-outline" style="padding:9px 18px;" onclick="cerrarExportModal()">Cancelar</button>
            <button class="btn-primary" style="padding:9px 18px;" id="btnDescargar" onclick="ejecutarExporte()" disabled>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar
            </button>
        </div>
    </div>
</div>

{{-- Modal QR --}}
<div class="qr-modal-overlay" id="qrModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="qr-modal-box">
        <div class="qr-modal-title" id="qrModalNombre"></div>
        <div class="qr-modal-code" id="qrModalCodigo"></div>
        <img id="qrModalImg" src="" alt="Código QR">
        <div style="display:flex;gap:8px;justify-content:center;margin-top:4px;">
            <a id="qrModalDownload" href="" download class="btn-primary" style="padding:9px 18px;font-size:.85rem;text-decoration:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar
            </a>
            <button class="btn-outline" style="padding:9px 18px;font-size:.85rem;" onclick="document.getElementById('qrModal').classList.remove('open')">Cerrar</button>
        </div>
    </div>
</div>

{{-- Lightbox foto --}}
<div class="lightbox-overlay" id="lightbox" onclick="if(event.target===this)cerrarLightbox()">
    <button class="lightbox-close" onclick="cerrarLightbox()">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img id="lightboxImg" src="" alt="Foto del activo">
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
            <button id="btnConfirmDelete" class="btn-primary" style="padding:9px 18px;background:#dc2626;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
const GOQR_URL = 'https://api.qrserver.com/v1/create-qr-code/';

// Datos completos de catálogos y activos reinyectados de la Capa API
const ACTIVOS_DATA = @json($activos);
const CATALOGOS = @json($catalogos);

// --- PANEL LATERAL ---
const BADGE_COLORS = { blue:'#dbeafe', yellow:'#fef9c3', green:'#dcfce7' };
const BADGE_TEXT   = { blue:'#1d4ed8', yellow:'#a16207', green:'#15803d' };

function abrirPanel(modo, idx) {
    const panel   = document.getElementById('panel');
    const overlay = document.getElementById('panelOverlay');
    const body    = document.getElementById('panelBody');
    const footer  = document.getElementById('panelFooter');
    const title   = document.getElementById('panelTitle');
    const iconWrap= document.getElementById('panelIconWrap');

    if (modo === 'nuevo') {
        fotoTempNuevo = null;
        title.textContent = 'Nuevo activo';
        iconWrap.style.background = '#dcfce7';
        iconWrap.innerHTML = `<svg width="16" height="16" fill="none" stroke="#15803d" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>`;
        body.innerHTML = renderFormNuevo();
        footer.innerHTML = `
            <button class="btn-outline" style="padding:9px 20px;" onclick="cerrarPanel()">Cancelar</button>
            <button class="btn-primary" style="padding:9px 20px;" onclick="document.getElementById('formNuevo').submit()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Guardar activo
            </button>`;
    } else if (modo === 'ver') {
        const a = ACTIVOS_DATA[idx];
        title.textContent = 'Detalle del activo';
        iconWrap.style.background = '#eff6ff';
        iconWrap.innerHTML = `<svg width="16" height="16" fill="none" stroke="#4a86b5" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
        body.innerHTML = renderVer(a);
        footer.innerHTML = `
            <button class="btn-outline" style="padding:9px 20px;" onclick="cerrarPanel()">Cerrar</button>
            <button class="btn-primary" style="padding:9px 20px;" onclick="abrirPanel('editar',${idx})">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </button>`;
    } else if (modo === 'editar') {
        fotoTempEdit = null;
        const a = ACTIVOS_DATA[idx];
        title.textContent = `Editar — ${a.codigo}`;
        iconWrap.style.background = '#eff6ff';
        iconWrap.innerHTML = `<svg width="16" height="16" fill="none" stroke="#4a86b5" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
        body.innerHTML = renderFormEditar(a);
        footer.innerHTML = `
            <button class="btn-outline" style="padding:9px 16px;color:#dc2626;border-color:#fecaca;" onclick="confirmarEliminar('${a.id}','${a.nombre}')">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                Eliminar
            </button>
            <div style="display:flex;gap:8px;margin-left:auto;">
                <button class="btn-outline" style="padding:9px 20px;" onclick="cerrarPanel()">Cancelar</button>
                <button class="btn-primary" style="padding:9px 20px;" onclick="document.getElementById('formEditar').submit()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Guardar cambios
                </button>
            </div>`;
    }

    overlay.classList.add('open');
    requestAnimationFrame(() => panel.classList.add('open'));
    document.body.style.overflow = 'hidden';
}

function cerrarPanel() {
    const panel = document.getElementById('panel');
    panel.classList.remove('open');
    setTimeout(() => {
        document.getElementById('panelOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }, 280);
}


// ─── CÓDIGO AUTOINCREMENTABLE ───────────────────────────────────────
function nextCodigo() {
    const nums = ACTIVOS_DATA.map(a => {
        const m = a.codigo.match(/^ACT-(\d+)$/);
        return m ? parseInt(m[1], 10) : 0;
    });
    const next = (nums.length ? Math.max(...nums) : 0) + 1;
    return 'ACT-' + String(next).padStart(3, '0');
}

// --- FOTO HANDLERS ---
let fotoTempNuevo = null;
let fotoTempEdit  = null;

function handleFotoNuevo(input) {
    const f = input.files[0];
    if (!f) return;
    const reader = new FileReader();
    reader.onload = e => {
        fotoTempNuevo = e.target.result;
        document.getElementById('fotoPreview').src = fotoTempNuevo;
        document.getElementById('fotoPreviewWrap').style.display = 'block';
        document.getElementById('fotoPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(f);
}

function handleFotoEdit(input) {
    const f = input.files[0];
    if (!f) return;
    const reader = new FileReader();
    reader.onload = e => {
        fotoTempEdit = e.target.result;
        document.getElementById('fotoPreviewEdit').src = fotoTempEdit;
        document.getElementById('fotoPreviewWrapEdit').style.display = 'block';
        document.getElementById('fotoPlaceholderEdit').style.display = 'none';
    };
    reader.readAsDataURL(f);
}

    // TODO: Las funciones locales de arrays se reemplazaron por llamadas HTTP mediante Laravel forms.
    // Solo manejaremos los eventos de click en los botones del Footer del panel que llaman al id del tag <form>.

function renderFormNuevo() {
    const optsCat = CATALOGOS.categorias.map(c => `<option value="${c.id}">${c.categoria}</option>`).join('');
    const optsUbi = CATALOGOS.ubicaciones.map(u => `<option value="${u.id}">${u.nombre} (${u.edificio})</option>`).join('');
    const optsUsu = CATALOGOS.usuarios.map(u => `<option value="${u.id}">${u.nombre} ${u.apellidos}</option>`).join('');

    return `
    <form id="formNuevo" method="POST" action="{{ route('activos.store') }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="pf-section">Información general</div>
        <div class="pf-row">
            <div><label class="pf-label">Nombre del activo *</label><input name="nombre" class="pf-field" type="text" placeholder="Ej. Escritorio" required autofocus></div>
            <div><label class="pf-label">Código</label><input class="pf-field" type="text" value="Autogenerado" readonly style="background:#f8fafc;color:#6b7a8d;cursor:not-allowed;"></div>
        </div>
        <div class="pf-row">
            <div><label class="pf-label">Categoría *</label>
                <select name="categoria_id" class="pf-field" onchange="const inp=this.nextElementSibling; if(this.value==='NEW'){inp.style.display='block';inp.focus()}else{inp.style.display='none';inp.value=''}">
                    <option value="">Selecciona...</option>${optsCat}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Agregar nueva...</option>
                </select>
                <input type="text" name="nueva_categoria" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre nueva categoría">
            </div>
            <div><label class="pf-label">Ubicación *</label>
                <select name="ubicacion_id" class="pf-field" onchange="const inp=this.nextElementSibling; if(this.value==='NEW'){inp.style.display='block';inp.focus()}else{inp.style.display='none';inp.value=''}">
                    <option value="">Selecciona...</option>${optsUbi}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Agregar nueva...</option>
                </select>
                <input type="text" name="nueva_ubicacion" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre nueva ubicación">
            </div>
        </div>
        <div class="pf-row">
            <div><label class="pf-label">Estado *</label>
                <select name="estado" class="pf-field">
                    <option value="funcional">Funcional</option>
                    <option value="mantenimiento">En mantenimiento</option>
                    <option value="baja">Baja</option>
                </select></div>
            <div><label class="pf-label">Usuario asignado</label>
                <select name="usuario_responsable_id" class="pf-field" onchange="const inp=this.nextElementSibling; if(this.value==='NEW'){inp.style.display='block';inp.focus()}else{inp.style.display='none';inp.value=''}">
                    <option value="">Sin asignar</option>${optsUsu}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Crear usuario rápido...</option>
                </select>
                <input type="text" name="nuevo_usuario_nombre" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre y Apellidos de nuevo usuario">
            </div>
        </div>
        <div class="pf-section" style="margin-top:8px;">Foto del activo</div>
        <div class="pf-group">
            <div id="fotoDropZone" onclick="document.getElementById('fotoInput').click()" style="border:2px dashed var(--border, #d1d5db);border-radius:12px;padding:24px 16px;text-align:center;cursor:pointer;background:var(--sk-bg, #fafafa);transition:border-color .2s;" onmouseenter="this.style.borderColor='#4a86b5'" onmouseleave="this.style.borderColor='var(--border, #d1d5db)'">
                <div id="fotoPreviewWrap" style="display:none;margin-bottom:8px;"><img id="fotoPreview" src="" style="max-height:160px;max-width:100%;border-radius:10px;object-fit:cover;"></div>
                <div id="fotoPlaceholder"><svg width="28" height="28" fill="none" stroke="#9ca3af" stroke-width="1.6" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><p style="font-size:.83rem;color:#6b7a8d;margin:0;">Haz clic para seleccionar una foto</p><p style="font-size:.75rem;color:#b0b8c1;margin:4px 0 0;">JPG, PNG o WEBP — máx. 5 MB</p></div>
            </div>
            <input name="foto" id="fotoInput" type="file" accept="image/*" style="display:none;" onchange="handleFotoNuevo(this)">
        </div>
        <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#eff6ff;border-radius:10px;margin-top:4px;">
            <svg width="15" height="15" fill="none" stroke="#4a86b5" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span style="font-size:.8rem;color:#4a86b5;font-weight:600;">El código QR se generará automáticamente.</span>
        </div>
        <div class="pf-group" style="margin-top:12px;"><label class="pf-label">Descripción</label><textarea name="descripcion" class="pf-field" rows="3" placeholder="Observaciones del activo..."></textarea></div>
    </form>`;
}

function renderVer(a) {
    const bg   = BADGE_COLORS[a.color] || '#f0f2f5';
    const fg   = BADGE_TEXT[a.color]   || '#374151';
    const initials = a.usuario !== '—' ? a.usuario.split(' ').map(w=>w[0]).join('').substring(0,2).toUpperCase() : '—';
    return `
    <div style="width:100%;height:180px;border-radius:14px;background:var(--sk-bg, #f0f4f8);overflow:hidden;margin-bottom:16px;display:flex;align-items:center;justify-content:center;position:relative;">
        ${a.foto
            ? `<img src="${a.foto}" style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;" onclick="abrirLightbox('${a.foto}')" title="Ver imagen completa">
               <div style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,.45);border-radius:7px;padding:4px 8px;display:flex;align-items:center;gap:4px;pointer-events:none;">
                   <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                   <span style="font-size:.7rem;color:#fff;font-weight:600;">Ampliar</span>
               </div>`
            : `<div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                <svg width="36" height="36" fill="none" stroke="#c7d0db" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <span style="font-size:.78rem;color:#b0b8c1;">Sin foto disponible</span>
               </div>`
        }
    </div>
    <div style="display:flex;align-items:center;gap:14px;padding:14px 16px;background:var(--sk-bg, #f8fafc);border-radius:12px;margin-bottom:20px;">
        <div style="flex:1;">
            <div style="font-family:'Sora',sans-serif;font-size:1rem;font-weight:800;color:var(--text-primary, #0f1f35);">${a.nombre}</div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <span style="font-family:'DM Mono',monospace;font-size:.78rem;color:#9ca3af;">${a.codigo}</span>
                <span style="background:${bg};color:${fg};font-size:.75rem;font-weight:700;padding:2px 10px;border-radius:20px;">${a.estado}</span>
            </div>
        </div>
    </div>
    <div class="pf-section">Información general</div>
    <div class="pf-row">
        <div><div class="detail-label">Categoría</div><div class="detail-value">${a.categoria}</div></div>
        <div><div class="detail-label">Ubicación</div><div class="detail-value">${a.ubicacion}</div></div>
    </div>
    <div class="pf-row" style="margin-top:8px;">
        <div>
            <div class="detail-label">Usuario asignado</div>
            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                ${a.usuario !== '—' ? `<div style="width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#4a86b5,#2d5a8e);display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#fff;">${initials}</div>` : ''}
                <span class="detail-value">${a.usuario}</span>
            </div>
        </div>
        <div><div class="detail-label">Estado</div><div class="detail-value" style="margin-top:4px;"><span style="background:${bg};color:${fg};font-size:.8rem;font-weight:700;padding:3px 12px;border-radius:20px;">${a.estado}</span></div></div>
    </div>
    <div class="pf-section" style="margin-top:16px;">Código QR</div>
    <div style="display:flex;align-items:center;gap:16px;">
        <img src="${GOQR_URL}?size=100x100&data=${encodeURIComponent(a.codigo)}&format=png" style="width:100px;height:100px;border-radius:10px;border:1px solid #e4e8ef;">
        <div>
            <p style="font-size:.82rem;color:#6b7a8d;margin-bottom:10px;">QR para identificar el activo físicamente.</p>
            <a href="${GOQR_URL}?size=300x300&data=${encodeURIComponent(a.codigo)}&format=png" download="QR_${a.codigo}.png"
               style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:#eff6ff;color:#4a86b5;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar PNG
            </a>
        </div>
    </div>
    
    <div style="margin-top:24px;padding-top:16px;border-top:1px solid #e4e8ef;display:flex;justify-content:flex-end;">
        <button onclick="confirmarEliminar('${a.id}', '${a.nombre}')" style="background:#fee2e2;color:#ef4444;border:none;padding:8px 16px;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;display:flex;align-items:center;gap:6px;transition:background 0.2s;" onmouseenter="this.style.background='#fecaca'" onmouseleave="this.style.background='#fee2e2'" title="Eliminar este activo">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
            Eliminar Activo
        </button>
    </div>`;
}

function renderFormEditar(a) {
    const optsCat = CATALOGOS.categorias.map(c => `<option value="${c.id}" ${c.categoria===a.categoria?'selected':''}>${c.categoria}</option>`).join('');
    const optsUbi = CATALOGOS.ubicaciones.map(u => `<option value="${u.id}" ${u.nombre===a.ubicacion?'selected':''}>${u.nombre} (${u.edificio})</option>`).join('');
    const optsUsu = CATALOGOS.usuarios.map(u => {
        const fullName = `${u.nombre} ${u.apellidos}`;
        return `<option value="${u.id}" ${fullName===a.usuario?'selected':''}>${fullName}</option>`;
    }).join('');

    const st = (a.estado || '').toLowerCase();
    const tieneFoto = !!a.foto;

    return `
    <form id="formEditar" method="POST" action="/activos/${a.id}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <div class="pf-section">Información general</div>
        <div class="pf-row">
            <div><label class="pf-label">Código</label><input class="pf-field" type="text" value="${a.codigo}" readonly style="cursor:not-allowed;"></div>
            <div><label class="pf-label">Nombre *</label><input name="nombre" class="pf-field" type="text" value="${a.nombre}" required></div>
        </div>
        <div class="pf-row">
            <div><label class="pf-label">Categoría *</label>
                <select name="categoria_id" class="pf-field" onchange="if(this.value==='NEW'){this.nextElementSibling.style.display='block';this.nextElementSibling.name='categoria_id';this.name='';this.nextElementSibling.focus()}else{this.nextElementSibling.style.display='none';this.nextElementSibling.name='';this.name='categoria_id'}">
                    ${optsCat}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Agregar nueva...</option>
                </select>
                <input type="text" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre nueva categoría">
            </div>
            <div><label class="pf-label">Ubicación *</label>
                <select name="ubicacion_id" class="pf-field" onchange="if(this.value==='NEW'){this.nextElementSibling.style.display='block';this.nextElementSibling.name='ubicacion_id';this.name='';this.nextElementSibling.focus()}else{this.nextElementSibling.style.display='none';this.nextElementSibling.name='';this.name='ubicacion_id'}">
                    ${optsUbi}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Agregar nueva...</option>
                </select>
                <input type="text" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre nueva ubicación">
            </div>
        </div>
        <div class="pf-row">
            <div><label class="pf-label">Estado *</label>
                <select name="estado" class="pf-field">
                    <option value="funcional"     ${st==='funcional'?'selected':''}>Funcional</option>
                    <option value="mantenimiento" ${st==='mantenimiento'||st==='en mantenimiento'?'selected':''}>En mantenimiento</option>
                    <option value="baja"          ${st==='baja'?'selected':''}>Baja</option>
                </select></div>
            <div><label class="pf-label">Usuario asignado</label>
                <select name="usuario_responsable_id" class="pf-field" onchange="if(this.value==='NEW'){this.nextElementSibling.style.display='block';this.nextElementSibling.name='usuario_responsable_id';this.name='';this.nextElementSibling.focus()}else{this.nextElementSibling.style.display='none';this.nextElementSibling.name='';this.name='usuario_responsable_id'}">
                    <option value="">Sin asignar</option>${optsUsu}
                    <option value="NEW" style="font-weight:bold;color:#4a86b5;">+ Cambiar a nuevo...</option>
                </select>
                <input type="text" style="display:none;margin-top:6px;" class="pf-field" placeholder="Nombre y Apellidos">
            </div>
        </div>
        <div class="pf-section" style="margin-top:8px;">Foto del activo</div>
        <div class="pf-group">
            <div id="fotoDropZoneEdit" onclick="document.getElementById('fotoInputEdit').click()" style="border:2px dashed var(--border, #d1d5db);border-radius:12px;padding:${tieneFoto?'10px':'24px'} 16px;text-align:center;cursor:pointer;background:var(--sk-bg, #fafafa);transition:border-color .2s;" onmouseenter="this.style.borderColor='#4a86b5'" onmouseleave="this.style.borderColor='var(--border, #d1d5db)'">
                <div id="fotoPreviewWrapEdit" style="display:${tieneFoto?'block':'none'};margin-bottom:8px;"><img id="fotoPreviewEdit" src="${a.foto||''}" style="max-height:160px;max-width:100%;border-radius:10px;object-fit:cover;"></div>
                <div id="fotoPlaceholderEdit" style="display:${tieneFoto?'none':'block'};"><svg width="28" height="28" fill="none" stroke="#9ca3af" stroke-width="1.6" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><p style="font-size:.83rem;color:#6b7a8d;margin:0;">Haz clic para cambiar la foto</p></div>
            </div>
            <input name="foto" id="fotoInputEdit" type="file" accept="image/*" style="display:none;" onchange="handleFotoEdit(this)">
        </div>
        <div class="pf-group" style="margin-top:12px;"><label class="pf-label">Descripción</label><textarea name="descripcion" class="pf-field" rows="3" placeholder="Observaciones..."></textarea></div>
    </form>`;
}

// Cerrar panel con Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarPanel(); });

// --- EXPORTAR QR MASIVO ---
let exportarModo = 'todos';   // 'todos' | 'seleccionados'
let formatoSeleccionado = null;

function abrirExportarQR(modo) {
    exportarModo = modo;
    formatoSeleccionado = null;
    document.getElementById('cardPDF').classList.remove('selected');
    document.getElementById('cardZIP').classList.remove('selected');
    document.getElementById('btnDescargar').disabled = true;
    document.getElementById('exportProgress').classList.remove('visible');

    const esSeleccion = modo === 'seleccionados';
    const n = esSeleccion
        ? document.querySelectorAll('.row-check:checked').length
        : ACTIVOS_DATA.length;

    document.getElementById('exportModalTitle').textContent = esSeleccion
        ? `Generar QR — ${n} activo${n !== 1 ? 's' : ''} seleccionado${n !== 1 ? 's' : ''}`
        : `Generar QR — todos los activos (${n})`;
    document.getElementById('exportModalDesc').textContent = 'Elige el formato de descarga';
    document.getElementById('exportModal').classList.add('open');
}

function cerrarExportModal() {
    document.getElementById('exportModal').classList.remove('open');
}

function seleccionarFormato(fmt) {
    formatoSeleccionado = fmt;
    document.getElementById('cardPDF').classList.toggle('selected', fmt === 'pdf');
    document.getElementById('cardZIP').classList.toggle('selected', fmt === 'zip');
    document.getElementById('btnDescargar').disabled = false;
}

function getActivosParaExportar() {
    if (exportarModo === 'seleccionados') {
        const codigos = new Set();
        document.querySelectorAll('.row-check:checked').forEach(chk => {
            const row = chk.closest('tr');
            codigos.add(row.cells[1].textContent.trim());
        });
        return ACTIVOS_DATA.filter(a => codigos.has(a.codigo));
    }
    return ACTIVOS_DATA;
}

async function ejecutarExporte() {
    if (!formatoSeleccionado) return;
    const activos = getActivosParaExportar();
    const btn = document.getElementById('btnDescargar');
    btn.disabled = true;
    if (formatoSeleccionado === 'pdf') {
        generarPDF(activos);
        cerrarExportModal();
        btn.disabled = false;
    } else {
        await generarZIP(activos);
        cerrarExportModal();
        btn.disabled = false;
    }
}

function generarPDF(activos) {
    const win = window.open('', '_blank');
    const items = activos.map(a => `
        <div class="qr-item">
            <img src="${GOQR_URL}?size=160x160&data=${encodeURIComponent(a.codigo)}&format=png" alt="${a.codigo}">
            <div class="qr-nombre">${a.nombre}</div>
            <div class="qr-codigo">${a.codigo}</div>
        </div>`).join('');

    win.document.write(`<!DOCTYPE html><html><head>
        <meta charset="UTF-8">
        <title>Códigos QR — Activos SGAFA</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 24px; color: #0f1f35; }
            h1 { font-size: 1.1rem; margin-bottom: 4px; }
            p  { font-size: .8rem; color: #6b7a8d; margin-bottom: 20px; }
            .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
            .qr-item { border: 1px solid #e4e8ef; border-radius: 10px; padding: 14px; text-align: center; break-inside: avoid; }
            .qr-item img { width: 120px; height: 120px; display: block; margin: 0 auto 8px; }
            .qr-nombre { font-size: .8rem; font-weight: 700; margin-bottom: 2px; }
            .qr-codigo { font-size: .72rem; color: #4a86b5; font-family: monospace; }
            @media print { @page { margin: 16mm; } }
        </style>
    </head><body>
        <h1>Códigos QR — Activos SGAFA</h1>
        <p>Generado el ${new Date().toLocaleDateString('es-PE', {day:'2-digit',month:'long',year:'numeric'})} · ${activos.length} activo${activos.length !== 1 ? 's' : ''}</p>
        <div class="grid">${items}</div>
        <script>window.onload=()=>{ window.print(); }<\/script>
    </body></html>`);
    win.document.close();
}

async function generarZIP(activos) {
    const progress = document.getElementById('exportProgress');
    const progressText = document.getElementById('exportProgressText');
    progress.classList.add('visible');

    const zip = new JSZip();
    const folder = zip.folder('QR_Activos');

    for (let i = 0; i < activos.length; i++) {
        const a = activos[i];
        progressText.textContent = `Descargando ${i + 1} de ${activos.length}: ${a.codigo}`;
        try {
            const url = `${GOQR_URL}?size=300x300&data=${encodeURIComponent(a.codigo)}&format=png`;
            const res = await fetch(url);
            const blob = await res.blob();
            const fileName = `QR_${a.codigo}_${a.nombre.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]/g,'')}.png`;
            folder.file(fileName, blob);
        } catch(e) {
            console.warn(`Error al obtener QR de ${a.codigo}:`, e);
        }
    }

    progressText.textContent = 'Comprimiendo archivo...';
    const content = await zip.generateAsync({ type: 'blob' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(content);
    link.download = 'QR_Activos_SGAFA.zip';
    link.click();
    URL.revokeObjectURL(link.href);
    progress.classList.remove('visible');
}

function mostrarQR(codigo, nombre) {
    const url = `${GOQR_URL}?size=200x200&data=${encodeURIComponent(codigo)}&format=png`;
    document.getElementById('qrModalNombre').textContent = nombre;
    document.getElementById('qrModalCodigo').textContent = codigo;
    document.getElementById('qrModalImg').src = url;
    document.getElementById('qrModalDownload').href = `${GOQR_URL}?size=300x300&data=${encodeURIComponent(codigo)}&format=png`;
    document.getElementById('qrModalDownload').download = `QR_${codigo}.png`;
    document.getElementById('qrModal').classList.add('open');
}

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
function confirmarEliminar(id, nombre) {
    document.getElementById('modalDesc').textContent=`Estás a punto de eliminar "${nombre}". Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.add('open');
    document.getElementById('btnConfirmDelete').onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/activos/' + id;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
    };
}
function abrirLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
}
function cerrarLightbox() {
    document.getElementById('lightbox').classList.remove('open');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });

function cerrarModal(e){
    if(e.target===document.getElementById('deleteModal'))
        document.getElementById('deleteModal').classList.remove('open');
}
function cerrarModalBtn(){
    document.getElementById('deleteModal').classList.remove('open');
}
</script>
@endpush