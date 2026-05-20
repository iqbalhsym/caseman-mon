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
        $data = Permintaan::with('user', 'lokasi')->orderBy('status', 'desc')->orderBy('created_at', 'desc')->get();

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
                'tanggal' => $item->tanggal,
                'phone' => "62" . ltrim($item->user->phone, '0'),
                'status2' => ucfirst($item->status),
                'manager' => $item->manager?->name,
                'indikasi' => $item->indikasi,
                'file' => $item->file ? asset($item->file) : null,
                'catatan_diterima' => $item->catatan_diterima,
                'jumlah_hari' => $item->jumlah_hari,
                'tanggal_mulai_expired' => Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y'),
                'tanggal_berakhir_expired' => Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y'),
            ];
        });

        return view('admin.permintaan.list', compact('datas'));
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
                $data->update([
                    'status' => $request->status,
                    'manager_id' => Auth::user()->id,
                    'status_angka' => $request->angka,
                    'tanggal_jam_respon' => date('Y-m-d H:i:s'),
                    'catatan_diterima' => $request->catatan,
                ]);
            }

            $user = $data->user;

            if ($user->phone){
                $api_key = env('API_KEY');
                $number_key = env('NUMBER_KEY');
                $url = env('URL_WA');

                $message = "*👋 Halo " . $user->name .'*' . "\n\n" . "Ini adalah pesan otomatis dari *Aplikasi*" . "\n\n";
                $message .= "Permintaan Anda sudah direspon oleh *Case Manager*" . "\n\n";

                $message .= "Nomor Permintaan : " . $data->nomor . "\n";
                $message .= "Tanggal : " . $data->tanggal . ' ' . date('H:i', strtotime($data->jam)) . "\n";
                $message .= "No. RM : " . $data->no_rm . "\n";
                $message .= "Nama : " . $data->nama . "\n";
                $message .= "Ruangan/Lantai : " . $data->lokasi?->nama . ' Lt. ' . $data->lokasi?->lantai . "\n";
                $message .= "Diagnosis : " . ucfirst($data->diagnosis) . "\n";
                $message .= "Kategori : " . ucfirst($data->kategori) . "\n\n";

                $message .= "*Status Permintaan : " . ucfirst($data->status) . "*\n\n";

                if ($request->status == 'disetujui'){
                    $message .= "Catatan : " . $data->catatan_diterima . "\n";
                    $message .= "Tanggal Mulai Expired : " . $data->tanggal_mulai_expired . "\n";
                    $message .= "Tanggal Berakhir Expired : " . $data->tanggal_berakhir_expired . "\n";
                    $message .= "Jumlah Hari : " . $data->jumlah_hari . "\n";
                } else {
                    $message .= "Catatan : " . $data->catatan_diterima . "\n";
                }

                $message .= "*Sub Direktorat Pelayanan Medik RSUI*" . "\n";

                $service = [
                    'api_key' => $api_key,
                    'number_key' => $number_key,
                    'phone_no' => $user->phone,
                    'message' => $message,
                ];

                $send_wa = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, $service);

                $response = json_decode($send_wa->body());
                $response = json_encode($response);

                if ($send_wa->status() != 200){
                    LogNotif::create([
                        'nama' => $user->name,
                        'status' => 'gagal',
                        'message' => $message,
                        'response' => $response,
                        'phone' =>  $user->phone
                    ]);
                } else {
                    LogNotif::create([
                        'nama' => $user->name,
                        'status' => 'sukses',
                        'message' => $message,
                        'response' => $response,
                        'phone' => $user->phone,
                    ]);
                }
            }

            DB::commit();

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
        //
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
}
