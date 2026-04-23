@extends('layouts.erp')

@section('page-title', 'Volunteer Details')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Volunteer Details</h3>
            <p class="text-muted mb-0">Community connect profile and contact information.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('volunteers.edit', $volunteer->id) }}" class="btn btn-outline-primary">Edit</a>
            <a href="{{ route('volunteers.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-3 detail-grid">
        <div class="col-md-4"><strong>Name</strong>{{ $volunteer->full_name }}</div>
        <div class="col-md-4"><strong>Gender</strong>{{ $volunteer->gender }}</div>
        <div class="col-md-4"><strong>Date of Birth</strong>@displayDate($volunteer->date_of_birth)</div>
        <div class="col-md-4"><strong>Phone</strong>{{ $volunteer->phone ?? '-' }}</div>
        <div class="col-md-4"><strong>Email</strong>{{ $volunteer->email ?? '-' }}</div>
        <div class="col-md-4"><strong>Status</strong><span class="badge text-bg-success">{{ $volunteer->status }}</span></div>
    </div>
</div>
@endsection
