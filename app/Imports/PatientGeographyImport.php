<?php

namespace App\Imports;

use App\Models\PatientGeography;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PatientGeographyImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $namaP   = trim($row['nama_pasien'] ?? '');
        $noRm    = trim($row['no_rm'] ?? '');
        $provinsi = trim($row['provinsi'] ?? '');
        $kota    = trim($row['kabupaten_kota'] ?? '');

        if (!$namaP || !$provinsi || !$kota) {
            return null;
        }

        // Filter duplikat berdasarkan no_rm jika ada
        if ($noRm !== '') {
            $exists = PatientGeography::where('no_rm', $noRm)->exists();
            if ($exists) {
                return null;
            }
        }

        return new PatientGeography([
            'nama_pasien'    => $namaP,
            'no_rm'          => $noRm ?: null,
            'provinsi'       => $provinsi,
            'kabupaten_kota' => $kota,
        ]);
    }
}
