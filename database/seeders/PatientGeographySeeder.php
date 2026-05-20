<?php

namespace Database\Seeders;

use App\Models\PatientGeography;
use Illuminate\Database\Seeder;

class PatientGeographySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // DKI Jakarta
            ['nama_pasien' => 'Ahmad Fauzi', 'no_rm' => 'GEO001', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Selatan'],
            ['nama_pasien' => 'Siti Rahayu', 'no_rm' => 'GEO002', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat'],
            ['nama_pasien' => 'Budi Santoso', 'no_rm' => 'GEO003', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Barat'],
            ['nama_pasien' => 'Dewi Permata', 'no_rm' => 'GEO004', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Utara'],
            ['nama_pasien' => 'Eko Prasetyo', 'no_rm' => 'GEO005', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Timur'],
            ['nama_pasien' => 'Fitriani Hasan', 'no_rm' => 'GEO006', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Selatan'],
            ['nama_pasien' => 'Gunawan Saputra', 'no_rm' => 'GEO007', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat'],
            ['nama_pasien' => 'Hana Kusuma', 'no_rm' => 'GEO008', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Kepulauan Seribu'],
            ['nama_pasien' => 'Irwan Maulana', 'no_rm' => 'GEO009', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Selatan'],
            ['nama_pasien' => 'Juliana Putri', 'no_rm' => 'GEO010', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Barat'],
            ['nama_pasien' => 'Krisna Wijaya', 'no_rm' => 'GEO011', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Timur'],
            ['nama_pasien' => 'Linda Agustina', 'no_rm' => 'GEO012', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat'],

            // Jawa Barat
            ['nama_pasien' => 'Manda Lestari', 'no_rm' => 'GEO013', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bandung'],
            ['nama_pasien' => 'Nanda Setiawan', 'no_rm' => 'GEO014', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bogor'],
            ['nama_pasien' => 'Oky Ramadan', 'no_rm' => 'GEO015', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bekasi'],
            ['nama_pasien' => 'Putri Andini', 'no_rm' => 'GEO016', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Depok'],
            ['nama_pasien' => 'Qoriy Aziz', 'no_rm' => 'GEO017', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kabupaten Bogor'],
            ['nama_pasien' => 'Rina Marlina', 'no_rm' => 'GEO018', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bandung'],
            ['nama_pasien' => 'Surya Nugraha', 'no_rm' => 'GEO019', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kabupaten Bekasi'],
            ['nama_pasien' => 'Tari Wulandari', 'no_rm' => 'GEO020', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Cimahi'],
            ['nama_pasien' => 'Umar Hakim', 'no_rm' => 'GEO021', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Sukabumi'],
            ['nama_pasien' => 'Vera Susanti', 'no_rm' => 'GEO022', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kabupaten Karawang'],

            // Banten
            ['nama_pasien' => 'Wahyu Hidayat', 'no_rm' => 'GEO023', 'provinsi' => 'Banten', 'kabupaten_kota' => 'Kota Tangerang'],
            ['nama_pasien' => 'Xena Palupi', 'no_rm' => 'GEO024', 'provinsi' => 'Banten', 'kabupaten_kota' => 'Kota Tangerang Selatan'],
            ['nama_pasien' => 'Yusuf Aditama', 'no_rm' => 'GEO025', 'provinsi' => 'Banten', 'kabupaten_kota' => 'Kabupaten Tangerang'],
            ['nama_pasien' => 'Zahra Amalia', 'no_rm' => 'GEO026', 'provinsi' => 'Banten', 'kabupaten_kota' => 'Kota Serang'],
            ['nama_pasien' => 'Anton Ismail', 'no_rm' => 'GEO027', 'provinsi' => 'Banten', 'kabupaten_kota' => 'Kota Cilegon'],

            // Jawa Tengah
            ['nama_pasien' => 'Bayu Firmansyah', 'no_rm' => 'GEO028', 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Kota Semarang'],
            ['nama_pasien' => 'Citra Dewi', 'no_rm' => 'GEO029', 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Kota Solo'],
            ['nama_pasien' => 'Doni Kurniawan', 'no_rm' => 'GEO030', 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Kabupaten Sleman'],
            ['nama_pasien' => 'Eva Kristina', 'no_rm' => 'GEO031', 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Kota Magelang'],
            ['nama_pasien' => 'Fajar Nugroho', 'no_rm' => 'GEO032', 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Kabupaten Klaten'],

            // DI Yogyakarta
            ['nama_pasien' => 'Gita Puspita', 'no_rm' => 'GEO033', 'provinsi' => 'DI Yogyakarta', 'kabupaten_kota' => 'Kota Yogyakarta'],
            ['nama_pasien' => 'Hendra Gunawan', 'no_rm' => 'GEO034', 'provinsi' => 'DI Yogyakarta', 'kabupaten_kota' => 'Kabupaten Bantul'],
            ['nama_pasien' => 'Ika Wahyuni', 'no_rm' => 'GEO035', 'provinsi' => 'DI Yogyakarta', 'kabupaten_kota' => 'Kabupaten Sleman'],
            ['nama_pasien' => 'Joko Prasetya', 'no_rm' => 'GEO036', 'provinsi' => 'DI Yogyakarta', 'kabupaten_kota' => 'Kabupaten Gunungkidul'],

            // Jawa Timur
            ['nama_pasien' => 'Kartika Sari', 'no_rm' => 'GEO037', 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Kota Surabaya'],
            ['nama_pasien' => 'Lukman Hakim', 'no_rm' => 'GEO038', 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Kota Malang'],
            ['nama_pasien' => 'Mila Kusuma', 'no_rm' => 'GEO039', 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Kota Kediri'],
            ['nama_pasien' => 'Nurul Faizah', 'no_rm' => 'GEO040', 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Kabupaten Sidoarjo'],
            ['nama_pasien' => 'Oscar Pradana', 'no_rm' => 'GEO041', 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Kota Mojokerto'],

            // Sumatera Utara
            ['nama_pasien' => 'Peni Rahayu', 'no_rm' => 'GEO042', 'provinsi' => 'Sumatera Utara', 'kabupaten_kota' => 'Kota Medan'],
            ['nama_pasien' => 'Ricky Siahaan', 'no_rm' => 'GEO043', 'provinsi' => 'Sumatera Utara', 'kabupaten_kota' => 'Kota Medan'],
            ['nama_pasien' => 'Sinta Manurung', 'no_rm' => 'GEO044', 'provinsi' => 'Sumatera Utara', 'kabupaten_kota' => 'Kota Pematangsiantar'],
            ['nama_pasien' => 'Togar Simbolon', 'no_rm' => 'GEO045', 'provinsi' => 'Sumatera Utara', 'kabupaten_kota' => 'Kabupaten Deli Serdang'],

            // Sumatera Selatan
            ['nama_pasien' => 'Uni Oktavia', 'no_rm' => 'GEO046', 'provinsi' => 'Sumatera Selatan', 'kabupaten_kota' => 'Kota Palembang'],
            ['nama_pasien' => 'Vina Anggraini', 'no_rm' => 'GEO047', 'provinsi' => 'Sumatera Selatan', 'kabupaten_kota' => 'Kota Palembang'],
            ['nama_pasien' => 'Wisnu Ardana', 'no_rm' => 'GEO048', 'provinsi' => 'Sumatera Selatan', 'kabupaten_kota' => 'Kota Lubuklinggau'],

            // Riau
            ['nama_pasien' => 'Yanti Simanjuntak', 'no_rm' => 'GEO049', 'provinsi' => 'Riau', 'kabupaten_kota' => 'Kota Pekanbaru'],
            ['nama_pasien' => 'Zul Efendi', 'no_rm' => 'GEO050', 'provinsi' => 'Riau', 'kabupaten_kota' => 'Kota Dumai'],
            ['nama_pasien' => 'Agus Salim', 'no_rm' => 'GEO051', 'provinsi' => 'Riau', 'kabupaten_kota' => 'Kabupaten Kampar'],

            // Lampung
            ['nama_pasien' => 'Bela Safitri', 'no_rm' => 'GEO052', 'provinsi' => 'Lampung', 'kabupaten_kota' => 'Kota Bandar Lampung'],
            ['nama_pasien' => 'Cahyo Purnomo', 'no_rm' => 'GEO053', 'provinsi' => 'Lampung', 'kabupaten_kota' => 'Kota Metro'],
            ['nama_pasien' => 'Diah Ayu', 'no_rm' => 'GEO054', 'provinsi' => 'Lampung', 'kabupaten_kota' => 'Kabupaten Lampung Selatan'],

            // Kalimantan Timur
            ['nama_pasien' => 'Erfan Hidayatullah', 'no_rm' => 'GEO055', 'provinsi' => 'Kalimantan Timur', 'kabupaten_kota' => 'Kota Samarinda'],
            ['nama_pasien' => 'Farida Yusuf', 'no_rm' => 'GEO056', 'provinsi' => 'Kalimantan Timur', 'kabupaten_kota' => 'Kota Balikpapan'],
            ['nama_pasien' => 'Gilang Ramadhan', 'no_rm' => 'GEO057', 'provinsi' => 'Kalimantan Timur', 'kabupaten_kota' => 'Kota Bontang'],

            // Sulawesi Selatan
            ['nama_pasien' => 'Hesti Wulandari', 'no_rm' => 'GEO058', 'provinsi' => 'Sulawesi Selatan', 'kabupaten_kota' => 'Kota Makassar'],
            ['nama_pasien' => 'Imam Suroso', 'no_rm' => 'GEO059', 'provinsi' => 'Sulawesi Selatan', 'kabupaten_kota' => 'Kota Makassar'],
            ['nama_pasien' => 'Jamilah Bahar', 'no_rm' => 'GEO060', 'provinsi' => 'Sulawesi Selatan', 'kabupaten_kota' => 'Kota Parepare'],

            // Bali
            ['nama_pasien' => 'Ketut Suardana', 'no_rm' => 'GEO061', 'provinsi' => 'Bali', 'kabupaten_kota' => 'Kota Denpasar'],
            ['nama_pasien' => 'Luh Made Sari', 'no_rm' => 'GEO062', 'provinsi' => 'Bali', 'kabupaten_kota' => 'Kabupaten Badung'],
            ['nama_pasien' => 'Made Arsa', 'no_rm' => 'GEO063', 'provinsi' => 'Bali', 'kabupaten_kota' => 'Kabupaten Gianyar'],

            // Nusa Tenggara Barat
            ['nama_pasien' => 'Nurhasanah', 'no_rm' => 'GEO064', 'provinsi' => 'Nusa Tenggara Barat', 'kabupaten_kota' => 'Kota Mataram'],
            ['nama_pasien' => 'Oki Firmansyah', 'no_rm' => 'GEO065', 'provinsi' => 'Nusa Tenggara Barat', 'kabupaten_kota' => 'Kabupaten Lombok Barat'],

            // Kalimantan Barat
            ['nama_pasien' => 'Petrus Ade', 'no_rm' => 'GEO066', 'provinsi' => 'Kalimantan Barat', 'kabupaten_kota' => 'Kota Pontianak'],
            ['nama_pasien' => 'Qris Handayani', 'no_rm' => 'GEO067', 'provinsi' => 'Kalimantan Barat', 'kabupaten_kota' => 'Kota Singkawang'],

            // Aceh
            ['nama_pasien' => 'Razif Harun', 'no_rm' => 'GEO068', 'provinsi' => 'Aceh', 'kabupaten_kota' => 'Kota Banda Aceh'],
            ['nama_pasien' => 'Safrida Ningsih', 'no_rm' => 'GEO069', 'provinsi' => 'Aceh', 'kabupaten_kota' => 'Kota Langsa'],

            // Papua
            ['nama_pasien' => 'Thomas Kambu', 'no_rm' => 'GEO070', 'provinsi' => 'Papua', 'kabupaten_kota' => 'Kota Jayapura'],
            ['nama_pasien' => 'Ulfa Wenda', 'no_rm' => 'GEO071', 'provinsi' => 'Papua', 'kabupaten_kota' => 'Kabupaten Mimika'],

            // Sulawesi Utara
            ['nama_pasien' => 'Viktor Mamahit', 'no_rm' => 'GEO072', 'provinsi' => 'Sulawesi Utara', 'kabupaten_kota' => 'Kota Manado'],
            ['nama_pasien' => 'Winda Maramis', 'no_rm' => 'GEO073', 'provinsi' => 'Sulawesi Utara', 'kabupaten_kota' => 'Kota Bitung'],

            // Kalimantan Selatan
            ['nama_pasien' => 'Xiong Anwar', 'no_rm' => 'GEO074', 'provinsi' => 'Kalimantan Selatan', 'kabupaten_kota' => 'Kota Banjarmasin'],
            ['nama_pasien' => 'Yayan Risdiyanto', 'no_rm' => 'GEO075', 'provinsi' => 'Kalimantan Selatan', 'kabupaten_kota' => 'Kota Banjarbaru'],

            // Sumatera Barat
            ['nama_pasien' => 'Zara Fitria', 'no_rm' => 'GEO076', 'provinsi' => 'Sumatera Barat', 'kabupaten_kota' => 'Kota Padang'],
            ['nama_pasien' => 'Aldo Putra', 'no_rm' => 'GEO077', 'provinsi' => 'Sumatera Barat', 'kabupaten_kota' => 'Kota Bukittinggi'],

            // Maluku
            ['nama_pasien' => 'Bernard Latupeirissa', 'no_rm' => 'GEO078', 'provinsi' => 'Maluku', 'kabupaten_kota' => 'Kota Ambon'],

            // Kepulauan Riau
            ['nama_pasien' => 'Cindy Marpaung', 'no_rm' => 'GEO079', 'provinsi' => 'Kepulauan Riau', 'kabupaten_kota' => 'Kota Batam'],
            ['nama_pasien' => 'David Tanjung', 'no_rm' => 'GEO080', 'provinsi' => 'Kepulauan Riau', 'kabupaten_kota' => 'Kota Tanjungpinang'],

            // Jambi
            ['nama_pasien' => 'Erna Susanti', 'no_rm' => 'GEO081', 'provinsi' => 'Jambi', 'kabupaten_kota' => 'Kota Jambi'],

            // Bengkulu
            ['nama_pasien' => 'Fadlan Akbar', 'no_rm' => 'GEO082', 'provinsi' => 'Bengkulu', 'kabupaten_kota' => 'Kota Bengkulu'],

            // Sulawesi Tengah
            ['nama_pasien' => 'Gandi Pratama', 'no_rm' => 'GEO083', 'provinsi' => 'Sulawesi Tengah', 'kabupaten_kota' => 'Kota Palu'],

            // Sulawesi Tenggara
            ['nama_pasien' => 'Harun Latief', 'no_rm' => 'GEO084', 'provinsi' => 'Sulawesi Tenggara', 'kabupaten_kota' => 'Kota Kendari'],

            // Gorontalo
            ['nama_pasien' => 'Irma Gobel', 'no_rm' => 'GEO085', 'provinsi' => 'Gorontalo', 'kabupaten_kota' => 'Kota Gorontalo'],

            // Kalimantan Tengah
            ['nama_pasien' => 'January Mandau', 'no_rm' => 'GEO086', 'provinsi' => 'Kalimantan Tengah', 'kabupaten_kota' => 'Kota Palangka Raya'],

            // Nusa Tenggara Timur
            ['nama_pasien' => 'Kornelius Bulu', 'no_rm' => 'GEO087', 'provinsi' => 'Nusa Tenggara Timur', 'kabupaten_kota' => 'Kota Kupang'],

            // Maluku Utara
            ['nama_pasien' => 'Laila Umasugi', 'no_rm' => 'GEO088', 'provinsi' => 'Maluku Utara', 'kabupaten_kota' => 'Kota Ternate'],

            // Papua Barat
            ['nama_pasien' => 'Marthinus Fakdawer', 'no_rm' => 'GEO089', 'provinsi' => 'Papua Barat', 'kabupaten_kota' => 'Kota Sorong'],

            // Sulawesi Barat
            ['nama_pasien' => 'Nurdin Rahim', 'no_rm' => 'GEO090', 'provinsi' => 'Sulawesi Barat', 'kabupaten_kota' => 'Kabupaten Majene'],

            // Bangka Belitung
            ['nama_pasien' => 'Omar Ardiansyah', 'no_rm' => 'GEO091', 'provinsi' => 'Bangka Belitung', 'kabupaten_kota' => 'Kota Pangkalpinang'],

            // Sumatera Barat tambahan
            ['nama_pasien' => 'Puti Rahayu', 'no_rm' => 'GEO092', 'provinsi' => 'Sumatera Barat', 'kabupaten_kota' => 'Kota Solok'],

            // Jawa Barat tambahan
            ['nama_pasien' => 'Randi Maulana', 'no_rm' => 'GEO093', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bandung'],
            ['nama_pasien' => 'Selly Amara', 'no_rm' => 'GEO094', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kota Bekasi'],
            ['nama_pasien' => 'Toni Susanto', 'no_rm' => 'GEO095', 'provinsi' => 'Jawa Barat', 'kabupaten_kota' => 'Kabupaten Bandung'],

            // DKI Jakarta tambahan
            ['nama_pasien' => 'Umi Kalsum', 'no_rm' => 'GEO096', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Selatan'],
            ['nama_pasien' => 'Valdo Rizki', 'no_rm' => 'GEO097', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Timur'],
            ['nama_pasien' => 'Wulan Anjani', 'no_rm' => 'GEO098', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat'],
            ['nama_pasien' => 'Yogi Pratama', 'no_rm' => 'GEO099', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Barat'],
            ['nama_pasien' => 'Zakia Amira', 'no_rm' => 'GEO100', 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Utara'],
        ];

        foreach ($data as $row) {
            PatientGeography::firstOrCreate(
                ['no_rm' => $row['no_rm']],
                $row
            );
        }
    }
}
