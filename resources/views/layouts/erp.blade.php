<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inmate ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 270px;
            --page-bg: #f3f6fb;
            --panel: #ffffff;
            --border: #e4eaf2;
            --text: #172033;
            --muted: #64748b;
            --primary: #2563eb;
            --primary-soft: #eef4ff;
            --success-soft: #edfdf4;
            --warning-soft: #fff7e8;
            --danger-soft: #fff1f2;
            --shadow-sm: 0 8px 22px rgba(15, 23, 42, 0.06);
            --shadow-md: 0 16px 42px rgba(15, 23, 42, 0.08);
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text);
            font-family: "Segoe UI", system-ui, sans-serif;
            font-size: 15px;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            overflow-y: auto;
            background: linear-gradient(180deg, #111c30, #0b1220);
            color: #fff;
            padding: 20px 16px;
            box-shadow: 14px 0 34px rgba(15, 23, 42, 0.14);
            transition: width 0.22s ease, padding 0.22s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.35rem 0.55rem 1.15rem;
            margin-bottom: 0.65rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb, #0f766e);
            color: #fff;
            font-weight: 800;
        }

        .brand-title {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand-copy,
        .nav-text,
        .nav-section-label,
        .logout-text {
            transition: opacity 0.16s ease;
        }

        .brand-subtitle {
            margin: 0.15rem 0 0;
            color: #aab7cc;
            font-size: 0.78rem;
        }

        .nav-link,
        .nav-section-toggle {
            width: 100%;
            color: #cbd5e1;
            padding: 0.72rem 0.82rem;
            border-radius: 0.7rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: 0.2s ease;
            border: 0;
            background: transparent;
            text-align: left;
            line-height: 1.25;
            min-height: 42px;
        }

        .nav-link i,
        .nav-section-toggle i:first-child {
            width: 1.15rem;
            text-align: center;
            font-size: 1.02rem;
        }

        .nav-link:hover,
        .nav-section-toggle:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.32);
        }

        .nav-section {
            margin-bottom: 0.35rem;
        }

        .nav-section summary {
            cursor: pointer;
            list-style: none;
        }

        .nav-section summary::-webkit-details-marker {
            display: none;
        }

        .nav-section-chevron {
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        .nav-section[open] .nav-section-chevron {
            transform: rotate(180deg);
        }

        .nav-children {
            display: grid;
            gap: 0.3rem;
            margin: 0.35rem 0 0.4rem 1.1rem;
            padding-left: 0.85rem;
            border-left: 1px solid rgba(203, 213, 225, 0.24);
        }

        .nav-children .nav-link {
            margin: 0;
            padding: 0.62rem 0.75rem;
            font-size: 0.93rem;
        }

        .logout-btn {
            margin-top: auto;
            padding-top: 1rem;
        }

        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.22s ease;
        }

        body.sidebar-collapsed {
            --sidebar-width: 86px;
        }

        body.sidebar-collapsed .sidebar {
            padding-inline: 14px;
            overflow-x: hidden;
        }

        body.sidebar-collapsed .brand {
            justify-content: center;
            padding-inline: 0;
            flex-direction: column;
            gap: 0.7rem;
        }

        body.sidebar-collapsed .brand-identity {
            justify-content: center;
        }

        body.sidebar-collapsed .brand-copy,
        body.sidebar-collapsed .nav-text,
        body.sidebar-collapsed .nav-section-label,
        body.sidebar-collapsed .nav-section-chevron,
        body.sidebar-collapsed .logout-text {
            display: none;
        }

        body.sidebar-collapsed .nav-link,
        body.sidebar-collapsed .nav-section-toggle {
            justify-content: center;
            padding-inline: 0.65rem;
        }

        body.sidebar-collapsed .nav-section {
            display: grid;
            gap: 0.25rem;
        }

        body.sidebar-collapsed .nav-section:not([open]) .nav-children {
            display: none;
        }

        body.sidebar-collapsed .nav-children {
            display: grid;
            gap: 0.25rem;
            margin: 0;
            padding-left: 0;
            border-left: 0;
        }

        body.sidebar-collapsed .nav-children .nav-link {
            justify-content: center;
            min-height: 38px;
            padding: 0.55rem;
            background: rgba(255, 255, 255, 0.04);
        }

        body.sidebar-collapsed .logout-btn .btn {
            width: 46px !important;
            height: 42px;
            padding: 0;
        }

        .sidebar-toggle {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border: 1px solid rgba(203, 213, 225, 0.2);
            border-radius: 0.7rem;
            background: rgba(255, 255, 255, 0.06);
            color: #dbeafe;
            transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(203, 213, 225, 0.35);
            color: #fff;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255,255,255,0.94);
            backdrop-filter: blur(16px);
            padding: 0.85rem 1.6rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .topbar h5 {
            margin: 0;
            font-weight: 800;
            color: #142033;
        }

        .topbar-title {
            display: grid;
            gap: 0.08rem;
        }

        .topbar-kicker {
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .search-form {
            position: relative;
            width: min(340px, 42vw);
        }

        .search-form i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-form .form-control,
        .form-control,
        .form-select {
            border-color: #d8e1ee;
            border-radius: 0.65rem;
            min-height: 42px;
            box-shadow: none;
        }

        .search-form .form-control {
            padding-left: 2.35rem;
            background: #f8fafc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93b4ff;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
        }

        .content {
            padding: 1.45rem 1.6rem 2rem;
            max-width: 1680px;
        }

        .card,
        .stat-card,
        .toolbar-card,
        .table-card {
            border-color: var(--border) !important;
            border-radius: 0.9rem !important;
            box-shadow: var(--shadow-sm);
        }

        .table {
            --bs-table-hover-bg: #f7faff;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fbff;
            color: #475569;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.055em;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            color: #27364a;
            vertical-align: middle;
        }

        .table-responsive,
        .table-wrap {
            border-radius: 0.85rem;
        }

        .btn {
            border-radius: 0.65rem;
            font-weight: 650;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
        }

        .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #475569;
        }

        .alert {
            border: 0;
            border-radius: 0.85rem;
            box-shadow: var(--shadow-sm);
        }

        .form-label {
            color: #334155;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.045em;
            text-transform: uppercase;
        }

        .form-text {
            color: #718096;
            font-size: 0.78rem;
        }

        .page-header,
        .erp-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            padding: 1.25rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 0.9rem;
            box-shadow: var(--shadow-sm);
        }

        .page-header h2,
        .page-header h3,
        .erp-page-header h2,
        .erp-page-header h3 {
            margin: 0;
            color: #132238;
            font-weight: 850;
        }

        .page-subtitle,
        .erp-page-header p {
            margin: 0.25rem 0 0;
            color: var(--muted);
        }

        .erp-form-card {
            max-width: 1180px;
        }

        .detail-grid strong {
            display: block;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.055em;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .detail-grid > div {
            padding: 0.9rem;
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 0.8rem;
        }

        .page-title {
            font-size: 1.45rem;
            font-weight: 800;
            margin-bottom: 1.2rem;
            color: #1f2937;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.05rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: #fff;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12);
        }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            color: #fff;
        }

        .blue .stat-icon { background: #2563eb; }
        .orange .stat-icon { background: #ea580c; }
        .green .stat-icon { background: #16a34a; }
        .purple .stat-icon { background: #7c3aed; }

        .stat-card h4,
        .stat-card h6 {
            margin: 0;
            font-size: 0.9rem;
            color: var(--muted);
            font-weight: 700;
        }

        .stat-card p {
            margin: 0.25rem 0 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
        }

        .action-btn {
            color: #000 !important;
            font-weight: 600;
        }

        body.dark-mode {
            --page-bg: #0f172a;
            --panel: #111827;
            --border: #1e293b;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --primary-soft: #0f1f3f;
            background: var(--page-bg);
            color: var(--text);
        }

        body.dark-mode .sidebar {
            background: linear-gradient(180deg, #020617, #0f172a);
        }

        body.dark-mode .topbar,
        body.dark-mode .card,
        body.dark-mode .stat-card,
        body.dark-mode .toolbar-card,
        body.dark-mode .table-card,
        body.dark-mode .page-header,
        body.dark-mode .erp-page-header {
            background: #020617 !important;
            color: #e5e7eb;
            border-color: #1e293b !important;
        }

        body.dark-mode .topbar h5,
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode .stat-card p,
        body.dark-mode .page-header h2,
        body.dark-mode .page-header h3,
        body.dark-mode .erp-page-header h2,
        body.dark-mode .erp-page-header h3 {
            color: #f8fafc !important;
        }

        body.dark-mode .text-muted,
        body.dark-mode .stat-card h4,
        body.dark-mode .stat-card h6 {
            color: #94a3b8 !important;
        }

        body.dark-mode table,
        body.dark-mode .table {
            color: #e5e7eb;
            --bs-table-bg: #020617;
            --bs-table-hover-bg: #111827;
            --bs-table-border-color: #1e293b;
        }

        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea {
            background: #0f172a;
            color: #e5e7eb;
            border-color: #334155;
        }

        body.dark-mode .search-form .form-control,
        body.dark-mode .bg-light,
        body.dark-mode .detail-grid > div {
            background: #0f172a;
            border-color: #1e293b;
        }

        body.dark-mode .text-dark {
            color: #f8fafc !important;
        }

        body.dark-mode .table thead th {
            background: #0f172a;
            color: #cbd5e1;
        }

        body.dark-mode .table tbody td {
            color: #e2e8f0;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-radius: 0;
            }

            .main {
                margin-left: 0;
            }

            body.sidebar-collapsed {
                --sidebar-width: 100%;
            }

            body.sidebar-collapsed .brand-copy,
            body.sidebar-collapsed .nav-text,
            body.sidebar-collapsed .nav-section-label,
            body.sidebar-collapsed .nav-section-chevron,
            body.sidebar-collapsed .logout-text {
                display: inline;
            }

            body.sidebar-collapsed .nav-children {
                display: grid;
            }

            body.sidebar-collapsed .nav-link,
            body.sidebar-collapsed .nav-section-toggle {
                justify-content: flex-start;
                padding: 0.72rem 0.82rem;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar-actions,
            .search-form {
                width: 100%;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
@php
    $directoryOpen = request()->is('directory*');
@endphp

<div class="sidebar">
    <div class="brand">
        <div class="brand-identity">
            <span class="brand-mark">UPF</span>
            <div class="brand-copy">
                <h4 class="brand-title">UPF Digi Link</h4>
                <p class="brand-subtitle">Inmate ERP</p>
            </div>
        </div>

        <button id="sidebarToggle" type="button" class="sidebar-toggle" title="Collapse sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
    </div>

    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
    </a>

    <a href="{{ route('home') }}"
       class="nav-link {{ request()->is('home*') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i> <span class="nav-text">Home</span>
    </a>

    <details class="nav-section" {{ $directoryOpen ? 'open' : '' }}>
        <summary class="nav-section-toggle">
            <i class="bi bi-folder2-open"></i> <span class="nav-section-label">Directory</span>
            <i class="bi bi-chevron-down nav-section-chevron"></i>
        </summary>

        <div class="nav-children">
            <a href="{{ route('directory.home') }}"
               class="nav-link {{ request()->is('directory/home') ? 'active' : '' }}">
                <i class="bi bi-house-add"></i> <span class="nav-text">Home</span>
            </a>
        </div>
    </details>

    <a href="{{ route('orphans.index') }}"
       class="nav-link {{ request()->is('orphans*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> <span class="nav-text">Inmates</span>
    </a>

    <a href="{{ route('volunteers.index') }}"
       class="nav-link {{ request()->is('volunteers*') ? 'active' : '' }}">
        <i class="bi bi-person-heart"></i> <span class="nav-text">Volunteers</span>
    </a>

    <a href="{{ route('community-connect.index') }}"
       class="nav-link {{ request()->is('community-connect-program*') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> <span class="nav-text">Community Connect Program</span>
    </a>

    <a href="{{ route('sponsors.index') }}"
       class="nav-link {{ request()->is('sponsors*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> <span class="nav-text">Staffs</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="logout-btn">
        @csrf
        <button class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> <span class="logout-text">Logout</span>
        </button>
    </form>
</div>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">
            <span class="topbar-kicker">UPF ERP</span>
            <h5>@yield('page-title', 'Dashboard')</h5>
        </div>

        <div class="topbar-actions">
            <form action="{{ route('global.search') }}" method="GET" class="search-form">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="q"
                    placeholder="Search inmate or staff..."
                    class="form-control form-control-sm"
                    required
                >
            </form>

            <button id="darkToggle" type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-moon-stars"></i> Dark Mode
            </button>

            <div class="text-muted small fw-semibold">
                {{ auth()->user()->name }}
            </div>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('darkToggle');
    const sidebarToggleBtn = document.getElementById('sidebarToggle');

    function enableDark() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        toggleBtn.innerHTML = '<i class="bi bi-sun"></i> Light Mode';
    }

    function disableDark() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        toggleBtn.innerHTML = '<i class="bi bi-moon-stars"></i> Dark Mode';
    }

    if (localStorage.getItem('theme') === 'dark') {
        enableDark();
    }

    toggleBtn.addEventListener('click', () => {
        document.body.classList.contains('dark-mode') ? disableDark() : enableDark();
    });

    function setSidebarCollapsed(collapsed) {
        document.body.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'expanded');
        sidebarToggleBtn.innerHTML = collapsed
            ? '<i class="bi bi-layout-sidebar"></i>'
            : '<i class="bi bi-layout-sidebar-inset"></i>';
        sidebarToggleBtn.title = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
    }

    if (localStorage.getItem('sidebar') === 'collapsed') {
        setSidebarCollapsed(true);
    }

    sidebarToggleBtn.addEventListener('click', () => {
        setSidebarCollapsed(!document.body.classList.contains('sidebar-collapsed'));
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
