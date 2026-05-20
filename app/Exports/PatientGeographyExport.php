<?php

namespace App\Exports;

use App\Models\PatientGeography;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PatientGeographyExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PatientGeography::select('nama_pasien', 'no_rm', 'provinsi', 'kabupaten_kota')->get();
    }

    public function headings(): array
    {
        return ['nama_pasien', 'no_rm', 'provinsi', 'kabupaten_kota'];
    }
}
