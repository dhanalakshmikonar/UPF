<?php

namespace App\Http\Controllers;

use App\Models\HomeDocument;
use App\Models\HomeDocumentRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class HomeDocumentController extends Controller
{
    private const ALLOWED_CATEGORIES = [
        'boys-hostel',
        'girls-hostel',
        'oam',
        'oaw',
        'dam',
        'daw',
        'mr-mi-m',
        'mr-mi-w',
    ];

    public function store(Request $request, string $category): RedirectResponse
    {
        if (!in_array($category, self::ALLOWED_CATEGORIES, true)) {
            return back()->with('error', 'Invalid category provided.');
        }

        $request->validate([
            'document' => 'required|file|mimes:csv,txt,xlsx|max:10240',
        ]);

        $file = $request->file('document');

        if (!$file) {
            return back()->with('error', 'Failed to upload document.');
        }

        $originalName = $file->getClientOriginalName();
        $storedName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs(
            "home_documents/{$category}",
            "{$storedName}.{$extension}",
            'public'
        );

        if (!$path) {
            return back()->with('error', 'Failed to save the uploaded file.');
        }

        try {
            $rows = $this->parseUploadedFile(storage_path('app/public/' . $path), $extension);

            if ($rows->isEmpty()) {
                Storage::disk('public')->delete($path);
                return back()->with('error', 'The uploaded file does not contain any usable rows.');
            }

            DB::transaction(function () use ($category, $path, $originalName, $rows): void {
                $this->deleteCategoryData($category);

                $document = HomeDocument::create([
                    'category' => $category,
                    'file_path' => $path,
                    'original_name' => $originalName,
                ]);

                foreach ($rows as $row) {
                    HomeDocumentRecord::create([
                        'home_document_id' => $document->id,
                        'category' => $category,
                        'data' => $row,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);

            return back()->with('error', 'Unable to read this file. Please upload a valid CSV or XLSX file.');
        }

        return back()->with('success', 'File uploaded and data imported successfully.');
    }

    public function clearCategory(string $category): RedirectResponse
    {
        if (!in_array($category, self::ALLOWED_CATEGORIES, true)) {
            return back()->with('error', 'Invalid category provided.');
        }

        $this->deleteCategoryData($category);

        return back()->with('success', 'All data for this category has been cleared successfully.');
    }

    private function deleteCategoryData(string $category): void
    {
        $documents = HomeDocument::where('category', $category)->get();

        foreach ($documents as $document) {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        }

        HomeDocumentRecord::where('category', $category)->delete();
        HomeDocument::where('category', $category)->delete();
    }

    private function parseUploadedFile(string $fullPath, string $extension): Collection
    {
        return match ($extension) {
            'csv', 'txt' => $this->parseCsv($fullPath),
            'xlsx' => $this->parseXlsx($fullPath),
            default => throw new RuntimeException('Unsupported file type.'),
        };
    }

    private function parseCsv(string $fullPath): Collection
    {
        $handle = fopen($fullPath, 'r');

        if ($handle === false) {
            throw new RuntimeException('Unable to open CSV file.');
        }

        $rows = collect();
        $headers = null;

        while (($row = fgetcsv($handle)) !== false) {
            $row = array_map(
                static fn ($value) => is_string($value) ? trim($value) : $value,
                $row
            );

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            if ($headers === null) {
                $headers = $this->normalizeHeaders($row);
                continue;
            }

            $rows->push($this->combineHeadersAndRow($headers, $row));
        }

        fclose($handle);

        return $rows;
    }

    private function parseXlsx(string $fullPath): Collection
    {
        $zip = new ZipArchive();

        if ($zip->open($fullPath) !== true) {
            throw new RuntimeException('Unable to open XLSX file.');
        }

        $sharedStrings = $this->extractSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            throw new RuntimeException('Worksheet data not found.');
        }

        $xml = simplexml_load_string($sheetXml);

        if (!$xml instanceof SimpleXMLElement) {
            throw new RuntimeException('Invalid worksheet XML.');
        }

        $rows = collect();
        $headers = null;

        foreach ($xml->sheetData->row as $sheetRow) {
            $rowValues = [];

            foreach ($sheetRow->c as $cell) {
                $rowValues[] = $this->extractCellValue($cell, $sharedStrings);
            }

            if ($this->rowIsEmpty($rowValues)) {
                continue;
            }

            if ($headers === null) {
                $headers = $this->normalizeHeaders($rowValues);
                continue;
            }

            $rows->push($this->combineHeadersAndRow($headers, $rowValues));
        }

        return $rows;
    }

    private function extractSharedStrings(ZipArchive $zip): array
    {
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedStringsXml === false) {
            return [];
        }

        $xml = simplexml_load_string($sharedStringsXml);

        if (!$xml instanceof SimpleXMLElement) {
            return [];
        }

        $strings = [];

        foreach ($xml->si as $item) {
            if (isset($item->t)) {
                $strings[] = (string) $item->t;
                continue;
            }

            $text = '';

            foreach ($item->r as $run) {
                $text .= (string) $run->t;
            }

            $strings[] = $text;
        }

        return $strings;
    }

    private function extractCellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) ($cell['t'] ?? '');
        $value = isset($cell->v) ? (string) $cell->v : '';

        if ($type === 's') {
            return trim($sharedStrings[(int) $value] ?? '');
        }

        if ($type === 'inlineStr') {
            return trim((string) ($cell->is->t ?? ''));
        }

        return trim($value);
    }

    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];

        foreach (array_values($headers) as $index => $header) {
            $label = trim((string) $header);
            $key = Str::slug($label, '_');
            $normalized[] = $key !== '' ? $key : 'column_' . ($index + 1);
        }

        return $normalized;
    }

    private function combineHeadersAndRow(array $headers, array $row): array
    {
        $row = array_values($row);
        $combined = [];

        foreach ($headers as $index => $header) {
            $combined[$header] = isset($row[$index]) ? trim((string) $row[$index]) : null;
        }

        return $combined;
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
