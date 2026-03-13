<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrphanController extends Controller
{

    /* =======================
       LIST ALL ORPHANS
    ======================== */
    public function index()
    {
        $orphans = Orphan::with('profile')->get();
        return view('orphans.index', compact('orphans'));
    }


    /* =======================
       SHOW CREATE FORM
    ======================== */
    public function create()
    {
        return view('orphans.create');
    }


    /* =======================
       STORE ORPHAN
    ======================== */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'status' => 'required',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_document' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        $photoPath = null;
        $aadhaarPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('orphans/photos', 'public');
        }

        if ($request->hasFile('aadhaar_document')) {
            $aadhaarPath = $request->file('aadhaar_document')
                ->store('orphans/aadhaar', 'public');
        }

        $orphan = Orphan::create([
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'admission_date' => $request->admission_date,
            'status' => $request->status,
            'photo' => $photoPath,
            'aadhaar_document' => $aadhaarPath
        ]);

        $orphan->profile()->create([
            'background_history' => $request->background_history,
            'disability_type' => $request->disability_type
        ]);

        return redirect()->route('orphans.index')
            ->with('success', 'Orphan added successfully');
    }


    /* =======================
       SHOW SINGLE ORPHAN
    ======================== */
    public function show(Orphan $orphan)
    {
        $orphan->load('profile');
        return view('orphans.show', compact('orphan'));
    }


    /* =======================
       EDIT FORM
    ======================== */
    public function edit(Orphan $orphan)
    {
        $orphan->load('profile');
        return view('orphans.edit', compact('orphan'));
    }


    /* =======================
       UPDATE ORPHAN (FIXED)
    ======================== */
    public function update(Request $request, Orphan $orphan)
    {
        $request->validate([
            'full_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'status' => 'required',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_document' => 'nullable|file|mimes:pdf|max:5120'
        ]);

        $photoPath = $orphan->photo;
        $aadhaarPath = $orphan->aadhaar_document;

        // Upload new photo if exists
        if ($request->hasFile('photo')) {

            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $request->file('photo')
                ->store('orphans/photos', 'public');
        }

        // Upload new aadhaar if exists
        if ($request->hasFile('aadhaar_document')) {

            if ($aadhaarPath && Storage::disk('public')->exists($aadhaarPath)) {
                Storage::disk('public')->delete($aadhaarPath);
            }

            $aadhaarPath = $request->file('aadhaar_document')
                ->store('orphans/aadhaar', 'public');
        }

        // Update orphan
        $orphan->update([
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'admission_date' => $request->admission_date,
            'status' => $request->status,
            'photo' => $photoPath,
            'aadhaar_document' => $aadhaarPath
        ]);

        // Update profile
        $orphan->profile()->updateOrCreate(
            ['orphan_id' => $orphan->id],
            [
                'background_history' => $request->background_history,
                'disability_type' => $request->disability_type
            ]
        );

        return redirect()->route('orphans.index')
            ->with('success', 'Updated successfully');
    }


    /* =======================
       DELETE
    ======================== */
    public function destroy(Orphan $orphan)
    {
        if ($orphan->photo && Storage::disk('public')->exists($orphan->photo)) {
            Storage::disk('public')->delete($orphan->photo);
        }

        if ($orphan->aadhaar_document && Storage::disk('public')->exists($orphan->aadhaar_document)) {
            Storage::disk('public')->delete($orphan->aadhaar_document);
        }

        if ($orphan->profile) {
            $orphan->profile->delete();
        }

        $orphan->delete();

        return redirect()->route('orphans.index')
            ->with('success', 'Deleted successfully');
    }

}
