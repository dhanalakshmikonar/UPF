<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inmate ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f4f6f9;
            font-family: "Segoe UI", system-ui, sans-serif;
        }

        /* ================= DARK MODE ================= */
body.dark-mode {
    background: #0f172a;
    color: #e5e7eb;
}

/* Main content */
body.dark-mode .main {
    background: #0f172a;
}

/* Topbar */
body.dark-mode .topbar {
    background: #020617;
    border-bottom: 1px solid #1e293b;
    color: #e5e7eb;
}

/* Sidebar */
body.dark-mode .sidebar {
    background: linear-gradient(180deg, #020617, #020617);
}

/* Cards */
body.dark-mode .stat-card,
body.dark-mode .card {
    background: #020617;
    color: #e5e7eb;
    box-shadow: 0 10px 25px rgba(0,0,0,0.6);
}

/* Card text */
body.dark-mode .stat-card h4,
body.dark-mode .stat-card h6 {
    color: #94a3b8;
}

body.dark-mode .stat-card p{
    color:  #f8fafc;
}

/* Tables */
body.dark-mode table {
    background: #020617;
    color: #e5e7eb;
}

body.dark-mode th {
    background: #020617;
    color: #f8fafc;
}

body.dark-mode td {
    border-color: #1e293b;
}

/* Links */
body.dark-mode a {
    color: #93c5fd;
}




        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #1f2937, #111827);
            color: #fff;
            padding: 20px;
        }

        .sidebar h4 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .nav-link {
            color: #cbd5e1;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.25s ease;
        }

        .nav-link:hover {
            background: #374151;
            color: #fff;
        }

        .nav-link.active {
            background: #2563eb;
            color: #fff;
            font-weight: 500;
        }

        /* ========== MAIN AREA ========== */
        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            background: #ffffff;
            padding: 15px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h5 {
            margin: 0;
            font-weight: 600;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        /* ========== DASHBOARD ========== */
        .page-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 22px;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
        }

        .blue  .stat-icon { background: #2563eb; }
        .orange .stat-icon { background: #ea580c; }
        .green .stat-icon { background: #16a34a; }
        .purple .stat-icon { background: #7c3aed; }

        .stat-card h6 {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-card p {
            margin: 4px 0 0;
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        /* LOGOUT */
        .logout-btn {
            margin-top: 30px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            .main {
                margin-left: 0;
            }
        }


        /* ===============================
   DARK MODE BASE
================================ */
body.dark-mode {
    background: #0f172a;
    color: #e5e7eb;
}

/* Main content */
body.dark-mode .main {
    background: #0f172a;
}

/* Topbar */
body.dark-mode .topbar {
    background: #020617;
    border-bottom: 1px solid #1e293b;
    color: #e5e7eb;
}

/* Cards */
body.dark-mode .card {
    background: #020617;
    color: #e5e7eb;
    box-shadow: 0 10px 30px rgba(0,0,0,.6);
}

/* Tables */
body.dark-mode table {
    color: #e5e7eb;
}

body.dark-mode table thead {
    background: #020617;
}

body.dark-mode table tbody tr {
    border-color: #1e293b;
}

/* Inputs */
body.dark-mode input,
body.dark-mode select,
body.dark-mode textarea {
    background: #020617;
    color: #e5e7eb;
    border: 1px solid #1e293b;
}

/* Titles */
body.dark-mode h1,
body.dark-mode h2,
body.dark-mode h3,
body.dark-mode h4,
body.dark-mode h5 {
    color: #f9fafb;
}

/* Sidebar */
body.dark-mode .sidebar {
    background: linear-gradient(180deg, #020617, #020617);
}


/* Action buttons text color */
.action-btn {
    color: #000 !important;   /* black text */
    font-weight: 500;
}

/* Hover effect */
.action-btn:hover {
    color: #4f4d4d !important; /* grey text */
}

/* Dark mode: keep same behavior */
body.dark-mode .action-btn {
    color: #000 !important;   /* still black */
}

body.dark-mode .action-btn:hover {
    color: #0b0c0d !important; /* lighter grey */
}

    </style>
</head>

<body>



<!-- SIDEBAR -->
<div class="sidebar">
    <h4>UPF - Digi Link</h4>

    <a href="{{ route('dashboard') }}" 
       class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('home') }}" 
   class="nav-link {{ request()->is('home*') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i> Home
</a>

    <a href="{{ route('orphans.index') }}" 
       class="nav-link {{ request()->is('orphans*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Inmates
    </a>

    <!-- ✅ NEW VOLUNTEERS MENU -->
    <a href="{{ route('volunteers.index') }}" 
       class="nav-link {{ request()->is('volunteers*') ? 'active' : '' }}">
        <i class="bi bi-person-heart"></i> Volunteers
    </a>

    <a href="{{ route('sponsors.index') }}" 
       class="nav-link {{ request()->is('sponsors*') ? 'active' : '' }}">
        <i class="bi bi-cash-coin"></i> Staffs
    </a>

    <form method="POST" action="{{ route('logout') }}" class="logout-btn">
        @csrf
        <button class="btn btn-danger w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
    <h5>@yield('page-title', 'Dashboard')</h5>

    <div class="d-flex align-items-center gap-3">
    <button id="darkToggle" class="btn btn-sm btn-outline-secondary">
        🌙 Dark Mode
    </button>

    <form action="{{ route('global.search') }}" method="GET" style="width:260px;color:#e5e7eb;">
    <input
        type="text"
        name="q"
        placeholder="Search inmate or sponsor..."
        class="form-control form-control-sm"
        required
    >
</form>

    <div class="text-muted small">
        {{ auth()->user()->name }}
    </div>

    
</div>

</div>


    <!-- CONTENT -->
    <div class="content">
        @yield('content')
    </div>

</div>
<script>
    const toggleBtn = document.getElementById('darkToggle');

    function enableDark() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        toggleBtn.innerText = '☀️ Light Mode';
    }

    function disableDark() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        toggleBtn.innerText = '🌙 Dark Mode';
    }

    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
        enableDark();
    }

    toggleBtn.addEventListener('click', () => {
        document.body.classList.contains('dark-mode')
            ? disableDark()
            : enableDark();
    });
</script>
    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
