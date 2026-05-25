<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'f_nf',
        'nama_generik',
        'kode_item',
        'nama_item',
        'warna'
    ];
}
