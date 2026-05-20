<?php

namespace App\Imports;

use App\Models\MedicalRecord;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicalRecordsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row['billing_no']) || $row['billing_no'] === null) {
            return null;
        }

        // Cek duplikat data No RM sesuai permintaan: jika No RM sudah ada, skip (tidak import)
        if (!empty($row['no_rm'])) {
            $exists = MedicalRecord::where('no_rm', $row['no_rm'])->exists();
            if ($exists) {
                return null;
            }
        }

        $isLengkap = isset($row['status_berkas_is_rm_lengkap']) 
                     ? (strtoupper(trim($row['status_berkas_is_rm_lengkap'])) === 'LENGKAP' ? true : false)
                     : false;
        
        $kembaliRM = isset($row['kembali_ke_rm_yatidak'])
                     ? (strtoupper(trim($row['kembali_ke_rm_yatidak'])) === 'YA' ? true : false)
                     : false;
        
        $analisa = isset($row['analisa_yatidak'])
                     ? (strtoupper(trim($row['analisa_yatidak'])) === 'YA' ? true : false)
                     : false;

        return new MedicalRecord([
            'billing_no'           => $row['billing_no'] ?? null,
            'no_rm'                => $row['no_rm'] ?? null,
            'nama_pasien'          => $row['nama_pasien'] ?? null,
            'guarantor'            => $row['guarantor'] ?? null,
            'tanggal_pulang'       => $row['tanggal_pulang'] ?? null,
            'ruangan_afya'         => $row['ruangan_afya'] ?? null,
            'ruangan'              => $row['ruangan'] ?? null,
            'status_kembali_rm'    => $kembaliRM,
            'tanggal_kembali_rm'   => $row['tgl_kembali_rm'] ?? null,
            'status_analisa'       => $analisa,
            'tanggal_analisa'      => $row['tgl_analisa'] ?? null,
            'is_rm_lengkap'        => $isLengkap,
            'laporan_pembedahan'   => $row['laporan_pembedahan'] ?? null,
            'persetujuan_tindakan' => $row['persetujuan_tindakan'] ?? null,
            'formulir_rawat_inap'  => $row['rawat_inap'] ?? null,
            'formulir_lain'        => $row['formulir_lain_lain'] ?? null,
            'nama_dokter'          => $row['nama_dokter_ksm'] ?? null,
            'keterangan_formulir'  => $row['keterangan_formulir_lain_lain'] ?? null,
        ]);
    }
}
