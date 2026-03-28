@extends('layouts.app')

@section('content')
<div class="page-container">
    <div class="page-header">
        <div class="header-left">
            <h1 class="page-title">Centro de Notificaciones</h1>
            <p class="page-subtitle">Revisa todos tus avisos, solicitudes y alertas de mantenimiento.</p>
        </div>
        <div class="header-actions">
            <form action="{{ route('notificaciones.todasLeidas') }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    Marcar todas como leídas
                </button>
            </form>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        @if($notificaciones->isEmpty())
            <div style="padding: 60px 20px; text-align: center; color: #9ca3af;">
                <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 16px;">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 style="color: #4b5563; font-size: 1.1rem; margin-bottom: 4px;">No hay notificaciones</h3>
                <p>Te avisaremos cuando ocurra algo importante.</p>
            </div>
        @else
            <div class="notif-full-list">
                @foreach($notificaciones as $n)
                    <div class="notif-full-item {{ $n->leida ? '' : 'no-leida' }}">
                        <div class="notif-icon {{ $n->color }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                @if($n->icono == 'warning')
                                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                @elseif($n->icono == 'check')
                                    <polyline points="20 6 9 17 4 12"/>
                                @elseif($n->icono == 'info')
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                @else
                                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                @endif
                            </svg>
                        </div>
                        <div class="notif-body">
                            <div class="notif-row">
                                <span class="notif-type">{{ strtoupper($n->tipo) }}</span>
                                <span class="notif-date">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="notif-title">{{ $n->titulo }}</h4>
                            <p class="notif-text">{{ $n->mensaje }}</p>
                            
                            @if($n->url)
                                <a href="{{ $n->url }}" class="notif-link">Ver detalles →</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="pagination-footer">
                {{ $notificaciones->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .notif-full-list {
        display: flex;
        flex-direction: column;
    }
    .notif-full-item {
        display: flex;
        gap: 16px;
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }
    .notif-full-item:hover { background: #f8fafc; }
    .notif-full-item.no-leida { background: #f0f7ff; }
    .notif-full-item.no-leida:hover { background: #e0f0ff; }

    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notif-icon.blue   { background: #eff6ff; color: #3b82f6; }
    .notif-icon.red    { background: #fef2f2; color: #ef4444; }
    .notif-icon.green  { background: #f0fdf4; color: #22c55e; }
    .notif-icon.orange { background: #fff7ed; color: #f97316; }

    .notif-body { flex: 1; }
    .notif-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .notif-type { font-size: 0.70rem; font-weight: 700; color: #64748b; letter-spacing: 0.05em; }
    .notif-date { font-size: 0.75rem; color: #94a3b8; }
    .notif-title { font-size: 1rem; font-weight: 600; color: #1e293b; margin: 0 0 4px 0; }
    .notif-text { font-size: 0.9rem; color: #475569; margin: 0 0 12px 0; line-height: 1.5; }
    .notif-link { font-size: 0.85rem; font-weight: 600; color: #3b82f6; text-decoration: none; }
    .notif-link:hover { text-decoration: underline; }

    .pagination-footer { padding: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; }
</style>
@endsection
