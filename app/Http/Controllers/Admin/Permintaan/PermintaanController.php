<?php

namespace App\Http\Controllers\Admin\Permintaan;

use App\Http\Controllers\Controller;
use App\Models\LogNotif;
use App\Models\Lokasi;
use App\Models\Penjamin;
use App\Models\Permintaan;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $data = Permintaan::with('user', 'lokasi', 'penjamin')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->orderBy('status_angka', 'asc')
            ->get();

        $datas = $data->map(function ($item) {
            return [
                'id'                      => $item->id,
                'nama'                    => $item->nama,
                'no_rm'                   => $item->no_rm,
                'lokasi'                  => $item->lokasi?->nama . ' Lt. ' . $item->lokasi?->lantai,
                'diagnosis'               => $item->diagnosis,
                'kategori'                => ucfirst($item->kategori),
                'keterangan'              => $item->keterangan,
                'detail_obat'             => $item->detail_obat,
                'status'                  => $item->status,
                'jam'                     => date('H:i', strtotime($item->created_at)),
                'tanggal'                 => $item->created_at,
                'tanggal_masuk'           => date('d-m-Y', strtotime($item->tanggal)),
                'status2'                 => ucfirst($item->status),
                'indikasi'                => $item->indikasi,
                'manager'                 => $item->manager?->name,
                'catatan_diterima'        => $item->catatan_diterima,
                'jumlah_hari'             => $item->jumlah_hari,
                'tanggal_mulai_expired'   => Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y'),
                'tanggal_berakhir_expired'=> Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y'),
                'detail_paket'            => $item->detail_paket,
                'file'                    => $item->file ? asset($item->file) : null,
                'file2'                   => $item->file2 ? asset($item->file2) : null,
                'file3'                   => $item->file3 ? asset($item->file3) : null,
                'user'                    => $item->user_id,
                'user_login'              => Auth::user()->id,
                'phone'                   => ($item->user && $item->user->phone) ? '62' . ltrim($item->user->phone, '0') : '',
                'jaminan'                 => $item->penjamin?->nama ?? (\App\Models\Penjamin::find($item->lantai)?->nama ?? '-'),
                'jam_respon'              => $item->tanggal_jam_respon ? date('d-m-Y H:i', strtotime($item->tanggal_jam_respon)) : '-',
                // ✅ Tambah info role untuk keperluan tampilan tombol di view
                'can_edit'                => $this->canEdit($item),
                'can_delete'              => $this->canDelete($item),
            ];
        });

        return view('admin.permintaan.index', compact('datas'));
    }

    private function canCreate(): bool
    {
        $role = auth()->user()->role->name;

        // Hanya administrator dan tenagamedis yang bisa membuat permintaan
        if ($role === 'administrator') return true;
        if ($role === 'tenagamedis') return true;

        return false; // 'viewer' dan 'casemanager' tidak bisa membuat baru
    }

    /**
     * Cek apakah user boleh edit permintaan ini
     */
    private function canEdit(Permintaan $item): bool
    {
        $role = Auth::user()->role?->name;

        // if ($role === 'viewer') return false;
        if ($role === 'administrator') return true;
        if ($role === 'casemanager') return true;
        if ($role === 'tenagamedis' && $item->status === 'menunggu') return true;

        return false;
    }

    /**
     * Cek apakah user boleh hapus permintaan ini
     */
    private function canDelete(Permintaan $item): bool
    {
        $role = Auth::user()->role?->name;

        if ($role === 'administrator') return true;
        if ($role === 'tenagamedis' && $item->status === 'menunggu') return true;

        return false;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kunci akses halaman form create
        if (!$this->canCreate()) {
            abort(403, 'Anda tidak memiliki akses untuk membuat permintaan.');
        }

        $lokasi   = Lokasi::all();
        $penjamin = Penjamin::where('status', 'ya')->get();

        return view('admin.permintaan.create', compact('lokasi', 'penjamin'));
    }
    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        try {
            // Kunci akses API Store
            if (!$this->canCreate()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda tidak memiliki hak akses untuk membuat permintaan.'
                ], 403);
            }
            // Dapatkan lantai dari lokasi yang dipilih pasien
            $lokasiPasien = \App\Models\Lokasi::find($request->lokasi);
            $lantaiPasien = $lokasiPasien ? $lokasiPasien->lantai : null;

            // Cari shift yang aktif hari ini pada jam sekarang DAN di lantai yang sama
            $nowTime = date('H:i');
            $manager = Shift::where('tanggal', date('Y-m-d'))
                ->where('jam_mulai', '<=', $nowTime)
                ->where('jam_selesai', '>=', $nowTime)
                ->where('lantai', $lantaiPasien)
                ->with('user')
                ->first();

            if (!$manager) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tidak ada Case Manager yang berjaga di Lantai ' . ($lantaiPasien ?? '-') . ' saat ini. Silahkan Hubungi Admin.'
                ]);
            }

            DB::beginTransaction();

            $detail_paket = [];
            if ($request->has('kategori')) {
                foreach ($request->kategori as $index => $kategori) {
                    if (!empty($kategori)) {
                        $detail_paket[] = [
                            'kategori'    => $kategori,
                            'keterangan'  => $request->keterangan[$index] ?? '',
                            'detail_obat' => $request->detail_obat[$index] ?? '',
                            'indikasi'    => $request->indikasi[$index] ?? ''
                        ];
                    }
                }
            }

            $data = Permintaan::create([
                'user_id'      => $request->user,
                'nomor'        => $this->generateCode(),
                'tanggal'      => date('Y-m-d', strtotime($request->tanggal_masuk)),
                'no_rm'        => $request->no_rm,
                'nama'         => $request->nama,
                'lokasi_id'    => $request->lokasi,
                'jaminan'      => $request->jaminan,
                'lantai'       => $lantaiPasien,
                'diagnosis'    => $request->diagnosis,
                'kategori'     => $request->kategori[0] ?? null,
                'keterangan'   => $request->keterangan[0] ?? null,
                'detail_obat'  => $request->detail_obat[0] ?? null,
                'indikasi'     => $request->indikasi[0] ?? null,
                'detail_paket' => $detail_paket,
                'status'       => 'menunggu',
                'status_angka' => 1,
                'jam'          => date('H:i'),
            ]);

            if ($request->hasFile('file')) {
                if ($data->file != null) {
                    $image_path = public_path($data->file);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . '.' . $request->file('file')->getClientOriginalExtension();
                $path     = $request->file('file')->storeAs('permintaan', $filename, 'public');
                $data->update(['file' => 'storage/' . $path]);
            }

            if ($request->hasFile('file2')) {
                if ($data->file2 != null) {
                    $image_path = public_path($data->file2);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . 'ke2' . '.' . $request->file('file2')->getClientOriginalExtension();
                $path     = $request->file('file2')->storeAs('permintaan', $filename, 'public');
                $data->update(['file2' => 'storage/' . $path]);
            }

            if ($request->hasFile('file3')) {
                if ($data->file3 != null) {
                    $image_path = public_path($data->file3);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . 'ke3' . '.' . $request->file('file3')->getClientOriginalExtension();
                $path     = $request->file('file3')->storeAs('permintaan', $filename, 'public');
                $data->update(['file3' => 'storage/' . $path]);
            }

            if ($manager && $manager->user && $manager->user->phone) {
                $message  = "*👋 Halo " . $manager->user->name . '*' . "\n\n" . "Ini adalah pesan otomatis dari *Aplikasi*" . "\n\n";
                $message .= "Ada Permintaan Baru Mohon Segera Di Proses" . "\n\n";
                $message .= "Nomor Permintaan : " . $data->nomor . "\n";
                $message .= "Tgl. Permintaan : " . date('d-m-Y H:i', strtotime($data->created_at)) . "\n\n";
                $message .= "Registered Date : " . date('d-m-Y', strtotime($data->tanggal)) . "\n";
                $message .= "No. RM : " . $data->no_rm . "\n";
                $message .= "Nama : " . $data->nama . "\n";
                $message .= "Ruangan/Lantai : " . $data->lokasi->nama . ' / ' . $data->lokasi->lantai . "\n";
                $message .= "Diagnosis : " . htmlspecialchars($data->diagnosis) . "\n\n";
                $message .= "<b>--- DETAIL PAKET ---</b>\n";

                if (!empty($data->detail_paket) && is_array($data->detail_paket)) {
                    foreach ($data->detail_paket as $idx => $paket) {
                        $message .= "<b>Paket " . ($idx + 1) . "</b>\n";
                        $message .= "Kategori : " . htmlspecialchars($paket['kategori']) . "\n";
                        if ($paket['kategori'] == 'obat') {
                            $message .= "Detail Obat : " . htmlspecialchars(strip_tags($paket['detail_obat'] ?? '')) . "\n";
                        }
                        $message .= "Keterangan : " . htmlspecialchars($paket['keterangan']) . "\n";
                        $message .= "Indikasi : " . htmlspecialchars($paket['indikasi']) . "\n\n";
                    }
                } else {
                    $message .= "Kategori : " . htmlspecialchars($data->kategori) . "\n";
                    if ($data->kategori == 'obat') {
                        $message .= "Detail Obat : " . htmlspecialchars(strip_tags($data->detail_obat ?? '')) . "\n";
                    }
                    $message .= "Keterangan : " . htmlspecialchars($data->keterangan) . "\n";
                    $message .= "Indikasi : " . htmlspecialchars($data->indikasi) . "\n\n";
                }

                $message .= "<b>Sub Direktorat Pelayanan Medik RSUI</b>" . "\n";

                try {
                    if ($manager->user->telegram_chat_id) {
                        $this->sendTelegramNotif($message, $manager->user->telegram_chat_id);
                    }
                } catch (\Throwable $th) {
                    // Abaikan error kirim notif agar data tetap tersimpan
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan Berhasil Dibuat', 'data' => $data]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Permintaan::where('no_rm', $id)->orderBy('created_at', 'desc')->first();

            if (!$data) {
                return response()->json(['status' => 'error', 'message' => 'Pasien Tidak Ada']);
            }

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Search permintaan by name or no_rm (AJAX)
     */
    public function search(Request $request)
    {
        try {
            $q     = trim($request->get('q', ''));
            $query = Permintaan::with('user', 'lokasi')->orderBy('created_at', 'desc');

            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'ilike', "%{$q}%")
                        ->orWhere('no_rm', 'ilike', "%{$q}%")
                        ->orWhere('ruangan', 'ilike', "%{$q}%")
                        ->orWhere('lantai', 'ilike', "%{$q}%")
                        ->orWhereHas('lokasi', function ($l) use ($q) {
                            $l->where('nama', 'ilike', "%{$q}%")
                              ->orWhere('lantai', 'ilike', "%{$q}%");
                        });
                });
            } else {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            }

            $data          = $query->get();
            $formattedData = $data->map(function ($item) {
                return [
                    'id'                      => $item->id,
                    'nama'                    => $item->nama,
                    'no_rm'                   => $item->no_rm,
                    'lokasi'                  => $item->lokasi ? ($item->lokasi->nama . ' Lt. ' . $item->lokasi->lantai) : ($item->ruangan ? ($item->ruangan . ' Lt. ' . $item->lantai) : '-'),
                    'diagnosis'               => $item->diagnosis,
                    'kategori'                => ucfirst($item->kategori),
                    'keterangan'              => $item->keterangan,
                    'detail_obat'             => $item->detail_obat,
                    'status'                  => $item->status,
                    'jam'                     => date('H:i', strtotime($item->created_at)),
                    'tanggal'                 => $item->created_at,
                    'tanggal_masuk'           => date('d-m-Y', strtotime($item->tanggal)),
                    'status2'                 => ucfirst($item->status),
                    'indikasi'                => $item->indikasi,
                    'manager'                 => $item->manager?->name,
                    'catatan_diterima'        => $item->catatan_diterima,
                    'jumlah_hari'             => $item->jumlah_hari,
                    'tanggal_mulai_expired'   => $item->tanggal_mulai_expired ? Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y') : null,
                    'tanggal_berakhir_expired'=> $item->tanggal_berakhir_expired ? Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y') : null,
                    'file'                    => $item->file ? asset($item->file) : null,
                    'file2'                   => $item->file2 ? asset($item->file2) : null,
                    'file3'                   => $item->file3 ? asset($item->file3) : null,
                    'user_id'                 => $item->user_id,
                    'user_login'              => Auth::user()->id,
                    'phone'                   => ($item->user && $item->user->phone) ? '62' . ltrim($item->user->phone, '0') : '',
                    'can_edit'                => $this->canEdit($item),
                    'can_delete'              => $this->canDelete($item),
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Data Ditemukan',
                'total'   => $formattedData->count(),
                'data'    => $formattedData
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Search patient by No. RM for create form autocomplete (AJAX)
     */
    public function searchRm(Request $request)
    {
        try {
            $q = trim($request->get('q', ''));

            if (empty($q)) {
                return response()->json(['status' => 'error', 'message' => 'No. RM tidak boleh kosong']);
            }

            $data = Permintaan::where('no_rm', $q)
                ->with('lokasi')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$data) {
                return response()->json(['status' => 'error', 'message' => 'Data pasien dengan No. RM tersebut tidak ditemukan']);
            }

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'nama'       => $data->nama,
                    'lokasi_id'  => $data->lokasi_id,
                    'jaminan_id' => $data->jaminan ?? $data->lantai,
                    'diagnosis'  => $data->diagnosis,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Get patient history by No. RM and category (AJAX)
     */
    public function riwayat(Request $request)
    {
        try {
            $rm  = trim($request->get('rm', ''));
            $cat = trim($request->get('cat', ''));

            if (empty($rm) || empty($cat)) {
                return response()->json(['status' => 'error', 'message' => 'No. RM dan Kategori harus diisi']);
            }

            $data = Permintaan::where('no_rm', $rm)
                ->where('kategori', $cat)
                ->orderBy('tanggal', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['status' => 'error', 'message' => 'Riwayat tidak ditemukan', 'total' => 0]);
            }

            $formattedData = $data->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'kategori'    => ucfirst($item->kategori),
                    'tanggal'     => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'keterangan'  => $item->keterangan,
                    'detail_obat' => $item->detail_obat,
                    'indikasi'    => $item->indikasi,
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Data Riwayat Ditemukan',
                'total'   => $formattedData->count(),
                'data'    => $formattedData
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Permintaan::findOrFail($id);

        // ✅ Cek izin edit berdasarkan role
        if (!$this->canEdit($data)) {
            abort(403, 'Permintaan sudah diproses oleh Case Manager, tidak dapat diedit.');
        }

        $lokasi = Lokasi::all();
        $penjamin = Penjamin::where('status', 'ya')->get();
        return view('admin.permintaan.edit', compact('data', 'lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = Permintaan::where('no_rm', $request->no_rm)
                ->where('kategori', $request->kategori)
                ->orderBy('tanggal', 'desc')
                ->get();

            if ($data->count() == 0) {
                return response()->json(['status' => 'error', 'message' => 'Permintaan Tidak Dapat Dibuat', 'total' => 0]);
            }

            foreach ($data as $item) {
                $item->tanggal = Carbon::parse($item->tanggal)->translatedFormat('d F Y');
            }

            return response()->json(['status' => 'success', 'message' => 'Data Permintaan', 'total' => $data->count(), 'data' => $data]);

        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Permintaan::findOrFail($id);

            DB::beginTransaction();

            // ✅ Cek izin hapus berdasarkan role (bukan hardcode role_id)
            if (!$this->canDelete($data)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Permintaan tidak dapat dihapus karena sudah diproses.'
                ]);
            }

            $manager = Shift::where('tanggal', date('Y-m-d'))
                ->where('jam_mulai', '<=', date('H:i'))
                ->where('jam_selesai', '>=', date('H:i'))
                ->with('user')
                ->first();

            if ($manager && $manager->user && $manager->user->phone) {
                $message  = "*👋 Halo " . $manager->user->name . '*' . "\n\n" . "Ini adalah pesan otomatis dari *Aplikasi*" . "\n\n";
                $message .= "Ada Permintaan Yang Dihapus" . "\n\n";
                $message .= "Nomor Permintaan : " . $data->nomor . "\n";
                $message .= "Tgl. Permintaan : " . date('d-m-Y H:i', strtotime($data->created_at)) . "\n\n";
                $message .= "Registered Date : " . date('d-m-Y', strtotime($data->tanggal)) . "\n";
                $message .= "No. RM : " . $data->no_rm . "\n";
                $message .= "Nama : " . $data->nama . "\n";
                $message .= "Ruangan/Lantai : " . $data->lokasi->nama . ' / ' . $data->lokasi->lantai . "\n";
                $message .= "Diagnosis : " . htmlspecialchars($data->diagnosis) . "\n\n";
                $message .= "<b>--- DETAIL PAKET ---</b>\n";

                if (!empty($data->detail_paket) && is_array($data->detail_paket)) {
                    foreach ($data->detail_paket as $idx => $paket) {
                        $message .= "<b>Paket " . ($idx + 1) . "</b>\n";
                        $message .= "Kategori : " . htmlspecialchars($paket['kategori']) . "\n";
                        if ($paket['kategori'] == 'obat') {
                            $message .= "Detail Obat : " . htmlspecialchars(strip_tags($paket['detail_obat'] ?? '')) . "\n";
                        }
                        $message .= "Keterangan : " . htmlspecialchars($paket['keterangan']) . "\n";
                        $message .= "Indikasi : " . htmlspecialchars($paket['indikasi']) . "\n\n";
                    }
                } else {
                    $message .= "Kategori : " . htmlspecialchars($data->kategori) . "\n";
                    if ($data->kategori == 'obat') {
                        $message .= "Detail Obat : " . htmlspecialchars(strip_tags($data->detail_obat ?? '')) . "\n";
                    }
                    $message .= "Keterangan : " . htmlspecialchars($data->keterangan) . "\n";
                    $message .= "Indikasi : " . htmlspecialchars($data->indikasi) . "\n\n";
                }

                $message .= "Permintaan Dihapus Dikarenakan Salah Penginputan Permintaan" . "\n\n";
                $message .= "<b>Sub Direktorat Pelayanan Medik RSUI</b>" . "\n";

                try {
                    if ($manager->user->telegram_chat_id) {
                        $this->sendTelegramNotif($message, $manager->user->telegram_chat_id);
                    }
                } catch (\Throwable $th) {
                    // Abaikan error
                }
            }

            $data->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan Berhasil Dihapus']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Update permintaan (AJAX)
     */
    public function ubah(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = Permintaan::findOrFail($request->id);

            // ✅ Cek izin edit berdasarkan role
            if (!$this->canEdit($data)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Permintaan sudah diproses oleh Case Manager, tidak dapat diedit.'
                ]);
            }

            $role = Auth::user()->role?->name;

            if (in_array($role, ['casemanager', 'administrator'])) {

                // Set angka status berdasarkan pilihan untuk keperluan sorting di index
                $statusAngka = 1; // menunggu
                if ($request->status === 'disetujui') $statusAngka = 2;
                if ($request->status === 'ditolak') $statusAngka = 3;

                $data->update([
                    'status'           => $request->status,
                    'status_angka'     => $statusAngka,
                    'catatan_diterima' => $request->catatan_diterima,
                    'manager_id'       => Auth::user()->id, // Menyimpan siapa CM yang melakukan ACC
                    'tanggal_jam_respon'=> Carbon::now(),     // Track waktu respon untuk kolom jam_respon
                ]);

                // Opsional: Anda bisa menambahkan trigger kirim notifikasi whatsapp/telegram
                // ke Tenaga Medis di sini jika statusnya di-acc atau ditolak.

            // KONDISI B: Jika yang login adalah Tenaga Medis (Hanya perbaikan data pengajuan)
            } else if ($role === 'tenagamedis') {

            $data->update([
                'user_id'     => $request->user,
                'tanggal'     => date('Y-m-d', strtotime($request->tanggal_masuk)),
                'no_rm'       => $request->no_rm,
                'nama'        => $request->nama,
                'ruangan'     => $request->jaminan,
                'lokasi_id'   => $request->lokasi,
                'diagnosis'   => $request->diagnosis,
                'kategori'    => $request->kategori,
                'keterangan'  => $request->keterangan,
                'detail_obat' => $request->detail_obat,
                'indikasi'    => $request->indikasi,
            ]);

            if ($request->hasFile('file')) {
                if ($data->file != null) {
                    $image_path = public_path($data->file);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . '.' . $request->file('file')->getClientOriginalExtension();
                $path     = $request->file('file')->storeAs('permintaan', $filename, 'public');
                $data->update(['file' => 'storage/' . $path]);
            }

            if ($request->hasFile('file2')) {
                if ($data->file2 != null) {
                    $image_path = public_path($data->file2);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . 'ke2' . '.' . $request->file('file2')->getClientOriginalExtension();
                $path     = $request->file('file2')->storeAs('permintaan', $filename, 'public');
                $data->update(['file2' => 'storage/' . $path]);
            }

            if ($request->hasFile('file3')) {
                if ($data->file3 != null) {
                    $image_path = public_path($data->file3);
                    if (file_exists($image_path)) unlink($image_path);
                }
                $filename = $data->uuid . 'ke3' . '.' . $request->file('file3')->getClientOriginalExtension();
                $path     = $request->file('file3')->storeAs('permintaan', $filename, 'public');
                $data->update(['file3' => 'storage/' . $path]);
            }
        }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan Berhasil Diupdate', 'data' => $data]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Format nomor HP
     */
    function formatNomorHp($nomor)
    {
        $nomor = preg_replace('/\D/', '', $nomor);
        if (substr($nomor, 0, 1) === "0") {
            $nomor = "62" . substr($nomor, 1);
        }
        return $nomor;
    }

    /**
     * Generate kode unik
     */
    function generateCode($length = 10)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code  = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Kirim notifikasi Telegram
     */
    function sendTelegramNotif($message, $chatId)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return;

        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML'
        ]);
    }
}
