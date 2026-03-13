<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $orphans = Orphan::where('full_name', 'like', "%$q%")->get();
        $sponsors = Sponsor::where('name', 'like', "%$q%")->get();

        return view('search.results', compact('q', 'orphans', 'sponsors'));
    }
}
