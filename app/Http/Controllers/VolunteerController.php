<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::all();
        $pageTitle = request()->routeIs('community-connect.*')
            ? 'Community Connect Program'
            : 'Volunteers';

        return view('volunteers.index', compact('volunteers', 'pageTitle'));
    }

    public function create()
    {
        return view('volunteers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        Volunteer::create($request->all());

        return redirect()->route('volunteers.index')
                         ->with('success', 'Volunteer added successfully.');
    }

    public function show(Volunteer $volunteer)
    {
        return view('volunteers.show', compact('volunteer'));
    }

    public function edit(Volunteer $volunteer)
    {
        return view('volunteers.edit', compact('volunteer'));
    }

    public function update(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'full_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $volunteer->update($request->all());

        return redirect()->route('volunteers.index')
                         ->with('success', 'Volunteer updated successfully.');
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();

        return redirect()->route('volunteers.index')
                         ->with('success', 'Volunteer deleted successfully.');
    }
}
