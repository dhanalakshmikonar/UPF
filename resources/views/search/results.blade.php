@extends('layouts.erp')

@section('page-title', 'Search Results')

@section('content')
<h4>Results for "{{ $q }}"</h4>

<h5 class="mt-4">Inmates</h5>
<ul>
@forelse($orphans as $orphan)
    <li>
        <a href="/orphans/{{ $orphan->id }}">{{ $orphan->full_name }}</a>
    </li>
@empty
    <li>No inmates found</li>
@endforelse
</ul>

<h5 class="mt-4">Sponsors</h5>
<ul>
@forelse($sponsors as $sponsor)
    <li>
        <a href="/sponsors/{{ $sponsor->id }}">{{ $sponsor->name }}</a>
    </li>
@empty
    <li>No sponsors found</li>
@endforelse
</ul>
@endsection
