<?php

namespace App\Http\Controllers;

use App\Models\Orphan;
use App\Support\SpreadsheetParser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

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

    public function import(Request $request, SpreadsheetParser $parser)
    {
        $request->validate([
            'document' => 'required|file|mimes:csv,txt,xlsx|max:10240',
        ]);

        $file = $request->file('document');

        if (!$file) {
            return back()->with('error', 'Failed to upload inmate file.');
        }

        try {
            $rows = $parser->parse($file->getRealPath(), $file->getClientOriginalExtension());

            if ($rows->isEmpty()) {
                return back()->with('error', 'The uploaded inmate file does not contain any usable rows.');
            }

            $importedCount = 0;
            $skippedRows = [];

            DB::transaction(function () use ($rows, &$importedCount, &$skippedRows): void {
                foreach ($rows as $index => $row) {
                    $serialNo = $this->getRowValue($row, ['s_no', 'serial_no', 'sno']);
                    $fullName = $this->getRowValue($row, ['full_name', 'name']);
                    $age = $this->getNullableIntegerValue($row, 'age');
                    $gender = $this->normalizeOption(
                        $this->getRowValue($row, ['gender', 'sex']),
                        ['Male', 'Female', 'Other']
                    ) ?? 'Other';
                    $category = $this->getRowValue($row, ['category', 'disability_type']);
                    $address = $this->getRowValue($row, ['address', 'background_history', 'background']);
                    $home = $this->getRowValue($row, 'home');
                    $aadhaarNumber = $this->getRowValue($row, ['aadhaar_number', 'aadhar_number', 'aadhaar', 'aadhar']);
                    $contactNumber = $this->getRowValue($row, ['contact_number', 'contact', 'phone']);
                    $remarks = $this->getRowValue($row, ['remarks', 'remark']);
                    $dateOfBirth = $this->getNullableDateValue($row, ['date_of_birth', 'dob', 'd_o_b'], $index, 'date_of_birth');
                    $status = $this->normalizeOption(
                        $this->getRowValue($row, 'status') ?: 'Active',
                        ['Active', 'Adopted', 'Transferred']
                    ) ?? 'Active';

                    if ($fullName === null && $dateOfBirth === null) {
                        continue;
                    }

                    if ($fullName === null || $dateOfBirth === null) {
                        $skippedRows[] = $index + 2;
                        continue;
                    }

                    $orphan = Orphan::create([
                        'serial_no' => $serialNo,
                        'full_name' => $fullName,
                        'age' => $age,
                        'gender' => $gender,
                        'category' => $category,
                        'address' => $address,
                        'home' => $home,
                        'aadhaar_number' => $aadhaarNumber,
                        'contact_number' => $contactNumber,
                        'remarks' => $remarks,
                        'date_of_birth' => $dateOfBirth,
                        'admission_date' => $this->getNullableDateValue($row, ['admission_date', 'joined_date', 'd_o_j']),
                        'status' => $status,
                        'photo' => null,
                        'aadhaar_document' => null,
                    ]);

                    $orphan->profile()->create([
                        'background_history' => $this->getRowValue($row, ['background_history', 'background', 'address', 'remarks']),
                        'disability_type' => $this->getRowValue($row, ['disability_type', 'disability', 'category']),
                    ]);

                    $importedCount++;
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Unable to read this inmate file. Please upload a valid CSV or XLSX file.');
        }

        if ($importedCount === 0) {
            return back()->with('error', 'No inmate rows were imported. Please check the Excel file content.');
        }

        $message = 'Inmates imported successfully.';

        if ($skippedRows !== []) {
            $message .= ' Skipped rows with missing required data: ' . implode(', ', $skippedRows) . '.';
        }

        return back()->with('success', $message);
    }


    /* =======================
       STORE ORPHAN
    ======================== */
    public function store(Request $request)
    {
        $request->validate([
            'serial_no' => 'nullable|string|max:255',
            'full_name' => 'required',
            'age' => 'nullable|integer|min:0',
            'gender' => 'required',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'home' => 'nullable|string|max:255',
            'aadhaar_number' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'date_of_birth' => 'required',
            'admission_date' => 'nullable|date',
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
            'serial_no' => $request->serial_no,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'category' => $request->category,
            'address' => $request->address,
            'home' => $request->home,
            'aadhaar_number' => $request->aadhaar_number,
            'contact_number' => $request->contact_number,
            'remarks' => $request->remarks,
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
            'serial_no' => 'nullable|string|max:255',
            'full_name' => 'required',
            'age' => 'nullable|integer|min:0',
            'gender' => 'required',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'home' => 'nullable|string|max:255',
            'aadhaar_number' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'date_of_birth' => 'required',
            'admission_date' => 'nullable|date',
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
            'serial_no' => $request->serial_no,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'category' => $request->category,
            'address' => $request->address,
            'home' => $request->home,
            'aadhaar_number' => $request->aadhaar_number,
            'contact_number' => $request->contact_number,
            'remarks' => $request->remarks,
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

    public function clearAll()
    {
        DB::transaction(function (): void {
            $orphans = Orphan::with('profile')->get();

            foreach ($orphans as $orphan) {
                if ($orphan->photo && Storage::disk('public')->exists($orphan->photo)) {
                    Storage::disk('public')->delete($orphan->photo);
                }

                if ($orphan->aadhaar_document && Storage::disk('public')->exists($orphan->aadhaar_document)) {
                    Storage::disk('public')->delete($orphan->aadhaar_document);
                }
            }

            Orphan::query()->delete();
        });

        return redirect()->route('orphans.index')
            ->with('success', 'All inmate records have been deleted successfully.');
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

    private function getRowValue(array $row, array|string $keys): ?string
    {
        foreach ((array) $keys as $key) {
            $value = $row[$key] ?? null;

            if ($value !== null && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    private function getNullableDateValue(array $row, array|string $keys, ?int $index = null, ?string $label = null): ?string
    {
        $value = $this->getRowValue($row, $keys);

        if ($value === null) {
            return null;
        }

        $normalized = $this->normalizeImportedDate($value);

        if ($normalized !== null) {
            return $normalized;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeImportedDate(string $value): ?string
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return null;
        }

        if (is_numeric($trimmed)) {
            $numeric = (float) $trimmed;

            if ($numeric >= 1) {
                $days = (int) floor($numeric);
                return Carbon::create(1899, 12, 30)->addDays($days)->format('Y-m-d');
            }
        }

        $formats = [
            'Y-m-d',
            'd-m-Y',
            'd/m/Y',
            'm/d/Y',
            'd.m.Y',
            'Y/m/d',
            'd M Y',
            'd-M-Y',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $trimmed);

                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                // Try next known format.
            }
        }

        return null;
    }

    private function getNullableIntegerValue(array $row, array|string $keys): ?int
    {
        $value = $this->getRowValue($row, $keys);

        if ($value === null) {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private function normalizeOption(?string $value, array $allowed): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalizedValue = Str::lower(trim($value));

        foreach ($allowed as $option) {
            if ($normalizedValue === Str::lower($option)) {
                return $option;
            }
        }

        return null;
    }
}
