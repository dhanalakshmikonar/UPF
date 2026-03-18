@extends('layouts.erp')

@section('content')
<div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="mb-1">Staff Details</h3>
            <p class="text-muted mb-0">Detailed view in the same layout as the Excel sheet.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sponsors.edit', $sponsor->id) }}" class="btn btn-outline-primary">Edit</a>
            <a href="{{ route('sponsors.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-2"><strong>S.NO</strong><div>{{ $sponsor->serial_no ?? '-' }}</div></div>
        <div class="col-md-4"><strong>NAME</strong><div>{{ $sponsor->name }}</div></div>
        <div class="col-md-2"><strong>AGE</strong><div>{{ $sponsor->age ?? '-' }}</div></div>
        <div class="col-md-2"><strong>GENDER</strong><div>{{ $sponsor->gender ?? '-' }}</div></div>
        <div class="col-md-2"><strong>CATEGORY</strong><div>{{ $sponsor->category ?? '-' }}</div></div>

        <div class="col-md-3"><strong>D.O.B</strong><div>{{ $sponsor->date_of_birth ?? '-' }}</div></div>
        <div class="col-md-3"><strong>D.O.J</strong><div>{{ $sponsor->date_of_joining ?? '-' }}</div></div>
        <div class="col-md-3"><strong>HOME</strong><div>{{ $sponsor->home ?? '-' }}</div></div>
        <div class="col-md-3"><strong>AADHAR NUMBER</strong><div>{{ $sponsor->aadhaar_number ?? '-' }}</div></div>

        <div class="col-12"><strong>CONTACT NUMBER</strong><div>{{ $sponsor->contact_number ?? '-' }}</div></div>
        <div class="col-12"><strong>ADDRESS</strong><div>{{ $sponsor->address ?? '-' }}</div></div>
        <div class="col-12"><strong>REMARKS</strong><div>{{ $sponsor->remarks ?? '-' }}</div></div>

        <div class="col-md-6">
            <strong>PHOTO</strong>
            <div class="mt-2">
                @if($sponsor->photo)
                    <img src="{{ asset('storage/'.$sponsor->photo) }}" width="140" class="img-thumbnail" alt="Staff photo">
                @else
                    <div>-</div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <strong>AADHAAR DOCUMENT</strong>
            <div class="mt-2">
                @if($sponsor->aadhaar_document)
                    <a href="{{ asset('storage/'.$sponsor->aadhaar_document) }}" target="_blank">View Aadhaar</a>
                @else
                    <div>-</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
