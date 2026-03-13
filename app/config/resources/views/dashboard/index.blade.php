@extends('layouts.erp')

@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard">

    <h2 class="page-title mb-4" style="color:#545658;">
        ERP Dashboard
    </h2>

    <!-- KPI CARDS -->
    <div class="stats-grid mb-4">

        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div>
                <h4>Total Inmates</h4>
                <p>{{ $totalOrphans }}</p>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-heart-pulse-fill"></i></div>
            <div>
                <h4>Disabled Inmates</h4>
                <p>{{ $disabledOrphans }}</p>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div>
                <h4>Total Staffs</h4>
                <p>{{ $totalSponsors }}</p>
            </div>
        </div>

        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div>
                <h4>Total Donations</h4>
                <p>₹ {{ number_format($totalDonations, 2) }}</p>
            </div>
        </div>

    </div>

    

@endsection
