@extends('layouts.erp')

@section('page-title', 'Edit Inmate')

@section('content')
<div class="card p-4">

    <h3 class="mb-3">Edit Inmate</h3>

    <form method="POST" action="{{ route('orphans.update', $orphan->id) }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <h5 class="mt-3">Basic Information</h5>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input
                class="form-control"
                name="full_name"
                value="{{ $orphan->full_name }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select class="form-select" name="gender">
                <option value="Male" {{ $orphan->gender=='Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $orphan->gender=='Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ $orphan->gender=='Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input
                type="date"
                class="form-control"
                name="date_of_birth"
                value="{{ $orphan->date_of_birth }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Admission Date</label>
            <input
                type="date"
                class="form-control"
                name="admission_date"
                value="{{ $orphan->admission_date }}"
            >
        </div>

        <div class="mb-4">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="Active" {{ $orphan->status=='Active' ? 'selected' : '' }}>Active</option>
                <option value="Adopted" {{ $orphan->status=='Adopted' ? 'selected' : '' }}>Adopted</option>
                <option value="Transferred" {{ $orphan->status=='Transferred' ? 'selected' : '' }}>Transferred</option>
            </select>
        </div>

        <hr>
        <h5>Background & Health</h5>

        <div class="mb-3">
            <label class="form-label">Background History</label>
            <textarea
                class="form-control"
                name="background_history"
                rows="3"
            >{{ $orphan->profile->background_history ?? '' }}</textarea>
        </div>

        <div class="mb-3">
    <label>Disability</label>
    <textarea class="form-control" name="disability_type" rows="3">
{{ $orphan->profile->disability_type ?? '' }}
    </textarea>
</div>

<hr>
<h5>Documents</h5>

<div class="mb-3">
    <label class="form-label">Photo</label>

    @if($orphan->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/'.$orphan->photo) }}" width="120" class="img-thumbnail">
        </div>
    @endif

    <input type="file" class="form-control" name="photo">
</div>

<div class="mb-3">
    <label class="form-label">Aadhaar Document</label>

    @if($orphan->aadhaar_document)
        <div class="mb-2">
            <a href="{{ asset('storage/'.$orphan->aadhaar_document) }}" target="_blank">
                View Current Aadhaar
            </a>
        </div>
    @endif

    <input type="file" class="form-control" name="aadhaar_document">
</div>


        <div class="d-flex gap-2">
            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Update Orphan
            </button>

            <a href="{{ route('orphans.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
