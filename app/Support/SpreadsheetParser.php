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
        $embeddedImages = $this->extractWorksheetImages($zip, $sheetPath, $sheetXml ?: '');
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
            $rowNumber = isset($sheetRow['r']) ? (int) $sheetRow['r'] : count($worksheetRows) + 1;

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

            $worksheetRows[] = [
                'number' => $rowNumber,
                'values' => $rowValues,
            ];
        }

        if ($worksheetRows === []) {
            return $rows;
        }

        $rowValues = array_map(static fn (array $row): array => $row['values'], $worksheetRows);
        $headerRowIndex = $this->detectHeaderRowIndex($rowValues);
        $headers = $this->normalizeHeaders($this->padRow($worksheetRows[$headerRowIndex]['values'], $maxColumns));

        foreach (array_slice($worksheetRows, $headerRowIndex + 1) as $rowValues) {
            $combined = $this->combineHeadersAndRow($headers, $this->padRow($rowValues['values'], $maxColumns));
            $imagesForRow = $embeddedImages[$rowValues['number']] ?? [];

            if ($imagesForRow !== []) {
                $combined['_embedded_images'] = $this->mapImagesToHeaders($headers, $imagesForRow);
            }

            $rows->push($combined);
        }

        return $rows;
    }

    private function extractWorksheetImages(ZipArchive $zip, string $sheetPath, string $sheetXml): array
    {
        if ($sheetXml === '') {
            return [];
        }

        $worksheet = simplexml_load_string($sheetXml);

        if (!$worksheet instanceof SimpleXMLElement || !isset($worksheet->drawing)) {
            return [];
        }

        $relationshipId = (string) $worksheet->drawing->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];

        if ($relationshipId === '') {
            return [];
        }

        $sheetDirectory = dirname($sheetPath);
        $sheetRelationshipsPath = $sheetDirectory . '/_rels/' . basename($sheetPath) . '.rels';
        $sheetRelationshipsXml = $zip->getFromName($sheetRelationshipsPath);

        if ($sheetRelationshipsXml === false) {
            return [];
        }

        $drawingPath = $this->resolveRelationshipTarget($sheetRelationshipsXml, $relationshipId, $sheetDirectory);

        if ($drawingPath === null) {
            return [];
        }

        $drawingXml = $zip->getFromName($drawingPath);

        if ($drawingXml === false) {
            return [];
        }

        $drawingDirectory = dirname($drawingPath);
        $drawingRelationshipsPath = $drawingDirectory . '/_rels/' . basename($drawingPath) . '.rels';
        $drawingRelationshipsXml = $zip->getFromName($drawingRelationshipsPath);
        $mediaRelationships = $drawingRelationshipsXml === false
            ? []
            : $this->extractRelationshipTargets($drawingRelationshipsXml, $drawingDirectory);

        if ($mediaRelationships === []) {
            return [];
        }

        $drawing = simplexml_load_string($drawingXml);

        if (!$drawing instanceof SimpleXMLElement) {
            return [];
        }

        $images = [];
        $anchors = $drawing->xpath('//*[local-name()="oneCellAnchor" or local-name()="twoCellAnchor"]') ?: [];

        foreach ($anchors as $anchor) {
            $from = $anchor->xpath('./*[local-name()="from"]')[0] ?? null;
            $to = $anchor->xpath('./*[local-name()="to"]')[0] ?? null;
            $blip = $anchor->xpath('.//*[local-name()="blip"]')[0] ?? null;

            if (!$from instanceof SimpleXMLElement || !$blip instanceof SimpleXMLElement) {
                continue;
            }

            $rowNode = $from->xpath('./*[local-name()="row"]')[0] ?? null;
            $columnNode = $from->xpath('./*[local-name()="col"]')[0] ?? null;
            $fromRow = (int) ($rowNode instanceof SimpleXMLElement ? (string) $rowNode : '0');
            $toRowNode = $to instanceof SimpleXMLElement ? ($to->xpath('./*[local-name()="row"]')[0] ?? null) : null;
            $toRow = $toRowNode instanceof SimpleXMLElement ? (int) (string) $toRowNode : $fromRow;
            $row = (int) floor(($fromRow + $toRow) / 2) + 1;
            $column = (int) ($columnNode instanceof SimpleXMLElement ? (string) $columnNode : '0');
            $embedId = (string) $blip->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['embed'];
            $mediaPath = $mediaRelationships[$embedId] ?? null;

            if ($mediaPath === null) {
                continue;
            }

            $contents = $zip->getFromName($mediaPath);

            if ($contents === false || $contents === '') {
                continue;
            }

            $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION)) ?: 'png';

            $images[$row][$column][] = [
                'contents' => base64_encode($contents),
                'extension' => $extension,
            ];
        }

        return $images;
    }

    private function mapImagesToHeaders(array $headers, array $imagesForRow): array
    {
        $mapped = [];

        foreach ($imagesForRow as $column => $images) {
            $header = $headers[$column] ?? 'column_' . ($column + 1);
            $mapped[$header] = array_merge($mapped[$header] ?? [], $images);
        }

        $photoColumn = array_search('photo', $headers, true);

        if (!isset($mapped['photo']) && $photoColumn !== false) {
            foreach ($mapped as $header => $images) {
                $imageColumn = array_search($header, $headers, true);

                if ($imageColumn !== false && abs($photoColumn - $imageColumn) <= 1) {
                    $mapped['photo'] = $images;
                    break;
                }
            }
        }

        return $mapped;
    }

    private function resolveRelationshipTarget(string $relationshipsXml, string $relationshipId, string $baseDirectory): ?string
    {
        $targets = $this->extractRelationshipTargets($relationshipsXml, $baseDirectory);

        return $targets[$relationshipId] ?? null;
    }

    private function extractRelationshipTargets(string $relationshipsXml, string $baseDirectory): array
    {
        $relationships = simplexml_load_string($relationshipsXml);

        if (!$relationships instanceof SimpleXMLElement) {
            return [];
        }

        $targets = [];

        foreach ($relationships->Relationship as $relationship) {
            $id = (string) ($relationship['Id'] ?? '');
            $target = (string) ($relationship['Target'] ?? '');

            if ($id === '' || $target === '' || str_starts_with($target, 'http')) {
                continue;
            }

            $targets[$id] = $this->normalizeZipPath($baseDirectory . '/' . $target);
        }

        return $targets;
    }

    private function normalizeZipPath(string $path): string
    {
        $parts = [];

        foreach (explode('/', str_replace('\\', '/', $path)) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }

            if ($part === '..') {
                array_pop($parts);
                continue;
            }

            $parts[] = $part;
        }

        return implode('/', $parts);
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
