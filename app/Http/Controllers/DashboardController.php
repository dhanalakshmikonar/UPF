<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use App\Models\Sponsor;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrphans = Orphan::count();

        $disabledOrphans = Orphan::whereHas('profile', function ($q) {
            $q->whereNotNull('disability_type')
              ->where('disability_type', '!=', '');
        })->count();

        $totalSponsors = Sponsor::count();

        $totalDonations = Sponsor::sum('amount_donated');

        return view('dashboard.index', compact(
            'totalOrphans',
            'disabledOrphans',
            'totalSponsors',
            'totalDonations'
        ));
    }
}
