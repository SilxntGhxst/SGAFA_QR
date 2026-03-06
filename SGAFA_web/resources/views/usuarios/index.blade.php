@extends('layouts.app')
@section('title','Usuarios')
@section('page-title','Usuarios')

@section('topbar-actions')
<a href="/usuarios/crear" class="btn-primary">
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
                ['Hannia Gonzales','hannia.gonzales@empresa.com','Auditor','purple','+51 912 345 678','Activo',true],
                ['Carlos Ruiz','carlos.ruiz@empresa.com','Resguardante','orange','+51 934 567 890','Inactivo',false],
                ['María González','maria.gonzalez@empresa.com','Resguardante','orange','+51 900 123 456','Activo',true],
                ['Ruth Piña','ruth.pina@empresa.com','Soporte TI','teal','+51 923 456 789','Activo',true],
                ['Andre Martinez','andre.martinez@empresa.com','Auditor','purple','+51 956 789 123','Activo',true],
                ['Valeria Briones','valeria.briones@empresa.com','Resguardante','orange','+51 980 654 321','Inactivo',false],
                ['Santiago Meneses','santiago.meneses@empresa.com','Administrador','blue','+51 995 321 654','Activo',true],
                ['Diego Ramirez','diego.ramirez@empresa.com','Auditor','purple','+51 902 111 222','Activo',true],
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
                        <a href="/usuarios/{{ $i+1 }}/edit" class="action-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <a href="/usuarios/{{ $i+1 }}" class="action-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="#" class="action-btn">
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
@endsection
