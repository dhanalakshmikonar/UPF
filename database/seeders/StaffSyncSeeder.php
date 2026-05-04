<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StaffSyncSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/staff_sync.json');

        if (!File::exists($path)) {
            $this->command?->error('Staff sync data file not found.');
            return;
        }

        $rows = json_decode(File::get($path), true);

        if (!is_array($rows)) {
            $this->command?->error('Staff sync data file is invalid.');
            return;
        }

        $updated = 0;

        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $sponsor = Sponsor::query()
                ->whereRaw('LOWER(TRIM(name)) = ?', [Str::lower($name)])
                ->first();

            if (!$sponsor) {
                continue;
            }

            $data = [
                'contact_number' => $this->nullableText($row['contact_number'] ?? null),
                'cug_number' => $this->nullableText($row['cug_number'] ?? null),
            ];

            $photo = $this->nullableText($row['photo'] ?? null);

            if ($photo !== null) {
                $data['photo'] = $photo;
            }

            $sponsor->update($data);
            $updated++;
        }

        $this->command?->info("Staff sync complete. Updated {$updated} records.");
    }

    private function nullableText(mixed $value): ?string
    {
        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }
}
