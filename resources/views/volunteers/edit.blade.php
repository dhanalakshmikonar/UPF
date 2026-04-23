@extends('layouts.erp')

@section('page-title', 'Edit Volunteer')

@section('content')
<div class="card p-4 erp-form-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Edit Volunteer</h3>
            <p class="text-muted mb-0">Update community support and contact information.</p>
        </div>
        <a href="{{ route('volunteers.index') }}" class="btn btn-outline-secondary">Back to Volunteers</a>
    </div>

    <form method="POST" action="{{ route('volunteers.update', $volunteer->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="full_name" value="{{ old('full_name', $volunteer->full_name) }}" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="Male" {{ old('gender', $volunteer->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $volunteer->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $volunteer->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $volunteer->date_of_birth) }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $volunteer->email) }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active" {{ old('status', $volunteer->status) === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status', $volunteer->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary"><i class="bi bi-save"></i> Update Volunteer</button>
            <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
