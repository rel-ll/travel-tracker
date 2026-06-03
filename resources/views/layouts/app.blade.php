<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Travel Tracker')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 220px;
            --sidebar-bg: #0f1117;
            --sidebar-border: #1e2130;
            --sidebar-text: #8b8fa8;
            --sidebar-text-hover: #e8eaf0;
            --sidebar-active-bg: #1e2130;
            --sidebar-active-text: #ffffff;
            --sidebar-section: #4a4f6a;
            --topbar-bg: #ffffff;
            --topbar-border: #e8ebf0;
            --content-bg: #f5f6fa;
            --card-bg: #ffffff;
            --card-border: #e8ebf0;
            --text-primary: #0f1117;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --accent: #2563eb;
            --accent-light: #eff6ff;
            --radius: 8px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
            --shadow: 0 1px 3px rgba(0,0,0,.07), 0 2px 8px rgba(0,0,0,.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-size: 14px;
            background: var(--content-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo-icon {
            width: 30px; height: 30px;
            background: var(--accent);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo-icon svg { width: 16px; height: 16px; color: #fff; }

        .sidebar-logo-text {
            font-size: 13.5px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -.01em;
            line-height: 1.2;
        }

        .sidebar-logo-sub {
            font-size: 10.5px;
            color: var(--sidebar-text);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: 10px 8px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--sidebar-section);
            padding: 14px 8px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 8px;
            border-radius: 6px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            transition: background .12s, color .12s;
            cursor: pointer;
            width: 100%;
            border: none;
            background: none;
        }

        .nav-item:hover {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-text-hover);
        }

        .nav-item.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            font-weight: 500;
        }

        .nav-item svg {
            width: 16px; height: 16px;
            flex-shrink: 0;
            opacity: .7;
            stroke-width: 1.6;
        }

        .nav-item.active svg, .nav-item:hover svg { opacity: 1; }

        .sidebar-footer {
            padding: 12px 8px;
            border-top: 1px solid var(--sidebar-border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px;
            border-radius: 6px;
        }

        .user-avatar {
            width: 28px; height: 28px;
            background: #1e2130;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: #8b8fa8;
            flex-shrink: 0;
            border: 1px solid #2a2f45;
        }

        .user-info { flex: 1; min-width: 0; }

        .user-name {
            font-size: 12.5px;
            font-weight: 500;
            color: #c8cad8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 10.5px;
            color: var(--sidebar-text);
            text-transform: capitalize;
        }

        /* ── Main layout ──────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ───────────────────────────────── */
        .topbar {
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            padding: 0 24px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .topbar-left .breadcrumb-sep { color: var(--text-muted); }
        .topbar-left .breadcrumb-current { color: var(--text-primary); font-weight: 500; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topbar-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all .12s;
            font-family: 'DM Sans', sans-serif;
        }

        .topbar-btn svg { width: 14px; height: 14px; stroke-width: 2; }

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 16px;
    background: var(--text-primary);
    color: var(--card-bg);
    border: none;
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}

.btn-primary:hover {
    opacity: .85;
}
.btn-outline {
    display: inline-flex;
    align-items: center;
    height: 36px;
    padding: 0 16px;
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}

.btn-outline:hover {
    background: var(--card-border);
}
        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--card-border);
        }

        .btn-ghost:hover { background: var(--content-bg); color: var(--text-primary); }

