<?php

namespace Database\Seeders;

use App\Models\Lab;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/Data LAB.xlsx');
        if (!file_exists($filePath)) {
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $inserted = 0;
        foreach ($rows as $idx => $row) {
            if ($idx == 1) continue; // Skip header

            $namaItem = trim($row['A'] ?? '');
            $kategori = trim($row['B'] ?? '');

            if ($namaItem === '') {
                continue;
            }

            // Generate unique item code: LAB-0001, LAB-0002, etc.
            $code = 'LAB-' . str_pad($inserted + 1, 4, '0', STR_PAD_LEFT);

            Lab::updateOrCreate(
                ['nama_item' => $namaItem],
                [
                    'kode_item' => $code,
                    'warna' => strtolower($kategori)
                ]
            );

            $inserted++;
        }
    }
}
