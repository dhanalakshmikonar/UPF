@extends('layouts.erp')

@section('page-title', 'Inmate Details')

@section('content')
<div class="card p-4">

    <h3 class="mb-3">Inmate Details</h3>

    <p><strong>Name:</strong> {{ $orphan->full_name }}</p>
    <p><strong>Gender:</strong> {{ $orphan->gender }}</p>
    <p><strong>Status:</strong> {{ $orphan->status }}</p>
    <p><strong>Date of Birth:</strong> {{ $orphan->date_of_birth }}</p>
    <p><strong>Admission Date:</strong> {{ $orphan->admission_date ?? '-' }}</p>

    <hr>

    @if($orphan->photo)
<img src="{{ asset('storage/'.$orphan->photo) }}" width="150">
@endif

@if($orphan->aadhaar_document)
<a href="{{ asset('storage/'.$orphan->aadhaar_document) }}">View Aadhaar</a>
@endif


    <h5 class="mt-3">Background & Health</h5>

<p>
    <strong>Background History:</strong><br>
    {{ $orphan->profile->background_history ?? '-' }}
</p>

<p><strong>Disability:</strong>
    {{ $orphan->profile->disability_type ?? 'None' }}
</p>




    <a href="{{ route('orphans.index') }}" class="btn btn-secondary mt-3">
        Back
    </a>

</div>
@endsection
