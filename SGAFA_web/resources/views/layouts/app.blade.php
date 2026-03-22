<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SGAFA QR') – S.G.A.F.A QR</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── VARIABLES ── */
        :root {
            --sidebar-w:      260px;
            --sidebar-w-collapsed: 88px;
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

        *, *::before, *::after { box-sizing: border-box; }

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
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s ease;
            overflow: hidden;
        }

        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-w-collapsed);
        }

        body.sidebar-collapsed .sidebar-logo { justify-content: center; padding-left: 0; padding-right: 0;}
        body.sidebar-collapsed .sidebar-logo-text { opacity: 0; width: 0; pointer-events: none; overflow: hidden; margin: 0; }
        body.sidebar-collapsed .nav-text { opacity: 0; width: 0; pointer-events: none; overflow: hidden; margin: 0; }
        body.sidebar-collapsed .nav-arrow { opacity: 0; width: 0; overflow: hidden; margin: 0; }
        body.sidebar-collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; gap: 0;}
        body.sidebar-collapsed .subnav { max-height: 0 !important; }
        body.sidebar-collapsed .sidebar-toggle { margin-left: auto; margin-right: auto; }
        
        .sidebar-logo-text, .nav-text, .nav-arrow {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
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
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body.sidebar-collapsed .main-wrapper {
            margin-left: var(--sidebar-w-collapsed);
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
            position: relative;
        }
        .topbar-icon-btn:hover { border-color: var(--accent); color: var(--accent); }

        /* ── NOTIFICACIONES ── */
        .notif-wrapper { position: relative; }

        .notif-badge {
            position: absolute; top: 6px; right: 6px;
            min-width: 8px; height: 8px;
            background: #ef4444; border-radius: 50%;
            border: 2px solid var(--body-bg);
            font-size: 9px; font-weight: 700; color: #fff;
            display: flex; align-items: center; justify-content: center;
            padding: 0 2px;
        }

        .notif-panel {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 340px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            z-index: 200;
            opacity: 0; pointer-events: none;
            transform: translateY(-6px);
            transition: all 0.18s ease;
            overflow: hidden;
        }
        .notif-panel.open { opacity: 1; pointer-events: all; transform: translateY(0); }

        .notif-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px 10px;
            border-bottom: 1px solid #f0f2f5;
        }
        .notif-panel-title { font-family: 'Sora', sans-serif; font-size: .92rem; font-weight: 700; color: #0f1f35; }
        .notif-mark-all {
            font-size: .75rem; font-weight: 600; color: #4a86b5;
            background: none; border: none; cursor: pointer; padding: 0;
            font-family: 'DM Sans', sans-serif;
        }
        .notif-mark-all:hover { text-decoration: underline; }

        .notif-list { max-height: 320px; overflow-y: auto; scrollbar-width: thin; }

        .notif-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #f7f8fa;
            cursor: pointer; transition: background .15s;
            text-decoration: none; color: inherit;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.no-leida { background: #eff6ff; }
        .notif-item.no-leida:hover { background: #dbeafe; }

        .notif-icon {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 1px;
        }
        .notif-icon.blue   { background: #dbeafe; color: #1d4ed8; }
        .notif-icon.red    { background: #fee2e2; color: #dc2626; }
        .notif-icon.yellow { background: #fef9c3; color: #a16207; }
        .notif-icon.green  { background: #dcfce7; color: #15803d; }

        .notif-body { flex: 1; min-width: 0; }
        .notif-titulo {
            font-size: .84rem; font-weight: 600; color: #0f1f35;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .notif-mensaje { font-size: .78rem; color: #6b7a8d; margin-top: 2px; line-height: 1.4; }
        .notif-tiempo  { font-size: .72rem; color: #9ca3af; margin-top: 4px; }

        .notif-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #4a86b5; flex-shrink: 0; margin-top: 6px;
        }

        .notif-loading {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 32px; font-size: .84rem; color: #9ca3af;
        }

        .notif-empty {
            padding: 32px 16px; text-align: center;
            font-size: .84rem; color: #9ca3af;
        }
        .notif-empty svg { display: block; margin: 0 auto 8px; }

        .notif-ver-todas {
            display: block; text-align: center;
            padding: 12px; font-size: .82rem; font-weight: 600;
            color: #4a86b5; text-decoration: none;
            border-top: 1px solid #f0f2f5;
            transition: background .15s;
        }
        .notif-ver-todas:hover { background: #f8fafc; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── PROFILE DROPDOWN ── */
        .profile-dropdown-wrapper { position: relative; }

        .profile-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 14px 6px 6px;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 40px; cursor: pointer; text-decoration: none;
            transition: all 0.2s; user-select: none;
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

        .profile-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            width: 200px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            opacity: 0; pointer-events: none;
            transform: translateY(-6px);
            transition: all 0.18s ease;
            z-index: 200;
        }
        .profile-menu.open { opacity: 1; pointer-events: all; transform: translateY(0); }

        .profile-menu-header {
            padding: 14px 16px 10px;
            border-bottom: 1px solid var(--border);
        }
        .profile-menu-name  { font-size: .88rem; font-weight: 700; color: var(--text-primary); }
        .profile-menu-email { font-size: .76rem; color: var(--text-secondary); margin-top: 2px; }

        .profile-menu a, .profile-menu button {
            display: flex; align-items: center; gap: 9px;
            width: 100%; padding: 10px 16px;
            font-family: 'DM Sans', sans-serif; font-size: .88rem;
            color: var(--text-primary); text-decoration: none;
            background: none; border: none; cursor: pointer;
            transition: background 0.15s; text-align: left;
        }
        .profile-menu a:hover, .profile-menu button:hover { background: #f4f6fb; }
        .profile-menu a svg, .profile-menu button svg { width: 15px; height: 15px; color: var(--text-secondary); }

        .profile-menu-logout { border-top: 1px solid var(--border); }
        .profile-menu-logout a { color: #dc2626 !important; }
        .profile-menu-logout a svg { color: #dc2626 !important; }
        .profile-menu-logout a:hover { background: #fff5f5 !important; }

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

        .action-btn {
            width: 30px; height: 30px; border-radius: 7px;
            border: 1px solid var(--border); background: transparent;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-secondary);
            transition: all 0.18s; text-decoration: none;
        }
        .action-btn:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

        .filter-select {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 14px;
            background: var(--card-bg); border: 1.5px solid var(--border);
            border-radius: 10px; font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem; font-weight: 500;
            color: var(--text-primary); cursor: pointer; white-space: nowrap;
        }
        .filter-select svg { color: var(--text-secondary); }

        input[type="checkbox"] { width: 17px; height: 17px; accent-color: var(--accent); cursor: pointer; }

        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 99;
        }
        .sidebar-overlay.visible { display: block; }

        @media (max-width: 1024px) {
            .sidebar      { transform: translateX(-100%); width: var(--sidebar-w); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0 !important; }
            body.sidebar-collapsed .main-wrapper { margin-left: 0 !important; }
            .topbar       { padding: 0 20px; }
            .page-content { padding: 0 20px 28px; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ─── SIDEBAR ─── --}}
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
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
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="{{ url('/activos') }}" class="nav-item {{ request()->is('activos*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
            </svg>
            <span class="nav-text">Activos</span>
        </a>

        <div>
            <div class="nav-item {{ request()->is('solicitudes*') ? 'active open' : '' }}"
                 onclick="toggleSubnav('subnav-sol', this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
                <span class="nav-text">Solicitudes</span>
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
            <span class="nav-text">Usuarios</span>
        </a>

        <a href="{{ url('/reportes') }}" class="nav-item {{ request()->is('reportes*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/>
                <path d="M14 17h6M17 14v6"/>
            </svg>
            <span class="nav-text">Reportes</span>
        </a>

    </nav>
</aside>

{{-- ─── MAIN ─── --}}
<div class="main-wrapper">
    <header class="topbar">
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-actions">

            @yield('topbar-actions')

            {{-- ── CAMPANA DE NOTIFICACIONES ── --}}
            <div class="notif-wrapper" id="notifWrapper">
                <button class="topbar-icon-btn" onclick="toggleNotif()" title="Notificaciones">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="notif-badge" id="notifBadge" style="display:none;"></span>
                </button>

                <div class="notif-panel" id="notifPanel">
                    <div class="notif-panel-header">
                        <span class="notif-panel-title">Notificaciones</span>
                        <button class="notif-mark-all" onclick="marcarTodas()">Marcar todas como leídas</button>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-loading">
                            <svg width="20" height="20" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"
                                style="animation:spin 1s linear infinite;">
                                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".3"/>
                                <path d="M21 12a9 9 0 00-9-9"/>
                            </svg>
                            Cargando...
                        </div>
                    </div>
                    <a href="#" class="notif-ver-todas">Ver todas las notificaciones →</a>
                </div>
            </div>

            {{-- ── PERFIL ── --}}
            <div class="profile-dropdown-wrapper">
                <div class="profile-btn" onclick="toggleProfileMenu()" id="profileBtn">
                    <div class="profile-avatar">AP</div>
                    <span class="profile-name">Adán Piña</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7a8d" stroke-width="2.5">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </div>

                <div class="profile-menu" id="profileMenu">
                    <div class="profile-menu-header">
                        <div class="profile-menu-name">Adán Piña</div>
                        <div class="profile-menu-email">adan.pina@upq.edu.mx</div>
                    </div>
                    <a href="{{ url('/perfil') }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mi perfil
                    </a>
                    <a href="{{ url('/perfil/edit') }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Editar perfil
                    </a>
                    <div class="profile-menu-logout">
                        <a href="{{ url('/login') }}">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Cerrar sesión
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<script>
    // ── SIDEBAR ──
    function toggleSubnav(id, el) {
        if (window.innerWidth > 1024 && document.body.classList.contains('sidebar-collapsed')) {
            document.body.classList.remove('sidebar-collapsed'); // Auto-expand when clicking a subnav
        }
        
        const sub    = document.getElementById(id);
        const isOpen = sub.classList.contains('open');
        document.querySelectorAll('.subnav').forEach(s => s.classList.remove('open'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('open'));
        if (!isOpen) {
            sub.classList.add('open');
            el.classList.add('open');
        }
    }

    function toggleSidebar() {
        if (window.innerWidth <= 1024) {
            // Comportamiento Mobile
            const s = document.getElementById('sidebar');
            const o = document.getElementById('sidebarOverlay');
            s.classList.toggle('open');
            o.classList.toggle('visible', s.classList.contains('open'));
        } else {
            // Comportamiento Desktop
            document.body.classList.toggle('sidebar-collapsed');
            if (document.body.classList.contains('sidebar-collapsed')) {
                // Cierra los submenus si decides contraer la barra
                document.querySelectorAll('.subnav').forEach(s => s.classList.remove('open'));
                document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('open'));
            }
        }
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('visible');
    }

    // ── PERFIL ──
    function toggleProfileMenu() {
        document.getElementById('profileMenu').classList.toggle('open');
        document.getElementById('notifPanel').classList.remove('open');
    }

    document.addEventListener('click', function(e) {
        const profile = document.querySelector('.profile-dropdown-wrapper');
        const notif   = document.getElementById('notifWrapper');
        if (profile && !profile.contains(e.target))
            document.getElementById('profileMenu').classList.remove('open');
        if (notif && !notif.contains(e.target))
            document.getElementById('notifPanel').classList.remove('open');
    });

    // ── NOTIFICACIONES ──
    function toggleNotif() {
        const panel = document.getElementById('notifPanel');
        panel.classList.toggle('open');
        document.getElementById('profileMenu').classList.remove('open');
        if (panel.classList.contains('open')) cargarNotificaciones();
    }

    async function cargarNotificaciones() {
        try {
            const res  = await fetch('/notificaciones', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            actualizarBadge(data.no_leidas);
            renderNotificaciones(data.notifications);
        } catch(e) {
            document.getElementById('notifList').innerHTML =
                '<div class="notif-empty">Error al cargar notificaciones.</div>';
        }
    }

    function actualizarBadge(count) {
        const badge = document.getElementById('notifBadge');
        if (count > 0) {
            badge.style.display = 'flex';
            badge.textContent = count > 9 ? '9+' : (count > 1 ? count : '');
        } else {
            badge.style.display = 'none';
        }
    }

    function renderNotificaciones(items) {
        const list = document.getElementById('notifList');
        if (!items.length) {
            list.innerHTML = `
                <div class="notif-empty">
                    <svg width="32" height="32" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Sin notificaciones nuevas
                </div>`;
            return;
        }
        const iconos = {
            bell:    '<path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
            warning: '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
            check:   '<polyline points="20 6 9 17 4 12"/>',
            info:    '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        };
        list.innerHTML = items.map(n => `
            <div class="notif-item ${n.leida ? '' : 'no-leida'}"
                 onclick="abrirNotif(${n.id}, '${n.url || ''}')">
                <div class="notif-icon ${n.color}">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        ${iconos[n.icono] || iconos.bell}
                    </svg>
                </div>
                <div class="notif-body">
                    <div class="notif-titulo">${n.titulo}</div>
                    <div class="notif-mensaje">${n.mensaje}</div>
                    <div class="notif-tiempo">${tiempoRelativo(n.created_at)}</div>
                </div>
                ${!n.leida ? '<div class="notif-dot"></div>' : ''}
            </div>
        `).join('');
    }

    async function abrirNotif(id, url) {
        await fetch(`/notificaciones/${id}/leer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        if (url) window.location.href = url;
        else cargarNotificaciones();
    }

    async function marcarTodas() {
        await fetch('/notificaciones/leer-todas', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            }
        });
        cargarNotificaciones();
    }

    function tiempoRelativo(fechaStr) {
        const diff = Math.floor((Date.now() - new Date(fechaStr)) / 1000);
        if (diff < 60)    return 'Hace un momento';
        if (diff < 3600)  return `Hace ${Math.floor(diff/60)} min`;
        if (diff < 86400) return `Hace ${Math.floor(diff/3600)} h`;
        return `Hace ${Math.floor(diff/86400)} días`;
    }

    // Carga el badge al iniciar
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const res  = await fetch('/notificaciones', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            actualizarBadge(data.no_leidas);
        } catch(e) {}
    });
</script>

@stack('scripts')
</body>
</html>