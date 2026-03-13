@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <h3>Staff Details</h3>

    <p><strong>Name:</strong> {{ $sponsor->name }}</p>
    <p><strong>Phone:</strong> {{ $sponsor->phone }}</p>
    <p><strong>Email:</strong> {{ $sponsor->email }}</p>
    <p><strong>Address:</strong> {{ $sponsor->address ?? 'N/A' }}</p>
    <p><strong>Amount Donated:</strong> ₹ {{ $sponsor->amount_donated }}</p>
    <p><strong>Donation Date:</strong>
        {{ $sponsor->donation_date ? \Carbon\Carbon::parse($sponsor->donation_date)->format('d M Y') : 'N/A' }}
    </p>

     @if($sponsor->photo)
<img src="{{ asset('storage/'.$sponsor->photo) }}" width="150">
@endif

@if($sponsor->aadhaar_document)
<a href="{{ asset('storage/'.$sponsor->aadhaar_document) }}">View Aadhaar</a>
@endif

    <a href="/sponsors" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
