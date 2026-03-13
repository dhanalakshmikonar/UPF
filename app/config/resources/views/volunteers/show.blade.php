@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <h3>Volunteer Details</h3>

    <p><strong>Name:</strong> {{ $volunteer->full_name }}</p>
    <p><strong>Gender:</strong> {{ $volunteer->gender }}</p>
    <p><strong>Date of Birth:</strong> {{ $volunteer->date_of_birth }}</p>
    <p><strong>Phone:</strong> {{ $volunteer->phone }}</p>
    <p><strong>Email:</strong> {{ $volunteer->email }}</p>
    <p><strong>Status:</strong> {{ $volunteer->status }}</p>

    <a href="{{ route('volunteers.index') }}" class="btn btn-secondary mt-3">
        Back
    </a>
</div>
@endsection