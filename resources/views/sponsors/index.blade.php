@extends('layouts.erp')

@section('page-title', 'Staff')

@section('content')
<style>
    .staff-shell {
        display: grid;
        gap: 1rem;
    }

    .toolbar-card,
    .table-card {
        border: 1px solid #e5edf7;
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        background: #fff;
    }

    .toolbar-card {
        padding: 0.95rem 1rem;
        background: #fff;
    }

    .toolbar-top {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #132238;
        margin-bottom: 0.1rem;
    }

    .toolbar-copy {
        color: #5b6b80;
        margin: 0;
        font-size: 0.88rem;
    }

    .toolbar-actions {
        display: flex;
        gap: 0.55rem;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-end;
        max-width: none;
    }

    .import-form {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
        align-items: center;
    }

    .import-file {
        width: 300px;
        min-width: 240px;
        min-height: 38px;
        border-radius: 0.65rem;
        border: 1px solid #d6e3f5;
        padding: 0.45rem 0.7rem;
        background: #fff;
        font-size: 0.88rem;
    }

    .toolbar-btn {
        border-radius: 0.65rem;
        padding: 0.5rem 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        white-space: nowrap;
    }

    .stats-row {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
        align-items: stretch;
    }

    .stats-card {
        min-width: 128px;
        padding: 0.62rem 0.78rem;
        border-radius: 0.75rem;
        background: #f8fbff;
        border: 1px solid #e4edf7;
    }

    .stats-label {
        display: block;
        color: #607089;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.24rem;
    }

    .stats-value {
        font-size: 1.18rem;
        font-weight: 800;
        color: #132238;
        line-height: 1;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .staff-table {
        margin-bottom: 0;
        min-width: 1600px;
    }

    .staff-table thead th {
        background: #f7faff;
        color: #274263;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e4edf7;
        padding: 1rem 0.9rem;
        white-space: nowrap;
    }

    .staff-table tbody td {
        padding: 1rem 0.9rem;
        vertical-align: middle;
        border-color: #edf2f8;
        color: #24364c;
    }

    .staff-table th:last-child,
    .staff-table td:last-child {
        position: sticky;
        right: 0;
        z-index: 2;
        min-width: 148px;
        background: #fff;
        box-shadow: -12px 0 24px rgba(15, 23, 42, 0.08);
    }

    .staff-table thead th:last-child {
        z-index: 3;
        background: #f7faff;
    }

    .cell-strong {
        font-weight: 700;
        color: #10233d;
    }

    .cell-muted {
        color: #697b91;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .actions-row {
        display: flex;
        gap: 0.35rem;
        justify-content: flex-end;
        flex-wrap: nowrap;
    }

    .record-action {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.65rem;
    }

    .record-action.view {
        background: #eef4ff;
        color: #1d4ed8;
        border: 1px solid #cfe0ff;
    }

    .record-action.edit {
        background: #fff7e8;
        color: #b45309;
        border: 1px solid #fde4b2;
    }

    .record-action.delete {
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #ffd5dc;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #5d6f86;
    }

    .empty-state h4 {
        color: #17304d;
        font-weight: 800;
        margin-bottom: 0.6rem;
    }

    @media (max-width: 768px) {
        .toolbar-actions,
        .import-form {
            width: 100%;
            flex-wrap: wrap;
        }

        .import-file,
        .toolbar-btn {
            width: 100%;
        }
    }

    body.dark-mode .staff-table th:last-child,
    body.dark-mode .staff-table td:last-child {
        background: #020617;
        box-shadow: -12px 0 24px rgba(0, 0, 0, 0.35);
    }

    body.dark-mode .staff-table thead th:last-child {
        background: #0f172a;
    }
</style>

<div class="staff-shell">
    @if(session('success'))
        <div class="alert alert-success mb-0">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-0">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="toolbar-card">
        <div class="toolbar-top">
            <div>
                <h3 class="toolbar-title">Staff</h3>
                <p class="toolbar-copy">Manage staff records in the same format used by your Excel sheet.</p>
            </div>

            <div class="toolbar-actions">
                <form action="{{ route('sponsors.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                    @csrf
                    <input type="file" name="document" class="form-control import-file" accept=".xlsx,.csv,.txt" required>
                    <button type="submit" class="btn btn-primary toolbar-btn">
                        <i class="bi bi-cloud-arrow-up"></i> Upload
                    </button>
                </form>

                <form action="{{ route('sponsors.clear') }}" method="POST" onsubmit="return confirm('Delete all staff records?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger toolbar-btn">
                        <i class="bi bi-trash3"></i> Delete All
                    </button>
                </form>
            </div>
        </div>

        <div class="stats-row">
            <div class="stats-card">
                <span class="stats-label">Total Staff</span>
                <span class="stats-value">{{ $sponsors->count() }}</span>
            </div>
            <a href="{{ route('sponsors.create') }}" class="btn btn-outline-primary toolbar-btn">
                <i class="bi bi-person-plus"></i> Add Staff
            </a>
        </div>
    </section>

    <section class="table-card">
        <div class="table-wrap">
            <table class="table table-hover align-middle staff-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>NAME</th>
                        <th>AGE</th>
                        <th>D.O.B</th>
                        <th>D.O.J</th>
                        <th>GENDER</th>
                        <th>CATEGORY</th>
                        <th>ADDRESS</th>
                        <th>HOME</th>
                        <th>AADHAR NUMBER</th>
                        <th>CONTACT NUMBER</th>
                        <th>REMARKS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sponsors as $sponsor)
                    <tr>
                        <td>@displaySerial($sponsor->serial_no)</td>
                        <td class="cell-strong">{{ $sponsor->name }}</td>
                        <td>{{ $sponsor->age ?? '-' }}</td>
                        <td class="cell-muted">@displayDate($sponsor->date_of_birth)</td>
                        <td class="cell-muted">@displayDate($sponsor->date_of_joining)</td>
                        <td>{{ $sponsor->gender ?? '-' }}</td>
                        <td>{{ $sponsor->category ?? '-' }}</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit($sponsor->address ?? '-', 80) }}</td>
                        <td>{{ $sponsor->home ?? '-' }}</td>
                        <td>@displayIdentifier($sponsor->aadhaar_number)</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit(\App\Support\ExcelValueFormatter::identifier($sponsor->contact_number) ?? '-', 55) }}</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit($sponsor->remarks ?? '-', 60) }}</td>
                        <td>
                            <div class="actions-row">
                                <a href="{{ route('sponsors.show', $sponsor->id) }}" class="record-action view" title="View staff">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sponsors.edit', $sponsor->id) }}" class="record-action edit" title="Edit staff">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('sponsors.destroy', $sponsor->id) }}" method="POST" onsubmit="return confirm('Delete this staff record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="record-action delete" title="Delete staff">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13">
                            <div class="empty-state">
                                <h4>No staff records yet</h4>
                                <p class="mb-0">Upload the staff Excel file or add a staff member manually to get started.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
