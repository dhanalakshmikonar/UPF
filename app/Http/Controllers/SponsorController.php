<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Support\ExcelValueFormatter;
use App\Support\SpreadsheetParser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::latest()->get();
        return view('sponsors.index', compact('sponsors'));
    }

    public function create()
    {
        return view('sponsors.create');
    }

    public function import(Request $request, SpreadsheetParser $parser)
    {
        $request->validate([
            'document' => 'required|file|mimes:csv,txt,xlsx|max:10240',
        ]);

        $file = $request->file('document');

        if (!$file) {
            return back()->with('error', 'Failed to upload staff file.');
        }

        try {
            $rows = $parser->parse(
                $file->getRealPath(),
                $file->getClientOriginalExtension(),
                'Staff'
            );

            if ($rows->isEmpty()) {
                return back()->with('error', 'The uploaded staff file does not contain any usable rows.');
            }

            $importedCount = 0;
            $skippedRows = [];

            DB::transaction(function () use ($rows, &$importedCount, &$skippedRows): void {
                foreach ($rows as $index => $row) {
                    $serialNo = $this->getRowValue($row, ['s_no', 'serial_no', 'sno']);
                    $name = $this->getRowValue($row, ['name', 'staff_name']);
                    $age = $this->getNullableIntegerValue($row, 'age');
                    $gender = $this->normalizeOption(
                        $this->getRowValue($row, ['gender', 'sex']),
                        ['Male', 'Female', 'Other']
                    ) ?? 'Other';
                    $dateOfBirth = $this->getNullableDateValue($row, ['date_of_birth', 'dob', 'd_o_b']);
                    $dateOfJoining = $this->getNullableDateValue($row, ['date_of_joining', 'doj', 'd_o_j', 'joining_date']);
                    $category = $this->getRowValue($row, ['category', 'department']);
                    $address = $this->getRowValue($row, 'address');
                    $home = $this->getRowValue($row, 'home');
                    $aadhaarNumber = ExcelValueFormatter::identifier($this->getRowValue($row, ['aadhaar_number', 'aadhar_number', 'aadhaar', 'aadhar']));
                    $contactNumber = ExcelValueFormatter::identifier($this->getRowValue($row, ['contact_number', 'contact_no', 'contact', 'phone', 'mobile']));
                    $cugNumber = ExcelValueFormatter::identifier($this->getRowValue($row, ['cug_number', 'cug_no', 'cug']));
                    $remarks = $this->getRowValue($row, ['remarks', 'remark']);

                    if ($name === null && $dateOfBirth === null) {
                        continue;
                    }

                    if ($name === null) {
                        $skippedRows[] = $index + 2;
                        continue;
                    }

                    $sponsor = Sponsor::query()
                        ->whereRaw('LOWER(TRIM(name)) = ?', [Str::lower(trim($name))])
                        ->first();

                    $data = [
                        'serial_no' => $serialNo,
                        'name' => $name,
                        'age' => $age,
                        'gender' => $gender,
                        'category' => $category,
                        'address' => $address,
                        'home' => $home,
                        'aadhaar_number' => $aadhaarNumber,
                        'contact_number' => $contactNumber,
                        'cug_number' => $cugNumber,
                        'remarks' => $remarks,
                        'date_of_birth' => $dateOfBirth,
                        'date_of_joining' => $dateOfJoining,
                        'phone' => $contactNumber,
                        'email' => $this->getRowValue($row, 'email'),
                        'amount_donated' => 0,
                        'donation_date' => null,
                        'aadhaar_document' => null,
                    ];

                    $photoPath = $this->storeEmbeddedPhoto($row, $name, $serialNo);

                    if ($photoPath !== null) {
                        if ($sponsor?->photo) {
                            $this->deletePublicPhoto($sponsor->photo);
                        }

                        $data['photo'] = $photoPath;
                    } elseif ($sponsor === null) {
                        $data['photo'] = null;
                    }

                    if ($sponsor) {
                        $sponsor->update($data);
                    } else {
                        Sponsor::create($data);
                    }

                    $importedCount++;
                }
            });
        } catch (\Throwable $e) {
            return back()->with('error', $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Unable to read this staff file. Please upload a valid CSV or XLSX file.');
        }

        if ($importedCount === 0) {
            return back()->with('error', 'No staff rows were imported. Please check the Excel file content.');
        }

        $message = 'Staff imported successfully.';

        if ($skippedRows !== []) {
            $message .= ' Skipped rows with missing required data: ' . implode(', ', $skippedRows) . '.';
        }

        return back()->with('success', $message);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'serial_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'home' => 'nullable|string|max:255',
            'aadhaar_number' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string',
            'cug_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'date_of_joining' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'amount_donated' => 'nullable|numeric',
            'donation_date' => 'nullable|date',
            'photo' => 'nullable|file',
            'aadhaar_document' => 'nullable|file',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->storeUploadedPhoto($request->file('photo'), $data['name'], $data['serial_no'] ?? null);
        }

        if ($request->hasFile('aadhaar_document')) {
            $data['aadhaar_document'] = $request->file('aadhaar_document')->store('sponsors/aadhaar', 'public');
        }

        $data['phone'] = $data['phone'] ?? $data['contact_number'] ?? null;
        $data['aadhaar_number'] = ExcelValueFormatter::identifier($data['aadhaar_number'] ?? null);
        $data['contact_number'] = ExcelValueFormatter::identifier($data['contact_number'] ?? null);
        $data['cug_number'] = ExcelValueFormatter::identifier($data['cug_number'] ?? null);
        $data['phone'] = ExcelValueFormatter::identifier($data['phone'] ?? null);
        $data['amount_donated'] = $data['amount_donated'] ?? 0;
        $data['donation_date'] = $data['donation_date'] ?? null;

        Sponsor::create($data);

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Staff added successfully');
    }

    public function show(Sponsor $sponsor)
    {
        return view('sponsors.show', compact('sponsor'));
    }

    public function edit(Sponsor $sponsor)
    {
        return view('sponsors.edit', compact('sponsor'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $data = $request->validate([
            'serial_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'home' => 'nullable|string|max:255',
            'aadhaar_number' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string',
            'cug_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'date_of_joining' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'amount_donated' => 'nullable|numeric',
            'donation_date' => 'nullable|date',
            'photo' => 'nullable|file',
            'aadhaar_document' => 'nullable|file',
        ]);

        if ($request->hasFile('photo')) {
            if ($sponsor->photo) {
                $this->deletePublicPhoto($sponsor->photo);
            }

            $data['photo'] = $this->storeUploadedPhoto($request->file('photo'), $data['name'], $data['serial_no'] ?? null);
        }

        if ($request->hasFile('aadhaar_document')) {
            if ($sponsor->aadhaar_document) {
                Storage::disk('public')->delete($sponsor->aadhaar_document);
            }

            $data['aadhaar_document'] = $request->file('aadhaar_document')->store('sponsors/aadhaar', 'public');
        }

        $data['phone'] = $data['phone'] ?? $data['contact_number'] ?? null;
        $data['aadhaar_number'] = ExcelValueFormatter::identifier($data['aadhaar_number'] ?? null);
        $data['contact_number'] = ExcelValueFormatter::identifier($data['contact_number'] ?? null);
        $data['cug_number'] = ExcelValueFormatter::identifier($data['cug_number'] ?? null);
        $data['phone'] = ExcelValueFormatter::identifier($data['phone'] ?? null);
        $data['amount_donated'] = $data['amount_donated'] ?? 0;
        $data['donation_date'] = $data['donation_date'] ?? null;

        $sponsor->update($data);

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Staff updated successfully');
    }

    public function clearAll()
    {
        DB::transaction(function (): void {
            $sponsors = Sponsor::all();

            foreach ($sponsors as $sponsor) {
                if ($sponsor->photo) {
                    $this->deletePublicPhoto($sponsor->photo);
                }

                if ($sponsor->aadhaar_document) {
                    Storage::disk('public')->delete($sponsor->aadhaar_document);
                }
            }

            Sponsor::query()->delete();
        });

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'All staff records have been deleted successfully.');
    }

    public function destroy(Sponsor $sponsor)
    {
        if ($sponsor->photo) {
            $this->deletePublicPhoto($sponsor->photo);
        }

        if ($sponsor->aadhaar_document) {
            Storage::disk('public')->delete($sponsor->aadhaar_document);
        }

        $sponsor->delete();

        return redirect()
            ->route('sponsors.index')
            ->with('success', 'Staff deleted successfully');
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

    private function storeUploadedPhoto(\Illuminate\Http\UploadedFile $file, string $name, ?string $serialNo): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) ? $extension : 'jpg';
        $fileName = $this->makePhotoFileName($name, $serialNo, $extension);
        $relativePath = 'img/staff/photos/' . $fileName;

        File::ensureDirectoryExists(public_path('img/staff/photos'));
        $file->move(public_path('img/staff/photos'), $fileName);

        return $relativePath;
    }

    private function deletePublicPhoto(string $path): void
    {
        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($normalizedPath, 'img/staff/photos/')) {
            File::delete(public_path($normalizedPath));
            return;
        }

        Storage::disk('public')->delete($normalizedPath);
    }

    private function storeEmbeddedPhoto(array $row, string $name, ?string $serialNo): ?string
    {
        $image = $this->getEmbeddedImage($row, ['photo', 'staff_photo']);

        if ($image === null) {
            return null;
        }

        $contents = base64_decode((string) ($image['contents'] ?? ''), true);

        if ($contents === false || $contents === '') {
            return null;
        }

        $extension = strtolower((string) ($image['extension'] ?? 'png'));
        $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) ? $extension : 'png';
        $path = 'img/staff/photos/' . $this->makePhotoFileName($name, $serialNo, $extension);

        File::ensureDirectoryExists(public_path('img/staff/photos'));
        File::put(public_path($path), $contents);

        return $path;
    }

    private function makePhotoFileName(string $name, ?string $serialNo, string $extension): string
    {
        $fileName = trim(($serialNo ?: 'staff') . '-' . Str::slug($name), '-');

        return $fileName . '-' . Str::random(8) . '.' . $extension;
    }

    private function getEmbeddedImage(array $row, array $keys): ?array
    {
        $images = $row['_embedded_images'] ?? [];

        if (!is_array($images)) {
            return null;
        }

        foreach ($keys as $key) {
            if (!empty($images[$key][0]) && is_array($images[$key][0])) {
                return $images[$key][0];
            }
        }

        foreach ($images as $items) {
            if (!empty($items[0]) && is_array($items[0])) {
                return $items[0];
            }
        }

        return null;
    }

    private function getNullableDateValue(array $row, array|string $keys): ?string
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
                return Carbon::create(1899, 12, 30)->addDays((int) floor($numeric))->format('Y-m-d');
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
                // Try next format.
            }
        }

        return null;
    }

    private function getNullableIntegerValue(array $row, array|string $keys): ?int
    {
        $value = $this->getRowValue($row, $keys);

        if ($value === null || !is_numeric($value)) {
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
