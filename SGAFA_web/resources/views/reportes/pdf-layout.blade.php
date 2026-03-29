<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #1e40af; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #1e40af; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f3f4f6; padding: 10px; text-align: left; border: 1px solid #ddd; color: #1e40af; }
        td { padding: 8px; border: 1px solid #ddd; vertical-align: top; }
        .status { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .status-funcional { background: #d1fae5; color: #065f46; }
        .status-mantenimiento { background: #fef3c7; color: #92400e; }
        .status-baja { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SGAFA QR - SISTEMA DE GESTIÓN</div>
        <div style="font-size: 16px; margin-top: 5px;">{{ $title }}</div>
        <div style="font-size: 10px; margin-top: 5px; color: #666;">Fecha de generación: {{ $fecha }}</div>
    </div>

    @yield('content')

    <div class="footer">
        Este documento es un reporte oficial generado por SGAFA QR. Página <script type="text/php">echo $PAGE_NUM . " de " . $PAGE_COUNT;</script>
    </div>
</body>
</html>
