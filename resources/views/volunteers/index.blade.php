@extends('layouts.erp')

@section('page-title', $pageTitle ?? 'Volunteers')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
        <div>
            <h3 class="mb-1">{{ $pageTitle ?? 'Volunteers' }}</h3>
            <p class="text-muted mb-0">Track volunteer engagement and community support records.</p>
        </div>
        <a href="{{ route('volunteers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Volunteer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="p-3 rounded border bg-light">
                <div class="text-muted small text-uppercase fw-bold">Total Volunteers</div>
                <div class="fs-4 fw-bold">{{ $volunteers->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 rounded border bg-light">
                <div class="text-muted small text-uppercase fw-bold">Active</div>
                <div class="fs-4 fw-bold">{{ $volunteers->where('status', 'Active')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>SR.NO</th>
                <th>Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Phone</th>
                <th>Status</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($volunteers as $volunteer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $volunteer->full_name }}</td>
                    <td>{{ $volunteer->gender }}</td>
                    <td>@displayDate($volunteer->date_of_birth)</td>
                    <td>{{ $volunteer->phone }}</td>
                    <td><span class="badge text-bg-success">{{ $volunteer->status }}</span></td>
                    <td>
                        <a href="{{ route('volunteers.show', $volunteer->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('volunteers.edit', $volunteer->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('volunteers.destroy', $volunteer->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No volunteers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
