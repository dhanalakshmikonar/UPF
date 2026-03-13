@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Volunteers</h3>
        <a href="{{ route('volunteers.create') }}" class="btn btn-primary">
             Add Volunteer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>sr.no</th>
                <th>Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Phone</th>
                <th>Status</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($volunteers as $volunteer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $volunteer->full_name }}</td>
                    <td>{{ $volunteer->gender }}</td>
                    <td>{{ $volunteer->date_of_birth }}</td>
                    <td>{{ $volunteer->phone }}</td>
                    <td>{{ $volunteer->status }}</td>
                    <td>
                        <a href="{{ route('volunteers.show', $volunteer->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('volunteers.edit', $volunteer->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('volunteers.destroy', $volunteer->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No volunteers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection