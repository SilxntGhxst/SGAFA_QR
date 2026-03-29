@extends('layouts.app')
@section('title','Reportes')
@section('page-title','Reportes')

@section('content')
{{-- Filtros y Selección de Reporte --}}
<div class="card" style="margin-bottom:20px;padding:20px 24px;">
    <form action="{{ route('reportes.descargar') }}" method="GET" id="reportForm">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;align-items:end;">
            
            <div>
                <label class="form-label-mini">Tipo de Reporte</label>
                <select name="tipo" id="tipoReporte" class="filter-select-input" onchange="actualizarInterfaz()">
                    <option value="activos" selected>Inventario de Activos</option>
                    <option value="auditorias">Resumen de Auditorías</option>
                    <option value="discrepancias">Buzón de Discrepancias</option>
                </select>
            </div>

            <div class="filter-group activos-only">
                <label class="form-label-mini">Categoría</label>
                <select name="categoria_id" class="filter-select-input">
                    <option value="">Todas</option>
                    @foreach($catalogos['categorias'] as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['categoria'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group activos-only">
                <label class="form-label-mini">Ubicación</label>
                <select name="ubicacion_id" class="filter-select-input">
                    <option value="">Todas</option>
                    @foreach($catalogos['ubicaciones'] as $ubi)
                        <option value="{{ $ubi['id'] }}">{{ $ubi['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="button" onclick="cargarVistaPrevia()" class="btn-secondary" style="flex:1;">
                    Previsualizar
                </button>
                <button type="submit" class="btn-primary" style="flex:1;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Descargar PDF
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Tabla de Vista Previa --}}
<div class="card">
    <div id="loadingState" style="display:none;padding:40px;text-align:center;color:#6b7280;">
        Cargando datos...
    </div>
    <div id="tableContainer">
        <table class="data-table" id="previewTable">
            <thead id="tableHead">
                {{-- Se llena con JS --}}
            </thead>
            <tbody id="tableBody">
                <tr id="emptyRow">
                    <td colspan="7" style="text-align:center;padding:40px;color:#9ca3af;">
                        Haz clic en "Previsualizar" para ver los datos antes de descargar el PDF
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    .form-label-mini { font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px; }
    .filter-select-input { padding:10px 14px;border-radius:10px;border:1.5px solid #e4e8ef;background:#f7f7f7;width:100%;outline:none;font-family:inherit; }
    .btn-secondary { display:flex;align-items:center;justify-content:center;gap:8px;padding:11px 22px;border-radius:12px;font-weight:700;font-size:.9rem;background:#f0f4f8;color:#1e40af;border:none;cursor:pointer;transition:all .2s; }
    .btn-secondary:hover { background:#e1e7f0; }
</style>

<script>
    function actualizarInterfaz() {
        const tipo = document.getElementById('tipoReporte').value;
        const activosFilters = document.querySelectorAll('.activos-only');
        
        activosFilters.forEach(el => {
            el.style.display = (tipo === 'activos') ? 'block' : 'none';
        });
    }

    async function cargarVistaPrevia() {
        const form = document.getElementById('reportForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const tipo = formData.get('tipo');

        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('tableContainer').style.opacity = '0.4';

        try {
            // Llamamos al proxy de Laravel en lugar de llamar directo a la API (evita CORS y fallos de red)
            const response = await fetch("{{ route('reportes.preview') }}?" + params.toString());
            const result = await response.json();
            renderTable(tipo, result.data);

        } catch (e) {
            console.error(e);
            alert("Error al cargar la vista previa.");
        } finally {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('tableContainer').style.opacity = '1';
        }
    }

    function renderTable(tipo, data) {
        const head = document.getElementById('tableHead');
        const body = document.getElementById('tableBody');
        head.innerHTML = "";
        body.innerHTML = "";

        if (data.length === 0) {
            body.innerHTML = '<tr><td colspan="10" style="text-align:center;padding:40px;">No se encontraron registros.</td></tr>';
            return;
        }

        let headers = [];
        if (tipo === 'activos') {
            headers = ['Código', 'Nombre', 'Categoría', 'Ubicación', 'Estado', 'Responsable'];
            head.innerHTML = `<tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
            data.forEach(item => {
                body.innerHTML += `
                    <tr>
                        <td style="font-weight:700;color:#4a86b5;">${item.codigo}</td>
                        <td style="font-weight:600;">${item.nombre}</td>
                        <td>${item.categoria}</td>
                        <td>${item.ubicacion}</td>
                        <td><span class="badge badge-dot">${item.estado}</span></td>
                        <td>${item.responsable}</td>
                    </tr>`;
            });
        } else if (tipo === 'auditorias') {
            headers = ['Folio', 'Ubicación', 'Fecha', 'Progreso', 'Estado'];
            head.innerHTML = `<tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
            data.forEach(item => {
                body.innerHTML += `
                    <tr>
                        <td style="font-weight:700;">${item.folio}</td>
                        <td>${item.ubicacion}</td>
                        <td>${item.fecha}</td>
                        <td>${item.progreso}</td>
                        <td>${item.estado}</td>
                    </tr>`;
            });
        } else {
            headers = ['Activo', 'Tipo', 'Área', 'Estado', 'Fecha'];
            head.innerHTML = `<tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr>`;
            data.forEach(item => {
                body.innerHTML += `
                    <tr>
                        <td style="font-weight:600;">${item.activo_nombre}</td>
                        <td>${item.tipo_dano}</td>
                        <td>${item.area}</td>
                        <td>${item.estado}</td>
                        <td>${item.fecha}</td>
                    </tr>`;
            });
        }
    }

    // Inicializar
    actualizarInterfaz();
</script>
@endsection
