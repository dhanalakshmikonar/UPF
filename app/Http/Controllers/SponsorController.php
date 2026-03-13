<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{

    /* =======================
       LIST ALL SPONSORS
    ======================== */
    public function index()
    {
        $sponsors = Sponsor::latest()->get();
        return view('sponsors.index', compact('sponsors'));
    }


    /* =======================
       SHOW CREATE FORM
    ======================== */
    public function create()
    {
        return view('sponsors.create');
    }


    /* =======================
       STORE SPONSOR
    ======================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string',
            'amount_donated'  => 'required|numeric',
            'donation_date'   => 'nullable|date',

            // Accept ALL file types without restriction
            'photo'            => 'nullable|file',
            'aadhaar_document' => 'nullable|file',
        ]);

        // Upload photo
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('sponsors/photos', 'public');
        }

        // Upload Aadhaar
        if ($request->hasFile('aadhaar_document')) {
            $data['aadhaar_document'] = $request->file('aadhaar_document')
                ->store('sponsors/aadhaar', 'public');
        }

        Sponsor::create($data);

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Sponsor added successfully');
    }


    /* =======================
       SHOW SINGLE SPONSOR
    ======================== */
    public function show(Sponsor $sponsor)
    {
        return view('sponsors.show', compact('sponsor'));
    }


    /* =======================
       EDIT FORM
    ======================== */
    public function edit(Sponsor $sponsor)
    {
        return view('sponsors.edit', compact('sponsor'));
    }


    /* =======================
       UPDATE SPONSOR
    ======================== */
    public function update(Request $request, Sponsor $sponsor)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string',
            'amount_donated'  => 'required|numeric',
            'donation_date'   => 'nullable|date',

            // Accept ALL file types without restriction
            'photo'            => 'nullable|file',
            'aadhaar_document' => 'nullable|file',
        ]);

        // Replace photo if new uploaded
        if ($request->hasFile('photo')) {

            if ($sponsor->photo) {
                Storage::disk('public')->delete($sponsor->photo);
            }

            $data['photo'] = $request->file('photo')
                ->store('sponsors/photos', 'public');
        }

        // Replace Aadhaar if new uploaded
        if ($request->hasFile('aadhaar_document')) {

            if ($sponsor->aadhaar_document) {
                Storage::disk('public')->delete($sponsor->aadhaar_document);
            }

            $data['aadhaar_document'] = $request->file('aadhaar_document')
                ->store('sponsors/aadhaar', 'public');
        }

        $sponsor->update($data);

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Sponsor updated successfully');
    }


    /* =======================
       DELETE SPONSOR
    ======================== */
    public function destroy(Sponsor $sponsor)
    {
        // Delete photo
        if ($sponsor->photo) {
            Storage::disk('public')->delete($sponsor->photo);
        }

        // Delete Aadhaar
        if ($sponsor->aadhaar_document) {
            Storage::disk('public')->delete($sponsor->aadhaar_document);
        }

        $sponsor->delete();

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Sponsor deleted successfully');
    }
}
