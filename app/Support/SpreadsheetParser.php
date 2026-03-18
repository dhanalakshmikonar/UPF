<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class SpreadsheetParser
{
    public function parse(string $fullPath, string $extension, ?string $sheetName = null): Collection
    {
        return match (strtolower($extension)) {
            'csv', 'txt' => $this->parseCsv($fullPath),
            'xlsx' => $this->parseXlsx($fullPath, $sheetName),
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

    private function parseXlsx(string $fullPath, ?string $sheetName = null): Collection
    {
        $zip = new ZipArchive();

        if ($zip->open($fullPath) !== true) {
            throw new RuntimeException('Unable to open XLSX file.');
        }

        $sharedStrings = $this->extractSharedStrings($zip);
        $sheetPath = $this->resolveWorksheetPath($zip, $sheetName) ?? 'xl/worksheets/sheet1.xml';
        $sheetXml = $zip->getFromName($sheetPath);
        $zip->close();

        if ($sheetXml === false) {
            throw new RuntimeException('Worksheet data not found.');
        }

        $xml = simplexml_load_string($sheetXml);

        if (!$xml instanceof SimpleXMLElement) {
            throw new RuntimeException('Invalid worksheet XML.');
        }

        $rows = collect();
        $worksheetRows = [];
        $maxColumns = 0;

        foreach ($xml->sheetData->row as $sheetRow) {
            $rowValues = [];

            foreach ($sheetRow->c as $cell) {
                $reference = (string) ($cell['r'] ?? '');
                $columnIndex = $this->getColumnIndexFromReference($reference);

                if ($columnIndex === null) {
                    $rowValues[] = $this->extractCellValue($cell, $sharedStrings);
                    continue;
                }

                $rowValues[$columnIndex] = $this->extractCellValue($cell, $sharedStrings);
            }

            if ($rowValues !== []) {
                ksort($rowValues);
                $maxColumns = max($maxColumns, max(array_keys($rowValues)) + 1);
            }

            if ($this->rowIsEmpty($rowValues)) {
                continue;
            }

            $worksheetRows[] = $rowValues;
        }

        if ($worksheetRows === []) {
            return $rows;
        }

        $headerRowIndex = $this->detectHeaderRowIndex($worksheetRows);
        $headers = $this->normalizeHeaders($this->padRow($worksheetRows[$headerRowIndex], $maxColumns));

        foreach (array_slice($worksheetRows, $headerRowIndex + 1) as $rowValues) {
            $rows->push($this->combineHeadersAndRow($headers, $this->padRow($rowValues, $maxColumns)));
        }

        return $rows;
    }

    private function resolveWorksheetPath(ZipArchive $zip, ?string $sheetName): ?string
    {
        if ($sheetName === null || trim($sheetName) === '') {
            return null;
        }

        $workbookXml = $zip->getFromName('xl/workbook.xml');
        $relationsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookXml === false || $relationsXml === false) {
            return null;
        }

        $workbook = simplexml_load_string($workbookXml);
        $relations = simplexml_load_string($relationsXml);

        if (!$workbook instanceof SimpleXMLElement || !$relations instanceof SimpleXMLElement) {
            return null;
        }

        $sheetRelationshipId = null;

        foreach ($workbook->sheets->sheet as $sheet) {
            $name = trim((string) ($sheet['name'] ?? ''));

            if (strcasecmp($name, $sheetName) === 0) {
                $sheetRelationshipId = (string) $sheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
                break;
            }
        }

        if ($sheetRelationshipId === null || $sheetRelationshipId === '') {
            return null;
        }

        foreach ($relations->Relationship as $relationship) {
            if ((string) ($relationship['Id'] ?? '') !== $sheetRelationshipId) {
                continue;
            }

            $target = (string) ($relationship['Target'] ?? '');

            if ($target === '') {
                return null;
            }

            return str_starts_with($target, 'xl/')
                ? $target
                : 'xl/' . ltrim($target, '/');
        }

        return null;
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

    private function getColumnIndexFromReference(string $reference): ?int
    {
        if ($reference === '') {
            return null;
        }

        if (!preg_match('/^[A-Z]+/', strtoupper($reference), $matches)) {
            return null;
        }

        $column = $matches[0];
        $index = 0;

        foreach (str_split($column) as $character) {
            $index = ($index * 26) + (ord($character) - 64);
        }

        return $index - 1;
    }

    private function padRow(array $row, int $maxColumns): array
    {
        if ($maxColumns <= 0) {
            return array_values($row);
        }

        $padded = array_fill(0, $maxColumns, null);

        foreach ($row as $index => $value) {
            $padded[$index] = is_string($value) ? trim($value) : $value;
        }

        return $padded;
    }

    private function detectHeaderRowIndex(array $rows): int
    {
        $bestIndex = 0;
        $bestScore = -1;

        foreach ($rows as $index => $row) {
            $nonEmpty = 0;
            $stringCells = 0;

            foreach ($row as $value) {
                $trimmed = trim((string) $value);

                if ($trimmed === '') {
                    continue;
                }

                $nonEmpty++;

                if (!is_numeric($trimmed)) {
                    $stringCells++;
                }
            }

            $score = ($nonEmpty * 2) + $stringCells;

            if ($nonEmpty >= 2 && $score > $bestScore) {
                $bestScore = $score;
                $bestIndex = $index;
            }
        }

        return $bestIndex;
    }
}
