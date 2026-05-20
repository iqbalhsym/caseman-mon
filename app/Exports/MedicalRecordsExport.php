<?php

namespace App\Exports;

use App\Models\MedicalRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MedicalRecordsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MedicalRecord::all([
            'billing_no',
            'no_rm',
            'nama_pasien',
            'guarantor',
            'tanggal_pulang',
            'ruangan_afya',
            'ruangan',
            'status_kembali_rm',
            'tanggal_kembali_rm',
            'status_analisa',
            'tanggal_analisa',
            'is_rm_lengkap',
            'laporan_pembedahan',
            'persetujuan_tindakan',
            'formulir_rawat_inap',
            'formulir_lain',
            'nama_dokter',
            'keterangan_formulir',
        ])->map(function ($record) {
            $record->status_kembali_rm = $record->status_kembali_rm ? 'YA' : 'TIDAK';
            $record->status_analisa = $record->status_analisa ? 'YA' : 'TIDAK';
            $record->is_rm_lengkap = $record->is_rm_lengkap ? 'LENGKAP' : 'TIDAK LENGKAP';
            return $record;
        });
    }

    public function headings(): array
    {
        return [
            'Billing No',
            'No RM',
            'Nama Pasien',
            'Guarantor',
            'Tanggal Pulang',
            'Ruangan Afya',
            'Ruangan',
            'Kembali ke RM (YA/TIDAK)',
            'Tgl Kembali RM',
            'Analisa (YA/TIDAK)',
            'Tgl Analisa',
            'Status Berkas (is_rm_lengkap)',
            'Laporan Pembedahan',
            'Persetujuan Tindakan',
            'Rawat Inap',
            'Formulir Lain-lain',
            'Nama Dokter / KSM',
            'Keterangan Formulir Lain-lain'
        ];
    }
}
