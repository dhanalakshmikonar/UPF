@extends('layouts.erp')

@section('page-title', 'Add Inmate')

@section('content')

<div class="card p-4">
    <h3>Add Sponsor</h3>

    @if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>

<div class="card p-4 shadow-sm">
    <h4 class="mb-4 border-bottom pb-2">Add Inmate Details</h4>

    <form method="POST" action="{{ route('orphans.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Admission Date</label>
                <input type="date" name="admission_date" class="form-control">
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option>Active</option>
                    <option>Adopted</option>
                    <option>Transferred</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
    <label>Photo</label>
    <input type="file" name="photo" class="form-control">
</div>

<div class="mb-3">
    <label>Aadhaar Document</label>
    <input type="file" name="aadhaar_document" class="form-control">
</div>


        <h5 class="mt-4 border-bottom pb-2">Background & Health</h5>

        <div class="mb-3">
            <label class="form-label">Background History</label>
            <textarea name="background_history" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
    <label class="form-label">Disability</label>
    <textarea name="disability_type" class="form-control" rows="3"></textarea>
</div>



        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Save Orphan
            </button>

            <a href="{{ route('orphans.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
