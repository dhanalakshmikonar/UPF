@extends('layouts.erp')

@section('page-title', 'Inmate Details')

@section('content')
<div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Inmate Details</h3>
            <p class="text-muted mb-0">Detailed view in the same layout as the Excel sheet.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orphans.edit', $orphan->id) }}" class="btn btn-outline-primary">Edit</a>
            <a href="{{ route('orphans.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-2"><strong>S.NO</strong><div>{{ $orphan->serial_no ?? '-' }}</div></div>
        <div class="col-md-4"><strong>NAME</strong><div>{{ $orphan->full_name }}</div></div>
        <div class="col-md-2"><strong>AGE</strong><div>{{ $orphan->age ?? '-' }}</div></div>
        <div class="col-md-2"><strong>GENDER</strong><div>{{ $orphan->gender }}</div></div>
        <div class="col-md-2"><strong>STATUS</strong><div>{{ $orphan->status }}</div></div>

        <div class="col-md-3"><strong>D.O.B</strong><div>{{ $orphan->date_of_birth }}</div></div>
        <div class="col-md-3"><strong>D.O.J</strong><div>{{ $orphan->admission_date ?? '-' }}</div></div>
        <div class="col-md-3"><strong>CATEGORY</strong><div>{{ $orphan->category ?? '-' }}</div></div>
        <div class="col-md-3"><strong>HOME</strong><div>{{ $orphan->home ?? '-' }}</div></div>

        <div class="col-md-6"><strong>AADHAR NUMBER</strong><div>{{ $orphan->aadhaar_number ?? '-' }}</div></div>
        <div class="col-md-6"><strong>CONTACT NUMBER</strong><div>{{ $orphan->contact_number ?? '-' }}</div></div>

        <div class="col-12"><strong>ADDRESS</strong><div>{{ $orphan->address ?? '-' }}</div></div>
        <div class="col-12"><strong>REMARKS</strong><div>{{ $orphan->remarks ?? '-' }}</div></div>

        <div class="col-md-6">
            <strong>PHOTO</strong>
            <div class="mt-2">
                @if($orphan->photo)
                    <img src="{{ asset('storage/'.$orphan->photo) }}" width="140" class="img-thumbnail" alt="Inmate photo">
                @else
                    <div>-</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <strong>AADHAAR DOCUMENT</strong>
            <div class="mt-2">
                @if($orphan->aadhaar_document)
                    <a href="{{ asset('storage/'.$orphan->aadhaar_document) }}" target="_blank">View Aadhaar</a>
                @else
                    <div>-</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
