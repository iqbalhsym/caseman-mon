<?php

namespace App\Exports;

use App\Models\Lab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LabExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Lab::select('kode_item', 'nama_item', 'warna')->get();
    }

    public function headings(): array
    {
        return [
            'KODE ITEM',
            'NAMA ITEM',
            'KATEGORI'
        ];
    }
}
