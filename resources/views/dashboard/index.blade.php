@extends('layouts.erp')

@section('page-title', 'Dashboard')

@section('content')
<style>
    .dashboard-shell {
        display: grid;
        gap: 1.1rem;
    }

    .dashboard-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(280px, 0.75fr);
        gap: 1rem;
        align-items: stretch;
    }

    .executive-panel {
        padding: 1.5rem;
        border: 1px solid var(--border);
        border-radius: 0.9rem;
        background: #fff;
        box-shadow: var(--shadow-sm);
    }

    .executive-label {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: var(--primary-soft);
        color: #1d4ed8;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .executive-panel h2 {
        margin: 0.9rem 0 0.35rem;
        color: #132238;
        font-size: 1.75rem;
        font-weight: 850;
    }

    .executive-panel p {
        max-width: 760px;
        margin: 0;
        color: var(--muted);
        line-height: 1.65;
    }

    .status-panel {
        padding: 1.2rem;
        border: 1px solid var(--border);
        border-radius: 0.9rem;
        background: #fff;
        box-shadow: var(--shadow-sm);
    }

    .status-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #eef2f7;
    }

    .status-line:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .status-dot {
        width: 0.65rem;
        height: 0.65rem;
        border-radius: 999px;
        background: #16a34a;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1);
    }

    .module-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1rem;
    }

    .module-card {
        display: flex;
        gap: 0.9rem;
        align-items: flex-start;
        height: 100%;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 0.9rem;
        background: #fff;
        box-shadow: var(--shadow-sm);
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .module-card:hover {
        transform: translateY(-2px);
        border-color: #b8cdf8;
        box-shadow: var(--shadow-md);
    }

    .module-icon {
        width: 44px;
        height: 44px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 1.25rem;
    }

    .module-card h5 {
        margin: 0 0 0.25rem;
        color: #132238;
        font-weight: 800;
        font-size: 1rem;
    }

    .module-card p {
        margin: 0;
        color: var(--muted);
        font-size: 0.88rem;
        line-height: 1.45;
    }

    .workbench-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 0.45fr);
        gap: 1rem;
    }

    .panel-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .panel-title h4 {
        margin: 0;
        color: #132238;
        font-size: 1.05rem;
        font-weight: 850;
    }

    .task-row {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid #eef2f7;
    }

    .task-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .task-icon {
        width: 36px;
        height: 36px;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #2563eb;
    }

    body.dark-mode .executive-panel,
    body.dark-mode .status-panel,
    body.dark-mode .module-card {
        background: #020617;
        border-color: #1e293b;
    }

    body.dark-mode .executive-panel h2,
    body.dark-mode .module-card h5,
    body.dark-mode .panel-title h4 {
        color: #f8fafc;
    }

    body.dark-mode .status-line,
    body.dark-mode .task-row {
        border-color: #1e293b;
    }

    body.dark-mode .task-icon {
        background: #0f172a;
    }

    @media (max-width: 992px) {
        .dashboard-hero,
        .workbench-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-shell">
    <section class="dashboard-hero">
        <div class="executive-panel">
            <span class="executive-label"><i class="bi bi-grid-1x2"></i> Administration Console</span>
            <h2>UPF Operations Dashboard</h2>
            <p>Central workspace for managing inmates, staff, volunteer programs, and home-wise records with quick access to the areas used most often by the administration team.</p>
        </div>

      <!-- Space of status panel -->
    </section>

    <section class="module-grid">
        <a href="{{ route('orphans.index') }}" class="module-card">
            <div class="module-icon" style="background:#eef4ff;color:#2563eb;"><i class="bi bi-people"></i></div>
            <div>
                <h5>Inmate Records</h5>
                <p>Manage profiles, status, documents, home, category, and contact details.</p>
            </div>
        </a>

        <a href="{{ route('sponsors.index') }}" class="module-card">
            <div class="module-icon" style="background:#edfdf4;color:#16a34a;"><i class="bi bi-person-badge"></i></div>
            <div>
                <h5>Staff Records</h5>
                <p>Maintain staff details, identity documents, assignments, and remarks.</p>
            </div>
        </a>

        <a href="{{ route('volunteers.index') }}" class="module-card">
            <div class="module-icon" style="background:#f0fdfa;color:#0f766e;"><i class="bi bi-person-heart"></i></div>
            <div>
                <h5>Volunteers</h5>
                <p>Track volunteer contacts, status, and program participation.</p>
            </div>
        </a>

        <a href="{{ route('community-connect.index') }}" class="module-card">
            <div class="module-icon" style="background:#fff7e8;color:#d97706;"><i class="bi bi-diagram-3"></i></div>
            <div>
                <h5>Community Connect</h5>
                <p>Coordinate community engagement and support program records.</p>
            </div>
        </a>
    </section>

    <section class="workbench-grid">
        <div class="card p-4">
            <div class="panel-title">
                <h4>Administration Workbench</h4>
                <span class="text-muted small">Daily actions</span>
            </div>

            <a href="{{ route('orphans.create') }}" class="task-row">
                <span class="task-icon"><i class="bi bi-person-plus"></i></span>
                <span>
                    <span class="fw-bold d-block text-dark">Create inmate record</span>
                    <span class="text-muted small">Add a new profile with documents and status.</span>
                </span>
            </a>

            <a href="{{ route('sponsors.create') }}" class="task-row">
                <span class="task-icon"><i class="bi bi-person-badge"></i></span>
                <span>
                    <span class="fw-bold d-block text-dark">Create staff record</span>
                    <span class="text-muted small">Register staff information and contact details.</span>
                </span>
            </a>

            <a href="{{ route('volunteers.create') }}" class="task-row">
                <span class="task-icon"><i class="bi bi-person-heart"></i></span>
                <span>
                    <span class="fw-bold d-block text-dark">Add volunteer</span>
                    <span class="text-muted small">Create a community support contact profile.</span>
                </span>
            </a>
        </div>

        <div class="card p-4">
            <div class="panel-title">
                <h4>Record Controls</h4>
            </div>
            <p class="text-muted">Use the sidebar to access home-wise directories, uploads, and specialized program areas. Search remains available from the top bar for quick lookup.</p>
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('global.search') }}?q=a" class="btn btn-outline-secondary">
                    <i class="bi bi-search"></i> Search Records
                </a>
                <a href="{{ route('directory.home') }}" class="btn btn-outline-primary">
                    <i class="bi bi-folder2-open"></i> Directory Home
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
