@extends('layouts.erp')

@section('content')

@php
$headers = [];
if(isset($records) && $records->count() > 0) {
$firstRow = $records->first()->data;
if(is_array($firstRow)) {
$headers = array_keys($firstRow);
}
@endphp

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>MR & MI Men Datatable</h2>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to
            Dashboard</a>
    </div>

    @if(isset($records) && $records->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        @foreach($headers as $header)
                            <th class="text-capitalize">{{ str_replace('_', ' ', $header) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @foreach($headers as $header)
                                <td>{{ $record->data[$header] ?? '-' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i> No Excel data has been uploaded for this category yet.
        </div>
    @endif
</div>

@endsection