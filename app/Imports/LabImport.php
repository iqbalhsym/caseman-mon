<?php

namespace App\Imports;

use App\Models\Lab;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LabImport implements ToModel, WithStartRow
{
    protected $warna;

    public function __construct($warna = null)
    {
        $this->warna = $warna;
    }

    public function startRow(): int
    {
        return 2; // Skip the header row
    }

    public function model(array $row)
    {
        $namaItem = trim($row[0] ?? '');
        $warnaVal = $this->warna ?: strtolower(trim($row[1] ?? ''));

        if ($namaItem === '') {
            return null;
        }

        // Use updateOrCreate with name to avoid duplicates
        $existing = Lab::where('nama_item', $namaItem)->first();
        if ($existing) {
            $existing->update([
                'warna' => $warnaVal ?: $existing->warna
            ]);
            return null;
        }

        $count = Lab::count();
        $code = 'LAB-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return new Lab([
            'kode_item' => $code,
            'nama_item' => $namaItem,
            'warna' => $warnaVal
        ]);
    }
}