.btn-danger {
    background: #E24B4A;
    color: #fff;
    border: none;
    display: inline-flex;
    align-items: center;
    height: 36px;
    padding: 0 16px;
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.btn-warning {
    background: #BA7517;
    color: #fff;
    border: none;
        display: inline-flex;
    align-items: center;
    height: 36px;
    padding: 0 16px;
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}

        .btn-danger:hover { background: var(--card-border); }

        /* ── Page content ─────────────────────────── */
        .page-content {
            padding: 24px;
            flex: 1;
        }

        .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.page-header h1 {
    font-size: 22px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -.02em;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 3px;
        }

        /* ── Cards ────────────────────────────────── */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body { padding: 20px; }

        /* ── Stat cards ───────────────────────────── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
            display: flex;          /* add */
            flex-direction: column; /* add */
            gap: 10px; 
        }

        .stat-label {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -.03em;
            font-family: 'DM Mono', monospace;
        }

        .stat-sub {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .stat-icon {
        width: 34px;
        height: 34px;
        background: var(--bg-secondary, #f4f6f9);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        }

        .stat-icon svg {
        width: 17px;
        height: 17px;
        stroke: var(--text-primary);
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        }

        /* ── filter ────────────────────────────────── */
        .filter-bar {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-bar > div {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 160px;
}

.filter-bar label {
    font-size: 11.5px;
    font-weight: 500;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .05em;
}

.filter-bar select,
.filter-bar input {
    height: 36px;
    padding: 0 10px;
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    font-size: 13px;
    color: var(--text-primary);
    background: var(--card-bg);
    width: 100%;
}

        /* ── Table ────────────────────────────────── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--card-border);
            background: #fafbfc;
        }

        tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-primary);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafbfc; }

        /* ── Badges ───────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 500;
            letter-spacing: .01em;
        }

        .badge-air  { background: #eff6ff; color: #2563eb; }
        .badge-sea  { background: #f0fdf4; color: #16a34a; }
        .badge-land { background: #fefce8; color: #a16207; }
        .badge-admin { background: #f5f3ff; color: #7c3aed; }
        .badge-staff { background: #f0fdf4; color: #16a34a; }

        /* ── Forms ────────────────────────────────── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group.full { grid-column: 1 / -1; }

        label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            letter-spacing: .01em;
        }

        input, select, textarea {
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            padding: 8px 11px;
            border: 1px solid var(--card-border);
            border-radius: 6px;
            background: #fff;
            color: var(--text-primary);
            transition: border-color .12s, box-shadow .12s;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,.08);
        }

        textarea { resize: vertical; min-height: 80px; line-height: 1.5; }

        .mode-selector {
            display: flex;
            gap: 8px;
        }

        .mode-option { display: none; }

        .mode-label {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 12px;
            border: 1px solid var(--card-border);
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all .12s;
        }

        .mode-label svg { width: 15px; height: 15px; stroke-width: 1.8; }

        .mode-option:checked + .mode-label {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--accent);
        }

        /* ── Alerts ───────────────────────────────── */
        .alert {
            padding: 10px 14px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        .alert svg { width: 15px; height: 15px; flex-shrink: 0; }

        /* ── Misc ─────────────────────────────────── */
        .text-mono { font-family: 'DM Mono', monospace; }
        .text-muted { color: var(--text-muted); }
        .divider { height: 1px; background: var(--card-border); margin: 16px 0; }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state svg {
            width: 36px; height: 36px;
            margin: 0 auto 12px;
            opacity: .3;
        }

        .empty-state p { font-size: 13px; }

        /* Print */
        @media print {
            .sidebar, .topbar { display: none; }
            .main-wrap { margin-left: 0; }
            .page-content { padding: 0; }
        }
    </style>
</head>
<body>

@auth
<!-- ── Sidebar ──────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
            </svg>
        </div>
        <div>
            <div class="sidebar-logo-text">Travel Tracker</div>
            <div class="sidebar-logo-sub">Management System</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>

        <a href="{{ route('travels.index') }}" class="nav-item {{ request()->routeIs('travels.index') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('travels.create') }}" class="nav-item {{ request()->routeIs('travels.create') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            New Travel Request
        </a>

        <a href="{{ route('travels.all') }}" class="nav-item {{ request()->routeIs('travels.show', 'travels.edit') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
            </svg>
            All Records
        </a>

@if(auth()->user()->role === 'admin')
<div class="nav-section-label">Administration</div>

<a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
    </svg>
    User Management
</a>

<a href="{{ route('travels.trash') }}" class="nav-item {{ request()->routeIs('travels.trash') ? 'active' : '' }}">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
    </svg>
    Trash
    @php $trashCount = \App\Models\Travel::onlyTrashed()->count(); @endphp
    @if($trashCount > 0)
        <span style="margin-left:auto;background:#3d1515;color:#f87171;font-size:10px;font-weight:600;padding:1px 6px;border-radius:10px">{{ $trashCount }}</span>
    @endif
</a>
@endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:4px">
            @csrf
            <button type="submit" class="nav-item" style="width:100%">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Sign out
            </button>
        </form>
    </div>
</aside>

<!-- ── Main ─────────────────────────────────────────── -->
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <span>Travel Tracker</span>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">@yield('title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            @yield('topbar-actions')
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@else
<!-- Guest / login layout -->
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f6fa; width:100%">
    @yield('content')
</div>
@endauth

</body>
</html>