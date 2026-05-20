<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientGeography extends Model
{
    protected $fillable = [
        'nama_pasien',
        'no_rm',
        'provinsi',
        'kabupaten_kota',
    ];
}
