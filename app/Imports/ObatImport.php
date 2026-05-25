<?php

namespace App\Imports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ObatImport implements ToModel, WithStartRow
{
    protected $warna;

    public function __construct($warna = null)
    {
        $this->warna = $warna;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Skip the header row
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Pastikan baris tidak kosong
        if (!isset($row[2]) || $row[2] == '') {
            return null;
        }

        return Obat::updateOrCreate(
            ['kode_item' => $row[2]], // Kunci unik untuk mencegah duplikasi
            [
                'f_nf'         => $row[0] ?? null,
                'nama_generik' => $row[1] ?? null,
                'nama_item'    => $row[3] ?? null,
                'warna'        => $this->warna,
            ]
        );
    }
}
