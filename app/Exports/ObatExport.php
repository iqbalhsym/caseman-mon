<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObatExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Obat::select('f_nf', 'nama_generik', 'kode_item', 'nama_item')->get();
    }

    public function headings(): array
    {
        return [
            'F/NF',
            'NAMA GENERIK',
            'KODE ITEM',
            'NAMA ITEM'
        ];
    }
}
