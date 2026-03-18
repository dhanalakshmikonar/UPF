@extends('layouts.erp')

@section('content')
<style>
    .inmates-shell {
        display: grid;
        gap: 1.5rem;
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
        padding: 1.4rem 1.5rem;
        background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
    }

    .toolbar-top {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .toolbar-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: #132238;
        margin-bottom: 0.25rem;
    }

    .toolbar-copy {
        color: #5b6b80;
        margin: 0;
    }

    .toolbar-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
    }

    .import-form {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .import-file {
        min-width: 250px;
        border-radius: 0.85rem;
        border: 1px solid #d6e3f5;
        padding: 0.65rem 0.85rem;
        background: #fff;
    }

    .toolbar-btn {
        border-radius: 0.85rem;
        padding: 0.72rem 1rem;
        font-weight: 700;
    }

    .stats-row {
        display: flex;
        gap: 0.9rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .stats-card {
        min-width: 150px;
        padding: 1rem 1.1rem;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.88);
        border: 1px solid #d8e4ff;
    }

    .stats-label {
        display: block;
        color: #607089;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.4rem;
    }

    .stats-value {
        font-size: 1.55rem;
        font-weight: 800;
        color: #132238;
        line-height: 1;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .inmates-table {
        margin-bottom: 0;
        min-width: 1600px;
    }

    .inmates-table thead th {
        background: #f7faff;
        color: #274263;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e4edf7;
        padding: 1rem 0.9rem;
        white-space: nowrap;
    }

    .inmates-table tbody td {
        padding: 1rem 0.9rem;
        vertical-align: middle;
        border-color: #edf2f8;
        color: #24364c;
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

    .status-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        background: #e9f9ef;
        color: #18794e;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .actions-row {
        display: flex;
        gap: 0.45rem;
        flex-wrap: wrap;
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
        }

        .import-file,
        .toolbar-btn {
            width: 100%;
        }
    }
</style>

@php
    $activeCount = $orphans->where('status', 'Active')->count();
@endphp

<div class="inmates-shell">
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
                <h3 class="toolbar-title">Inmates</h3>
                <p class="toolbar-copy">Manage inmate records in the same format used by your Excel sheet.</p>
            </div>

            <div class="toolbar-actions">
                <form action="{{ route('orphans.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                    @csrf
                    <input type="file" name="document" class="form-control import-file" accept=".xlsx,.csv,.txt" required>
                    <button type="submit" class="btn btn-primary toolbar-btn">Upload File</button>
                </form>

                <form action="{{ route('orphans.clear') }}" method="POST" onsubmit="return confirm('Delete all inmate records?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger toolbar-btn">Delete All</button>
                </form>

                <a href="{{ route('orphans.create') }}" class="btn btn-outline-primary toolbar-btn">Add Inmate</a>
            </div>
        </div>

        <div class="stats-row">
            <div class="stats-card">
                <span class="stats-label">Total Inmates</span>
                <span class="stats-value">{{ $orphans->count() }}</span>
            </div>
            <div class="stats-card">
                <span class="stats-label">Active Records</span>
                <span class="stats-value">{{ $activeCount }}</span>
            </div>
        </div>
    </section>

    <section class="table-card">
        <div class="table-wrap">
            <table class="table table-hover align-middle inmates-table">
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
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orphans as $orphan)
                    <tr>
                        <td>{{ $orphan->serial_no ?? '-' }}</td>
                        <td class="cell-strong">{{ $orphan->full_name }}</td>
                        <td>{{ $orphan->age ?? '-' }}</td>
                        <td class="cell-muted">{{ $orphan->date_of_birth }}</td>
                        <td class="cell-muted">{{ $orphan->admission_date ?? '-' }}</td>
                        <td>{{ $orphan->gender }}</td>
                        <td>{{ $orphan->category ?? '-' }}</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit($orphan->address ?? '-', 80) }}</td>
                        <td>{{ $orphan->home ?? '-' }}</td>
                        <td>{{ $orphan->aadhaar_number ?? '-' }}</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit($orphan->contact_number ?? '-', 55) }}</td>
                        <td class="cell-muted">{{ \Illuminate\Support\Str::limit($orphan->remarks ?? '-', 60) }}</td>
                        <td><span class="status-chip">{{ $orphan->status }}</span></td>
                        <td>
                            <div class="actions-row">
                                <a href="{{ route('orphans.show', $orphan->id) }}" class="btn btn-sm btn-info" style="color:#000; font-weight:600;">View</a>
                                <a href="{{ route('orphans.edit', $orphan->id) }}" class="btn btn-sm btn-warning" style="color:#000; font-weight:600;">Edit</a>
                                <form action="{{ route('orphans.destroy', $orphan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inmate?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14">
                            <div class="empty-state">
                                <h4>No inmate records yet</h4>
                                <p class="mb-0">Upload the Excel file or add an inmate manually to get started.</p>
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
