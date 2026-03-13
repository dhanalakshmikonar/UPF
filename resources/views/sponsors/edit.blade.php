@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <h3>Edit Staff</h3>

    <form method="POST" action="{{ route('sponsors.update', $sponsor->id) }}" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input class="form-control" name="name"
                   value="{{ $sponsor->name }}">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input class="form-control" name="phone"
                   value="{{ $sponsor->phone }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input class="form-control" name="email"
                   value="{{ $sponsor->email }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea class="form-control" name="address">
{{ $sponsor->address }}
            </textarea>
        </div>

        <div class="mb-3">
            <label>Amount Donated</label>
            <input class="form-control" name="amount_donated"
                   value="{{ $sponsor->amount_donated }}">
        </div>

        <div class="mb-3">
            <label>Donation Date</label>
            <input type="date" class="form-control"
                   name="donation_date"
                   value="{{ $sponsor->donation_date }}">
        </div>

        <hr>
<h5>Documents</h5>

<div class="mb-3">
    <label class="form-label">Photo</label>

    @if($sponsor->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/'.$sponsor->photo) }}" width="120" class="img-thumbnail">
        </div>
    @endif

    <input type="file" class="form-control" name="photo">
</div>

<div class="mb-3">
    <label class="form-label">Aadhaar Document</label>

    @if($sponsor->aadhaar_document)
        <div class="mb-2">
            <a href="{{ asset('storage/'.$sponsor->aadhaar_document) }}" target="_blank">
                View Current Aadhaar
            </a>
        </div>
    @endif

    <input type="file" class="form-control" name="aadhaar_document">
</div>


        <button class="btn btn-primary">Update Sponsor</button>
        <a href="/sponsors" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
