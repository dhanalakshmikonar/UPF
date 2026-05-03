@extends('layouts.erp')

@section('content')
<style>
    .staff-photo-large {
        width: min(100%, 320px);
        max-height: 420px;
        object-fit: contain;
        object-position: center;
        padding: 0.5rem;
        border-radius: 0.9rem;
        border: 1px solid #d8e4f2;
        background: #f8fbff;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.12);
    }
</style>

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

    <div class="row g-3 detail-grid">
        <div class="col-md-2"><strong>S.NO</strong><div>@displaySerial($sponsor->serial_no)</div></div>
        <div class="col-md-4"><strong>NAME</strong><div>{{ $sponsor->name }}</div></div>
        <div class="col-md-2"><strong>AGE</strong><div>{{ $sponsor->age ?? '-' }}</div></div>
        <div class="col-md-2"><strong>GENDER</strong><div>{{ $sponsor->gender ?? '-' }}</div></div>
        <div class="col-md-2"><strong>CATEGORY</strong><div>{{ $sponsor->category ?? '-' }}</div></div>

        <div class="col-md-3"><strong>D.O.B</strong><div>@displayDate($sponsor->date_of_birth)</div></div>
        <div class="col-md-3"><strong>D.O.J</strong><div>@displayDate($sponsor->date_of_joining)</div></div>
        <div class="col-md-3"><strong>HOME</strong><div>{{ $sponsor->home ?? '-' }}</div></div>
        <div class="col-md-3"><strong>AADHAR NUMBER</strong><div>@displayIdentifier($sponsor->aadhaar_number)</div></div>

        <div class="col-md-6"><strong>CONTACT NUMBER</strong><div>@displayIdentifier($sponsor->contact_number)</div></div>
        <div class="col-md-6"><strong>CUG NUMBER</strong><div>@displayIdentifier($sponsor->cug_number)</div></div>
        <div class="col-12"><strong>ADDRESS</strong><div>{{ $sponsor->address ?? '-' }}</div></div>
        <div class="col-12"><strong>REMARKS</strong><div>{{ $sponsor->remarks ?? '-' }}</div></div>

        <div class="col-md-6">
            <strong>PHOTO</strong>
            <div class="mt-2">
                @if($sponsor->photo)
                    <a href="{{ asset($sponsor->photo) }}" target="_blank" title="Open staff photo">
                        <img src="{{ asset($sponsor->photo) }}" class="staff-photo-large" alt="{{ $sponsor->name }} photo">
                    </a>
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
