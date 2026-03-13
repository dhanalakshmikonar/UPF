@extends('layouts.erp')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Staff List</h3>
        <a href="/sponsors/create" class="btn btn-primary">Add Staff</a>
    </div>

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Amount</th>
                <th width="200">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sponsors as $sponsor)
            <tr>
                <td>{{ $sponsor->name }}</td>
                <td>{{ $sponsor->phone }}</td>
                <td><strong>₹ {{ $sponsor->amount_donated }}</strong></td>
                <td>
                    <a href="/sponsors/{{ $sponsor->id }}"  class="btn btn-sm btn-info"
   style="color:#000; font-weight:500;"
   onmouseover="this.style.color='#6b7280'"
   onmouseout="this.style.color='#000'">View</a>
                    <a href="/sponsors/{{ $sponsor->id }}/edit"  class="btn btn-sm btn-warning"
   style="color:#000; font-weight:500;"
   onmouseover="this.style.color='#6b7280'"
   onmouseout="this.style.color='#000'">Edit</a>

   <form action="{{ route('sponsors.destroy', $sponsor->id) }}"
      method="POST"
      style="display:inline;">
    @csrf
    @method('DELETE')

    <button type="submit"
        class="btn btn-sm btn-danger"
        onclick="return confirm('Delete this sponsor?')">
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
