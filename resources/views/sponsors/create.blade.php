
@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <h3>Add Staff</h3>

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

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Amount Donated</label>
            <input type="number" step="0.01" name="amount_donated" class="form-control" required>
        </div>

        <div class="mb-3">
    <label>Photo</label>
    <input type="file" name="photo" class="form-control">
</div>

<div class="mb-3">
    <label>Aadhaar Document</label>
    <input type="file" name="aadhaar_document" class="form-control">
</div>


        <button class="btn btn-primary">Save Sponsor</button>
    </form>
</div>
@endsection
