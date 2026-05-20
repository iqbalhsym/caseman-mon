<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_no',
        'tanggal_pulang',
        'status_kembali_rm',
        'tanggal_kembali_rm',
        'status_analisa',
        'tanggal_analisa',
        'no_rm',
        'nama_pasien',
        'guarantor',
        'ruangan_afya',
        'is_rm_lengkap',
        'laporan_pembedahan',
        'persetujuan_tindakan',
        'ruangan',
        'nama_dokter',
        'formulir_igd',
        'formulir_rawat_inap',
        'formulir_lain',
        'keterangan_formulir',
    ];
}
