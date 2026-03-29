@extends('layouts.app')
@section('title', 'Ajustes del Sistema')
@section('page-title', 'Ajustes del Sistema')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto; padding: 32px;">
    <h2 style="font-size: 1.5rem; color: #1e40af; margin-bottom: 24px;">Configuración Global</h2>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('ajustes.guardar') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 32px;">
            <h3 style="font-size: 1.1rem; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; margin-bottom: 16px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align: middle; margin-right: 8px;">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Servidor de Correo (SMTP)
            </h3>
            <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 20px;">
                Configura la cuenta que el sistema usará para enviar correos de recuperación de contraseña a todos los usuarios.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label class="form-label">Servidor SMTP</label>
                    <input type="text" name="mail_server" value="{{ $ajustes['Smtp'][0]['valor'] ?? 'smtp.gmail.com' }}" class="form-input" placeholder="smtp.gmail.com">
                </div>
                <div>
                    <label class="form-label">Puerto</label>
                    <input type="number" name="mail_port" value="{{ $ajustes['Smtp'][1]['valor'] ?? '587' }}" class="form-input" placeholder="587">
                </div>
                <div>
                    <label class="form-label">Correo Emisor (Username)</label>
                    <input type="email" name="mail_username" value="{{ $ajustes['Smtp'][2]['valor'] ?? '' }}" class="form-input" placeholder="sistema@gmail.com">
                </div>
                <div>
                    <label class="form-label">Contraseña / Token</label>
                    <input type="password" name="mail_password" value="{{ $ajustes['Smtp'][3]['valor'] ?? '' }}" class="form-input" placeholder="••••••••••••">
                </div>
                <div>
                    <label class="form-label">Nombre del Remitente</label>
                    <input type="text" name="mail_from_name" value="{{ $ajustes['Smtp'][4]['valor'] ?? 'SGAFA QR Sistema' }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Correo de Respuesta (From)</label>
                    <input type="email" name="mail_from" value="{{ $ajustes['Smtp'][5]['valor'] ?? '' }}" class="form-input">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e5e7eb; padding-top: 24px;">
            <button type="reset" class="btn-secondary" style="background: #f3f4f6; color: #374151;">Cancelar</button>
            <button type="submit" class="btn-primary" style="padding: 12px 32px;">Guardar Configuración</button>
        </div>
    </form>
</div>

<style>
    .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
    .form-input { width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid #d1d5db; background: #fff; outline: none; transition: border-color 0.2s; }
    .form-input:focus { border-color: #1e40af; }
</style>
@endsection
