@extends('layouts.erp')

@section('page-title', 'Add Volunteer')

@section('content')
<div class="card p-4 erp-form-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Add Volunteer</h3>
            <p class="text-muted mb-0">Create a community support profile for volunteer program tracking.</p>
        </div>
        <a href="{{ route('volunteers.index') }}" class="btn btn-outline-secondary">Back to Volunteers</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('volunteers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary"><i class="bi bi-save"></i> Save Volunteer</button>
            <a href="{{ route('volunteers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
