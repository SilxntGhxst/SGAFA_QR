@extends('layouts.app')
@section('title','Solicitudes / Movimientos Administrativos')
@section('page-title','Solicitud/Movimientos Administrativos')

@section('topbar-actions')
<a href="/solicitudes/nueva" class="btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nueva auditoria
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
                <th>Código</th>
                <th>Solicitante</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @php $sols=[
                ['SOL-025','Santiago Meneses','Asignación','19 Feb 2026','Pendiente','yellow'],
                ['SOL-026','Carlos Chavez','Traslado','18 Feb 2026','Aprobado','green'],
                ['SOL-027','Ruth Piña','Mantenimiento','17 Feb 2026','Aprobado','teal'],
                ['SOL-028','Andre Martinez','Asignación','17 Feb 2026','Rechazado','red'],
                ['SOL-029','Varia Gonzalez','Asignación','16 Feb 2026','En proceso','blue'],
                ['SOL-030','Yaleria Brionez','Devolución','16 Feb 2026','Aprobado','green'],
            ]; @endphp
            @foreach($sols as $s)
            <tr>
                <td style="font-weight:700;font-size:.88rem;">{{ $s[0] }}</td>
                <td style="font-size:.88rem;">{{ $s[1] }}</td>
                <td style="font-weight:600;">{{ $s[2] }}</td>
                <td style="font-size:.85rem;color:#6b7a8d;">{{ $s[3] }}</td>
                <td><span class="badge badge-{{ $s[5] }}">{{ $s[4] }}</span></td>
                <td>
                    <a href="/solicitudes/1" class="action-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f0f2f5;">
        <span style="font-size:.85rem;color:#6b7a8d;">1 – 5 of 18</span>
        <div class="pagination">
            <a href="#" class="page-btn">‹</a>
            <a href="#" class="page-btn">4</a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">›</a>
        </div>
    </div>
</div>
@endsection
