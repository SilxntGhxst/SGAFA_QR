<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — S.G.A.F.A QR</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #ebebeb; margin: 0; }
        .card { width: 100%; max-width: 420px; background: #fff; border-radius: 28px; padding: 3rem; box-shadow: 0 40px 80px rgba(0,0,0,.06); text-align: center; }
        .title { font-family: 'Sora', sans-serif; font-size: 1.8rem; font-weight: 800; color: #0f1f35; margin-bottom: 0.5rem; }
        .sub { color: #6b7a8d; font-size: 0.95rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1.5rem; position: relative; text-align: left; }
        .form-input { width: 100%; padding: 1.1rem; background: #f7f7f7; border: 1.5px solid #e8e8e8; border-radius: 14px; font-size: 0.95rem; outline: none; }
        .btn-submit { width: 100%; padding: 1.1rem; background: linear-gradient(135deg, #4a86b5, #3a6e9f); color: #fff; font-weight: 700; border: none; border-radius: 14px; cursor: pointer; box-shadow: 0 4px 20px rgba(74,134,181,.35); margin-top: 1rem; }
        .back-link { margin-top: 2rem; display: block; color: #9ca3af; text-decoration: none; font-size: 0.85rem; }
        .alert { padding: 12px; border-radius: 10px; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="title">Recuperar acceso</h2>
        <p class="sub">Ingresa tu correo para recibir un código de seguridad de 6 dígitos.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            <p style="font-size: 0.85rem; margin-bottom: 1rem;">¿Recibiste el código? <a href="{{ route('password.reset.view', ['email' => session('email')]) }}" style="color: #4a86b5; font-weight: bold;">Ingrésalo aquí</a></p>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.forgot.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-input" placeholder="tu-correo@ejemplo.com" required>
            </div>
            <button type="submit" class="btn-submit">Enviar código</button>
        </form>

        <a href="/login" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>
