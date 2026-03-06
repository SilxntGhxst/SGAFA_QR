@extends('layouts.app')
@section('title','Reportes')
@section('page-title','Reportes')

@section('content')
{{-- Filtros --}}
<div class="card" style="margin-bottom:20px;padding:20px 24px;">
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:16px;align-items:end;flex-wrap:wrap;">
        <div>
            <label style="font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Fecha de inicio</label>
            <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f7f7f7;border:1.5px solid #e4e8ef;border-radius:10px;">
                <input type="date" value="2026-02-01" style="border:none;background:transparent;font-family:'DM Sans',sans-serif;font-size:.9rem;color:#374151;outline:none;width:100%;">
            </div>
        </div>
        <div>
            <label style="font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Fecha de fin</label>
            <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:#f7f7f7;border:1.5px solid #e4e8ef;border-radius:10px;">
                <input type="date" value="2026-02-28" style="border:none;background:transparent;font-family:'DM Sans',sans-serif;font-size:.9rem;color:#374151;outline:none;width:100%;">
            </div>
        </div>
        <div>
            <label style="font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Área</label>
            <div class="filter-select" style="padding:10px 14px;border-radius:10px;border:1.5px solid #e4e8ef;background:#f7f7f7;width:100%;">
                Todas
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left:auto;"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
        </div>
        <div style="grid-column:1/-1;display:flex;align-items:end;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div style="flex:1;max-width:360px;">
                <label style="font-size:.78rem;font-weight:600;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Tipo de reporte</label>
                <div class="filter-select" style="padding:10px 14px;border-radius:10px;border:1.5px solid #e4e8ef;background:#f7f7f7;">
                    Activos por estado
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-left:auto;"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <button class="btn-primary" style="padding:11px 22px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Generar y Descargar PDF
            </button>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Fecha de creación</th>
            </tr>
        </thead>
        <tbody>
            @php $rep=[
                ['ACT-001','Escritorio','Mobiliario','Oficina','Asignado','blue',true,'10/02/2026'],
                ['ACT-002','Ventilador','Equipos','Almacén','Disponible','green',false,'28/02/2026'],
                ['ACT-003','Impresora HP Laser','Equipos','Sala IT','Mantenimiento','yellow',false,'03/02/2026'],
                ['ACT-004','Laptop Lenovo','Equipos','Oficina 204','Prestado','purple',false,'05/02/2026'],
                ['ACT-005','Dispensador de agua','Equipos','Oficina','Prestado','purple',false,'05/02/2026'],
                ['ACT-006','Impresora Canon Color','Equipos','Laboratorio 3','Disponible','green',false,'05/02/2026'],
                ['ACT-007','Proyector Epson','Audio-Video','Oficina','Prestado','red',false,'05/02/2026'],
                ['ACT-010','Televisión Samsung 55"','Audio-Video','Laboratorio 3','Obsoleto','gray',false,'04/02/2026'],
            ]; @endphp
            @foreach($rep as $r)
            <tr>
                <td><input type="checkbox" {{ $r[6]?'checked':'' }}></td>
                <td style="font-weight:700;font-size:.85rem;color:#4a86b5;">{{ $r[0] }}</td>
                <td style="font-weight:600;">{{ $r[1] }}</td>
                <td style="font-size:.88rem;color:#6b7a8d;">{{ $r[2] }}</td>
                <td style="font-size:.88rem;">{{ $r[3] }}</td>
                <td><span class="badge badge-{{ $r[5] }} badge-dot">{{ $r[4] }}</span></td>
                <td style="font-size:.85rem;color:#6b7a8d;">{{ $r[7] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;">
        <span style="font-size:.85rem;color:#6b7a8d;">Mostrando 1–10 de 34 activos</span>
        <div class="pagination">
            <a href="#" class="page-btn">‹</a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">2</a>
            <a href="#" class="page-btn">3</a>
            <a href="#" class="page-btn">›</a>
        </div>
    </div>
</div>
@endsection
