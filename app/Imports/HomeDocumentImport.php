<?php

namespace App\Imports;

use App\Models\HomeDocumentRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HomeDocumentImport implements ToCollection, WithHeadingRow
{
    private $documentId;
    private $category;

    public function __construct($documentId, $category)
    {
        $this->documentId = $documentId;
        $this->category = $category;
    }

    public function collection(Collection $rows)
    {
        // First, check if there are previous records for this category and optionally clear them
        // HomeDocumentRecord::where('category', $this->category)->delete();

        foreach ($rows as $row) {
            // Keep rows with at least one non-null value
            if ($row->filter()->isNotEmpty()) {
                HomeDocumentRecord::create([
                    'home_document_id' => $this->documentId,
                    'category' => $this->category,
                    'data' => $row->toArray(), // We use WithHeadingRow so this becomes a nice associative array
                ]);
            }
        }
    }
}
