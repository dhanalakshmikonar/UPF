@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Inmates List</h3>
        <a href="/orphans/create" class="btn btn-primary">Add Inmate</a>
    </div>

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Status</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orphans as $orphan)
            <tr>
                <td>{{ $orphan->full_name }}</td>
                <td>{{ $orphan->gender }}</td>
                <td>
                    <span class="badge bg-success">{{ $orphan->status }}</span>
                </td>
                <td>
                    <a href="{{ route('orphans.show', $orphan->id) }}"
   class="btn btn-sm btn-info"
   style="color:#000; font-weight:500;"
   onmouseover="this.style.color='#6b7280'"
   onmouseout="this.style.color='#000'">
   View
</a>

<a href="{{ route('orphans.edit', $orphan->id) }}"
   class="btn btn-sm btn-warning"
   style="color:#000; font-weight:500;"
   onmouseover="this.style.color='#6b7280'"
   onmouseout="this.style.color='#000'">
   Edit
</a>

<form action="{{ route('orphans.destroy', $orphan->id) }}"
              method="POST"
              style="display:inline;"
              onsubmit="return confirm('Are you sure you want to delete this orphan?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-danger"
                    title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </form>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
