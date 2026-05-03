@extends('layouts.erp')

@section('content')
<div class="card p-4 shadow-sm erp-form-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Add Staff</h3>
            <p class="text-muted mb-0">Create a staff record using the same structure as the Excel sheet.</p>
        </div>
        <a href="{{ route('sponsors.index') }}" class="btn btn-outline-secondary">Back to Staff</a>
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

    <form method="POST" action="{{ route('sponsors.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">S.NO</label>
                <input type="text" name="serial_no" class="form-control" value="{{ old('serial_no') }}">
            </div>

            <div class="col-md-5">
                <label class="form-label">NAME</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">AGE</label>
                <input type="number" min="0" name="age" class="form-control" value="{{ old('age') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">GENDER</label>
                <select name="gender" class="form-select">
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">D.O.B</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                <div class="form-text">Date of Birth</div>
            </div>

            <div class="col-md-3">
                <label class="form-label">D.O.J</label>
                <input type="date" name="date_of_joining" class="form-control" value="{{ old('date_of_joining') }}">
                <div class="form-text">Date of Joining</div>
            </div>

            <div class="col-md-3">
                <label class="form-label">CATEGORY</label>
                <input type="text" name="category" class="form-control" value="{{ old('category') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">HOME</label>
                <input type="text" name="home" class="form-control" value="{{ old('home') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">AADHAR NUMBER</label>
                <input type="text" name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">CONTACT NUMBER</label>
                <textarea name="contact_number" class="form-control" rows="2">{{ old('contact_number') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">CUG NUMBER</label>
                <input type="text" name="cug_number" class="form-control" value="{{ old('cug_number') }}">
            </div>

            <div class="col-12">
                <label class="form-label">ADDRESS</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">REMARKS</label>
                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">PHOTO</label>
                <input type="file" name="photo" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">AADHAAR DOCUMENT</label>
                <input type="file" name="aadhaar_document" class="form-control">
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Save Staff
            </button>
            <a href="{{ route('sponsors.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
