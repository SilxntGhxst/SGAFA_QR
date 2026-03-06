@extends('layouts.app')
@section('title','Buzón de Discrepancia')
@section('page-title','Solicitud/Buzón de Discrepancia')

@push('styles')
<style>
.page-subtitle { font-size:.88rem; color:#6b7a8d; margin-top:-18px; margin-bottom:0; font-weight:500; }
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
            @foreach($buzon as $b)
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
                    <a href="/solicitudes/buzon/1" class="action-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                    <a href="/solicitudes/buzon/1/edit" class="action-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>
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
@endsection
