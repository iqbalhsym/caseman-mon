<?php

namespace App\Http\Controllers\Admin\Permintaan;

use App\Http\Controllers\Controller;
use App\Models\LogNotif;
use App\Models\Permintaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ListPermintaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Permintaan::with('user', 'lokasi', 'penjamin')->where('created_at', '>=', Carbon::now()->subDays(7))->orderBy('created_at', 'desc')->get();
        // $data = Permintaan::with('user', 'lokasi')->orderBy('created_at', 'desc')->get();
        // $data = Permintaan::with('user', 'lokasi')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();

        $datas = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'no_rm' => $item->no_rm,
                'lokasi' => $item->lokasi?->nama . ' Lt. ' . $item->lokasi?->lantai,
                // 'ruangan' => $item->ruangan,
                // 'lantai' => $item->lantai,
                'diagnosis' => $item->diagnosis,
                'kategori' => ucfirst($item->kategori),
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'jam' => date('H:i', strtotime($item->jam)),
                'tanggal' => $item->created_at,
                'tanggal_masuk' => date('d-m-Y', strtotime($item->tanggal)),
                'phone' => ($item->user && $item->user->phone) ? "62" . ltrim($item->user->phone, '0') : '',
                'status2' => ucfirst($item->status),
                'manager' => $item->manager?->name,
                'indikasi' => $item->indikasi,
                'detail_paket' => $item->detail_paket,
                'file' => $item->file ? asset($item->file) : null,
                'file2' => $item->file2 ? asset($item->file2) : null,
                'file3' => $item->file3 ? asset($item->file3) : null,
                'catatan_diterima' => $item->catatan_diterima,
                'jumlah_hari' => $item->jumlah_hari,
                'tanggal_mulai_expired' => Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y'),
                'tanggal_berakhir_expired' => Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y'),
                'manager_id' => $item->manager_id,
                'pengaju' => $item->user?->name ?? '-',
                'user_login' => Auth::user()->id,
                'jaminan' => $item->penjamin?->nama ?? (\App\Models\Penjamin::find($item->lantai)?->nama ?? '-'),
                'jam_respon' => $item->tanggal_jam_respon ? date('d-m-Y H:i', strtotime($item->tanggal_jam_respon)) : '-',
            ];
        });

        return view('admin.permintaan.list', compact('datas'));
    }

    function formatNomorHp($nomor)
    {
        // hilangkan semua karakter non angka
        $nomor = preg_replace('/\D/', '', $nomor);

        if (substr($nomor, 0, 1) === "0") {
            $nomor = "62" . substr($nomor, 1);
        }

        return $nomor;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $data = Permintaan::find($request->id);

            DB::beginTransaction();

            if ($request->has('paket_index') && $request->paket_index !== null && $request->paket_index !== '') {
                $idx = $request->paket_index;
                $detail_paket = $data->detail_paket ?? [];
                
                if ($request->status == 'disetujui') {
                    $tanggal_berakhir_expired = date('Y-m-d', strtotime($request->tanggal . ' + ' . $request->hari . ' days'));
                    $detail_paket[$idx]['status'] = 'disetujui';
                    $detail_paket[$idx]['catatan'] = $request->catatan;
                    $detail_paket[$idx]['jumlah_hari'] = $request->hari;
                    $detail_paket[$idx]['tanggal_mulai_expired'] = $request->tanggal;
                    $detail_paket[$idx]['tanggal_berakhir_expired'] = $tanggal_berakhir_expired;
                } else {
                    if ($request->angka == '3') $status_p = 'konfirmasi';
                    else if ($request->angka == '4') $status_p = 'ditolak';
                    else $status_p = 'batal';
                    
                    $detail_paket[$idx]['status'] = $status_p;
                    $detail_paket[$idx]['catatan'] = $request->catatan;
                }
                
                $has_disetujui = false;
                $has_konfirmasi = false;
                $has_ditolak = false;
                $all_processed = true;
                foreach ($detail_paket as $p) {
                    $p_status = $p['status'] ?? 'menunggu';
                    if ($p_status == 'menunggu') {
                        $all_processed = false;
                    }
                    if ($p_status == 'disetujui') $has_disetujui = true;
                    if ($p_status == 'konfirmasi') $has_konfirmasi = true;
                    if ($p_status == 'ditolak') $has_ditolak = true;
                }
                
                $updateData = [
                    'detail_paket' => $detail_paket,
                    'manager_id' => Auth::user()->id,
                    'tanggal_jam_respon' => date('Y-m-d H:i:s'),
                ];
                
                if ($all_processed) {
                    if ($has_disetujui) {
                        $updateData['status'] = 'disetujui';
                        $updateData['status_angka'] = 2;
                    } else if ($has_konfirmasi) {
                        $updateData['status'] = 'konfirmasi';
                        $updateData['status_angka'] = 3;
                    } else if ($has_ditolak) {
                        $updateData['status'] = 'ditolak';
                        $updateData['status_angka'] = 4;
                    } else {
                        $updateData['status'] = 'batal';
                        $updateData['status_angka'] = 5;
                    }
                }
                
                $data->update($updateData);
            } else {
                if ($request->status == 'disetujui'){
    
                    $tanggal_berakhir_expired = date('Y-m-d', strtotime($request->tanggal . ' + ' . $request->hari . ' days'));
    
                    $data->update([
                        'status' => $request->status,
                        'manager_id' => Auth::user()->id,
                        'status_angka' => $request->angka,
                        'tanggal_jam_respon' => date('Y-m-d H:i:s'),
                        'catatan_diterima' => $request->catatan,
                        'jumlah_hari' => $request->hari,
                        'tanggal_mulai_expired' => $request->tanggal,
                        'tanggal_berakhir_expired' => $tanggal_berakhir_expired,
                    ]);
                } else {
                    if ($request->angka == '3'){
                        $status = 'konfirmasi';
                    } else if ($request->angka == '4'){
                        $status = 'ditolak';
                    } else {
                        $status = 'batal';
                    }
    
                    $data->update([
                        'status' => $status,
                        'manager_id' => Auth::user()->id,
                        'status_angka' => $request->angka,
                        'tanggal_jam_respon' => date('Y-m-d H:i:s'),
                        'catatan_diterima' => $request->catatan,
                    ]);
                }
            }

            $user = $data->user;

            if ($user->phone){

                if ($data->status == 'disetujui'){
                    $status = 'Disetujui';
                } else if ($data->status == 'konfirmasi'){
                    $status = 'Dalam Konfirmasi';
                } else if ($data->status == 'ditolak'){
                    $status = 'Ditolak';
                } else {
                    $status = 'Dibatalkan';
                }

                $message = "*👋 Halo " . $user->name .'*' . "\n\n" . "Ini adalah pesan otomatis dari *Aplikasi*" . "\n\n";
                $message .= "Permintaan Anda sudah direspon oleh *". Auth::user()->name ."*" . "\n\n";

                $message .= "Nomor Permintaan : " . $data->nomor . "\n";
                $message .= "Tgl. Permintaan : " . date('d-m-Y H:i', strtotime($data->created_at)) . "\n\n";

                $message .= "Registered Date : " . date('d-m-Y', strtotime($data->tanggal)) . "\n";
                $message .= "No. RM : " . $data->no_rm . "\n";
                $message .= "Nama : " . $data->nama . "\n";
                $message .= "Ruangan/Lantai : " . $data->lokasi?->nama . ' Lt. ' . $data->lokasi?->lantai . "\n\n";
                
                $message .= "<b>--- DETAIL PAKET ---</b>\n";
                if (!empty($data->detail_paket) && is_array($data->detail_paket)) {
                    foreach ($data->detail_paket as $idx => $paket) {
                        $message .= "<b>Paket " . ($idx + 1) . "</b>\n";
                        $message .= "Kategori : " . htmlspecialchars($paket['kategori']) . "\n";
                        $message .= "Keterangan : " . htmlspecialchars($paket['keterangan']) . "\n";
                        $message .= "Indikasi : " . htmlspecialchars($paket['indikasi']) . "\n";
                        if ($request->has('paket_index') && $request->paket_index !== null && $request->paket_index == $idx) {
                            $message .= "<b>Status Paket : " . ucfirst($paket['status'] ?? '') . "</b>\n";
                            $message .= "Catatan Paket : " . htmlspecialchars($paket['catatan'] ?? '') . "\n";
                            if (($paket['status'] ?? '') == 'disetujui' && isset($paket['jumlah_hari'])) {
                                $message .= "Tanggal Mulai Expired : " . date('d-m-Y', strtotime($paket['tanggal_mulai_expired'])) . "\n";
                                $message .= "Tanggal Berakhir Expired : " . date('d-m-Y', strtotime($paket['tanggal_berakhir_expired'])) . "\n";
                                $message .= "Jumlah Hari : " . $paket['jumlah_hari'] . "\n";
                            }
                        }
                        $message .= "\n";
                    }
                } else {
                    $message .= "Kategori : " . ucfirst($data->kategori) . "\n";
                    $message .= "Keterangan : " . htmlspecialchars($data->keterangan) . "\n";
                    $message .= "Indikasi : " . htmlspecialchars($data->indikasi) . "\n\n";
                }

                $message .= "<b>Status Permintaan : " . $status . "</b>\n\n";

                if (!($request->has('paket_index') && $request->paket_index !== null && $request->paket_index !== '')) {
                    $message .= "Catatan : " . htmlspecialchars($data->catatan_diterima) . "\n\n";
                    if ($request->status == 'disetujui'){
                        if ($data->jumlah_hari != null){
                            $message .= "Tanggal Mulai Expired : " . date('d-m-Y', strtotime($data->tanggal_mulai_expired)) . "\n";
                            $message .= "Tanggal Berakhir Expired : " . date('d-m-Y', strtotime($data->tanggal_berakhir_expired)) . "\n";
                            $message .= "Jumlah Hari : " . $data->jumlah_hari . "\n\n";
                        }
                    }
                }

                $message .= "<b>Sub Direktorat Pelayanan Medik RSUI</b>" . "\n";

                try {
                    if ($user->telegram_chat_id) {
                        $this->sendTelegramNotif($message, $user->telegram_chat_id);
                    }
                } catch (\Throwable $th) {
                    // Abaikan error
                }
            }

            DB::commit();

            // SINKRONISASI KE PASIEN HISTORI (pasien-mon)
            try {
                if ($data->status == 'disetujui' || (isset($detail_paket) && isset($detail_paket[$idx]) && $detail_paket[$idx]['status'] == 'disetujui')) {
                    $pasienHistoriService = resolve(\App\Services\PasienHistoriService::class);
                    
                    if (isset($detail_paket) && isset($detail_paket[$idx])) {
                        // Jika memproses per paket
                        $paket = $detail_paket[$idx];
                        if (isset($paket['kategori']) && strtolower($paket['kategori']) === 'obat' && !empty($paket['detail_obat'])) {
                            $pasienHistoriService->sendObatPickup(
                                $data->no_rm,
                                $paket['detail_obat'],
                                date('Y-m-d H:i:s')
                            );
                        }
                    } else {
                        // Jika memproses single request
                        if (strtolower($data->kategori) === 'obat' && !empty($data->detail_obat)) {
                            $pasienHistoriService->sendObatPickup(
                                $data->no_rm,
                                $data->detail_obat,
                                date('Y-m-d H:i:s')
                            );
                        }
                    }
                }
            } catch (\Throwable $th) {
                \Illuminate\Support\Facades\Log::error('Gagal sinkronisasi obat ke pasien-histori: ' . $th->getMessage());
            }

            return response()->json(['status' => 'success', 'message' => 'Data Permintaan Berhasil Diubah', 'data' => $data]);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {

            $data = Permintaan::find($id);

            $updateData = [
                'status' => 'menunggu',
                'status_angka' => 1,
                'manager_id' => null,
                'tanggal_jam_respon' => null,
                'catatan_diterima' => null,
                'jumlah_hari' => null,
                'tanggal_mulai_expired' => null,
                'tanggal_berakhir_expired' => null,
            ];

            if (!empty($data->detail_paket) && is_array($data->detail_paket)) {
                $detail_paket = $data->detail_paket;
                foreach ($detail_paket as $idx => $paket) {
                    $detail_paket[$idx]['status'] = 'menunggu';
                    unset($detail_paket[$idx]['catatan']);
                    unset($detail_paket[$idx]['jumlah_hari']);
                    unset($detail_paket[$idx]['tanggal_mulai_expired']);
                    unset($detail_paket[$idx]['tanggal_berakhir_expired']);
                }
                $updateData['detail_paket'] = $detail_paket;
            }

            $data->update($updateData);

            return response()->json(['status' => 'success', 'message' => 'Data Permintaan Berhasil Diubah']);

        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function sendTelegramNotif($message, $chatId)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return;

        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }
}
