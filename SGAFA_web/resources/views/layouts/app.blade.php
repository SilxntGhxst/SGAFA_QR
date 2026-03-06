<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SGAFA QR') — S.G.A.F.A QR</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

    {{-- Vite (Tailwind v4 + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Estilos propios del sistema SGAFA —
         Se declaran DESPUÉS de Vite para tener mayor especificidad
         y no ser pisados por el reset de Tailwind v4               --}}
    <style>
        /* ── VARIABLES ── */
        :root {
            --sidebar-w:      260px;
            --sidebar-bg:     #3a6ea8;
            --sidebar-active: rgba(255,255,255,0.18);
            --sidebar-hover:  rgba(255,255,255,0.10);
            --topbar-h:       72px;
            --body-bg:        #f0f2f5;
            --card-bg:        #ffffff;
            --accent:         #4a86b5;
            --accent-dark:    #2d5a8e;
            --text-primary:   #0f1f35;
            --text-secondary: #6b7a8d;
            --border:         #e4e8ef;
            --radius:         14px;
            --shadow:         0 2px 12px rgba(0,0,0,0.07);
            --shadow-lg:      0 8px 32px rgba(0,0,0,0.10);
        }

        /* ── RESET NECESARIO (Tailwind v4 puede alterar estos valores) ── */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* ── BODY ── */
        /* !important para sobreescribir el body de app.css de Tailwind v4 */
        body {
            font-family: 'DM Sans', sans-serif !important;
            background: var(--body-bg) !important;
            color: var(--text-primary) !important;
            display: flex !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0;
        }

        .sidebar-logo-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .sidebar-logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .sidebar-logo-icon svg { width: 22px; height: 22px; color: #fff; }

        .sidebar-logo-text {
            font-family: 'Sora', sans-serif;
            font-size: 1rem; font-weight: 800;
            color: #fff; letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .sidebar-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 34px; height: 34px;
            margin: 14px 20px;
            background: none; border: none; cursor: pointer;
            border-radius: 8px; transition: background 0.2s;
            flex-shrink: 0;
        }
        .sidebar-toggle:hover { background: var(--sidebar-hover); }
        .sidebar-toggle svg   { color: rgba(255,255,255,0.65); }

        .sidebar-nav {
            flex: 1; overflow-y: auto;
            padding: 6px 12px 16px;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.65);
            font-size: 0.95rem; font-weight: 500;
            text-decoration: none; transition: all 0.18s ease;
            cursor: pointer; margin-bottom: 2px;
            user-select: none;
        }
        .nav-item:hover  { background: var(--sidebar-hover); color: #fff; }
        .nav-item.active { background: var(--sidebar-active); color: #fff; font-weight: 600; }
        .nav-item svg    { flex-shrink: 0; width: 20px; height: 20px; }

        .nav-arrow { margin-left: auto; transition: transform 0.25s; flex-shrink: 0; }
        .nav-item.open .nav-arrow { transform: rotate(180deg); }

        .subnav { overflow: hidden; max-height: 0; transition: max-height 0.3s ease; }
        .subnav.open { max-height: 300px; }

        .subnav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px 9px 46px; border-radius: 10px;
            color: rgba(255,255,255,0.60);
            font-size: 0.87rem; font-weight: 400;
            text-decoration: none; transition: all 0.18s; margin-bottom: 2px;
        }
        .subnav-item:hover  { background: var(--sidebar-hover); color: #fff; }
        .subnav-item.active { color: #fff; font-weight: 600; background: rgba(255,255,255,0.08); }

        /* ── MAIN WRAPPER ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex;
            flex-direction: column; min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--topbar-h);
            background: var(--body-bg);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.55rem; font-weight: 800;
            color: var(--text-primary); letter-spacing: -0.03em;
            /* Tailwind v4 resetea h1 — lo reestablecemos */
            margin: 0; padding: 0;
        }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .topbar-icon-btn {
            width: 40px; height: 40px;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
            text-decoration: none; color: var(--text-secondary);
        }
        .topbar-icon-btn:hover { border-color: var(--accent); color: var(--accent); }

        .profile-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 14px 6px 6px;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 40px; cursor: pointer; text-decoration: none; transition: all 0.2s;
        }
        .profile-btn:hover { border-color: var(--accent); }

        .profile-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #4a86b5, #2d5a8e);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: #fff;
            overflow: hidden; flex-shrink: 0;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .profile-name { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }

        /* ── PAGE CONTENT ── */
        .page-content { flex: 1; padding: 0 32px 32px; }

        /* ── COMPONENTES COMPARTIDOS ── */

        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 20px;
            background: var(--accent); color: #fff;
            font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 700;
            border: none; border-radius: 10px; cursor: pointer;
            text-decoration: none; transition: all 0.2s; white-space: nowrap;
        }
        .btn-primary:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(74,134,181,0.30); color: #fff; }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 20px;
            background: transparent; color: var(--text-primary);
            font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 600;
            border: 1.5px solid var(--border); border-radius: 10px;
            cursor: pointer; text-decoration: none; transition: all 0.2s;
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .search-bar {
            display: flex; align-items: center; gap: 10px;
            padding: 13px 18px;
            background: var(--card-bg); border: 1.5px solid var(--border);
            border-radius: 50px; width: 100%;
        }
        .search-bar svg   { color: var(--text-secondary); flex-shrink: 0; }
        .search-bar input {
            border: none; outline: none; background: transparent;
            font-family: 'DM Sans', sans-serif; font-size: 1rem;
            color: var(--text-primary); width: 100%;
        }
        .search-bar input::placeholder { color: #b0bac6; }

        /* Tabla */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 13px 16px; text-align: left;
            font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); text-transform: uppercase;
            letter-spacing: 0.05em; border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .data-table td {
            padding: 14px 16px; font-size: 0.9rem;
            color: var(--text-primary); border-bottom: 1px solid #f0f2f5;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td      { background: #f8fafc; }
        .data-table th:first-child,
        .data-table td:first-child   { padding-left: 20px; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 12px; border-radius: 50px;
            font-size: 0.78rem; font-weight: 600;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .badge-gray   { background: #f1f5f9; color: #64748b; }
        .badge-teal   { background: #ccfbf1; color: #0f766e; }
        .badge-dot::before {
            content: ''; width: 7px; height: 7px;
            border-radius: 50%; background: currentColor; display: inline-block;
        }

        /* Paginación */
        .pagination { display: flex; align-items: center; gap: 6px; }
        .page-btn {
            width: 34px; height: 34px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--card-bg);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 600;
            color: var(--text-secondary); cursor: pointer;
            transition: all 0.2s; text-decoration: none;
        }
        .page-btn:hover, .page-btn.active { background: var(--accent); color: #fff; border-color: var(--accent); }

        /* Botones de acción (tabla) */
        .action-btn {
            width: 30px; height: 30px; border-radius: 7px;
            border: 1px solid var(--border); background: transparent;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-secondary);
            transition: all 0.18s; text-decoration: none;
        }
        .action-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        /* Filtros select */
        .filter-select {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 14px;
            background: var(--card-bg); border: 1.5px solid var(--border);
            border-radius: 10px; font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem; font-weight: 500;
            color: var(--text-primary); cursor: pointer; white-space: nowrap;
        }
        .filter-select svg { color: var(--text-secondary); }

        /* Checkbox */
        input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); cursor: pointer; }

        /* Overlay mobile */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 99;
        }
        .sidebar-overlay.visible { display: block; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .sidebar      { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar       { padding: 0 20px; }
            .page-content { padding: 0 20px 28px; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Overlay para cerrar sidebar en mobile --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ─── SIDEBAR ─── --}}
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            {{-- Si tienes public/img/logo.png descomenta y borra el SVG --}}
            {{-- <img src="{{ asset('img/logo.png') }}" alt="SGAFA QR"> --}}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/>
                <rect x="14" y="3" width="7" height="7"/>
                <rect x="3" y="14" width="7" height="7"/>
                <path d="M14 14h3v3m0 4h4m-4 0v-4m4 0h-4"/>
            </svg>
        </div>
        <span class="sidebar-logo-text">S.G.A.F.A QR</span>
    </div>

    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>

    <nav class="sidebar-nav">

        <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ url('/activos') }}" class="nav-item {{ request()->is('activos*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
            </svg>
            Activos
        </a>

        {{-- Solicitudes --}}
        <div>
            <div class="nav-item {{ request()->is('solicitudes*') ? 'active open' : '' }}"
                 onclick="toggleSubnav('subnav-sol', this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
                Solicitudes
                <svg class="nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>
            <div class="subnav {{ request()->is('solicitudes*') ? 'open' : '' }}" id="subnav-sol">
                <a href="{{ url('/solicitudes/administrativos') }}"
                   class="subnav-item {{ request()->is('solicitudes/administrativos') ? 'active' : '' }}">
                    Movimientos Admin.
                </a>
                <a href="{{ url('/solicitudes/buzon') }}"
                   class="subnav-item {{ request()->is('solicitudes/buzon') ? 'active' : '' }}">
                    Buzón de Discrepancia
                </a>
            </div>
        </div>

        <a href="{{ url('/usuarios') }}" class="nav-item {{ request()->is('usuarios*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Usuarios
        </a>

        <a href="{{ url('/reportes') }}" class="nav-item {{ request()->is('reportes*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/>
                <path d="M14 17h6M17 14v6"/>
            </svg>
            Reportes
        </a>

        {{-- Más --}}
        <div>
            <div class="nav-item {{ request()->is('perfil*') ? 'active open' : '' }}"
                 onclick="toggleSubnav('subnav-mas', this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="5" r="1"/>
                    <circle cx="12" cy="12" r="1"/>
                    <circle cx="12" cy="19" r="1"/>
                </svg>
                Más
                <svg class="nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </div>
            <div class="subnav {{ request()->is('perfil*') ? 'open' : '' }}" id="subnav-mas">
                <a href="{{ url('/perfil') }}"
                   class="subnav-item {{ request()->is('perfil') ? 'active' : '' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Perfil
                </a>
            </div>
        </div>

    </nav>
</aside>

{{-- ─── MAIN ─── --}}
<div class="main-wrapper">
    <header class="topbar">
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-actions">
            @yield('topbar-actions')
            <a href="{{ url('/perfil') }}" class="profile-btn">
                <div class="profile-avatar">AP</div>
                <span class="profile-name">Profile</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7a8d" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<script>
    function toggleSubnav(id, el) {
        const sub    = document.getElementById(id);
        const isOpen = sub.classList.contains('open');
        // Cierra todos antes de abrir el clickeado
        document.querySelectorAll('.subnav').forEach(s => s.classList.remove('open'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('open'));
        if (!isOpen) {
            sub.classList.add('open');
            el.classList.add('open');
        }
    }

    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        const o = document.getElementById('sidebarOverlay');
        s.classList.toggle('open');
        o.classList.toggle('visible', s.classList.contains('open'));
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('visible');
    }
</script>

@stack('scripts')
</body>
</html>
