<?php

namespace App\Http\Controllers\Admin\Permintaan;

use App\Http\Controllers\Controller;
use App\Models\LogNotif;
use App\Models\Lokasi;
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

        if ($user->role_id == 3){
            $data = Permintaan::where('user_id', Auth::user()->id)->with('user', 'lokasi')->orderBy('created_at', 'desc')->orderBy('status_angka', 'asc')->get();
        } else {
            $data = Permintaan::with('user', 'lokasi')->orderBy('created_at', 'desc')->orderBy('status_angka', 'asc')->get();
        }

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
                'status2' => ucfirst($item->status),
                'indikasi' => $item->indikasi,
                'manager' => $item->manager?->name,
                'catatan_diterima' => $item->catatan_diterima,
                'jumlah_hari' => $item->jumlah_hari,
                'tanggal_mulai_expired' => Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y'),
                'tanggal_berakhir_expired' => Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y'),
                'file' => $item->file ? asset($item->file) : null
            ];
        });

        return view('admin.permintaan.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasi = Lokasi::all();

        return view('admin.permintaan.create', compact('lokasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $manager = Shift::where('tanggal', date('Y-m-d'))->where('jam_mulai', '<=', date('Y-m-d H:i'))->where('jam_selesai', '>=', date('Y-m-d H:i'))->with('user')->first();

            if (!$manager){
                return response()->json(['status' => 'error', 'message' => 'Shift Case Manager Tidak Ditemukan, Silahkan Hubungi Admin']);
            }

            DB::beginTransaction();

            $data = Permintaan::create([
                'user_id' => $request->user,
                'nomor' => $this->generateCode(),
                'tanggal' => date('Y-m-d', $request->tanggal_masuk),
                'no_rm' => $request->no_rm,
                'nama' => $request->nama,
                'ruangan' => $request->jaminan,
                'lokasi_id' => $request->lokasi,
                // 'ruangan' => $request->ruangan,
                // 'lantai' => $request->lantai,
                'diagnosis' => $request->diagnosis,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'indikasi' => $request->indikasi,
                'status' => 'menunggu',
                'status_angka' => 1,
                'jam' => date('H:i'),
            ]);

            if ($request->hasFile('file')){
                if ($data->file != null){
                    $image_path = public_path($data->file);
                    if (file_exists($image_path)){
                        unlink($image_path);
                    }
                }

                $filename = $data->uuid . '.' . $request->file('file')->getClientOriginalExtension();
                $path = $request->file('file')->storeAs('permintaan', $filename, 'public');

                $data->update([
                    'file' => 'storage/'.$path,
                ]);
            }

            if ($request->hasFile('file2')){
                if ($data->file2 != null){
                    $image_path = public_path($data->file2);
                    if (file_exists($image_path)){
                        unlink($image_path);
                    }
                }

                $filename = $data->uuid . 'ke2' . '.' . $request->file('file2')->getClientOriginalExtension();
                $path = $request->file('file2')->storeAs('permintaan', $filename, 'public');

                $data->update([
                    'file2' => 'storage/'.$path,
                ]);
            }

            if ($request->hasFIle('file3')){
                if ($data->file3 != null){
                    $image_path = public_path($data->file3);
                    if (file_exists($image_path)){
                        unlink($image_path);
                    }
                }

                $filename = $data->uuid . 'ke3' . '.' . $request->file('file3')->getClientOriginalExtension();
                $path = $request->file('file3')->storeAs('permintaan', $filename, 'public');

                $data->update([
                    'file3' => 'storage/'.$path,
                ]);
            }

            if ($manager->user->phone){
                $api_key = env('API_KEY');
                $number_key = env('NUMBER_KEY');
                $url = env('URL_WA');

                $message = "*👋 Halo " . $manager->user->name .'*' . "\n\n" . "Ini adalah pesan otomatis dari *Aplikasi*" . "\n\n";
                $message .= "Ada Permintaan Baru Mohon Segera Di Proses" . "\n\n";

                $message .= "Nomor Permintaan : " . $data->nomor . "\n";
                $message .= "Tanggal : " . $data->tanggal . ' ' . date('H:i', strtotime($data->jam)) . "\n";
                $message .= "No. RM : " . $data->no_rm . "\n";
                $message .= "Nama : " . $data->nama . "\n";
                $message .= "Ruangan/Lantai : " . $data->lokasi->nama . ' / ' . $data->lokasi->lantai . "\n";
                $message .= "Diagnosis : " . $data->diagnosis . "\n";
                $message .= "Kategori : " . $data->kategori . "\n";
                $message .= "Keterangan : " . $data->keterangan . "\n";
                $message .= "Indikasi : " . $data->indikasi . "\n\n";
                $message .= "*Sub Direktorat Pelayanan Medik RSUI*" . "\n";

                $service = [
                    'api_key' => $api_key,
                    'number_key' => $number_key,
                    'phone_no' => $manager->user->phone,
                    'message' => $message,
                ];

                $send_wa = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, $service);

                $response = json_decode($send_wa->body());
                $response = json_encode($response);

                if ($send_wa->status() != 200){
                    LogNotif::create([
                        'nama' => $manager->user->name,
                        'status' => 'gagal',
                        'message' => $message,
                        'response' => $response,
                        'phone' =>  $manager->user->phone
                    ]);
                } else {
                    LogNotif::create([
                        'nama' => $manager->user->name,
                        'status' => 'sukses',
                        'message' => $message,
                        'response' => $response,
                        'phone' => $manager->user->phone,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan Berhasil Dibuat', 'data' => $data]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function generateCode($length = 10) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Permintaan::where('no_rm', $id)->orderBy('tanggal', 'desc')->first();

            if (!$data){
                return response()->json(['status' => 'error', 'message' => 'Pasien Tidak Ada']);
            }

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
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
        try {
            $data = Permintaan::where('no_rm', $request->no_rm)->where('kategori', $request->kategori)->orderBy('tanggal', 'desc')->get();

            if ($data->count() == 0){
                return response()->json(['status' => 'error', 'message' => 'Permintaan Tidak Dapat Dibuat', 'total' => 0]);
            }

            foreach ($data as $item){
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
            $data = Permintaan::find($id);

            DB::beginTransaction();

            if ($data->status != 'menunggu'){
                return response()->json(['status' => 'error', 'message' => 'Permintaan Tidak Dapat Dihapus']);
            }

            $data->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Permintaan Berhasil Dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
