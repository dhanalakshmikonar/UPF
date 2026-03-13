@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <h3>Edit Volunteer</h3>

    <form method="POST" action="{{ route('volunteers.update', $volunteer->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="full_name"
                   value="{{ $volunteer->full_name }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="Male" {{ $volunteer->gender=='Male'?'selected':'' }}>Male</option>
                <option value="Female" {{ $volunteer->gender=='Female'?'selected':'' }}>Female</option>
                <option value="Other" {{ $volunteer->gender=='Other'?'selected':'' }}>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth"
                   value="{{ $volunteer->date_of_birth }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone"
                   value="{{ $volunteer->phone }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active" {{ $volunteer->status=='Active'?'selected':'' }}>Active</option>
                <option value="Inactive" {{ $volunteer->status=='Inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>

        <button class="btn btn-primary">Update Volunteer</button>
    </form>
</div>
@endsection