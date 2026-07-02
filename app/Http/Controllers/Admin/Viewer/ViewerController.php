<?php

namespace App\Http\Controllers\Admin\Viewer;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ViewerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Permintaan::where('status', 'disetujui')
            ->with('user', 'lokasi', 'penjamin')
            ->where('created_at', '>=', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->orderBy('status', 'asc')
            ->get();

        $datas = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'no_rm' => $item->no_rm,
                'lokasi' => $item->lokasi?->nama . ' Lt. ' . $item->lokasi?->lantai,
                'diagnosis' => $item->diagnosis,
                'kategori' => ucfirst($item->kategori),
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'jam' => date('H:i', strtotime($item->jam)),
                'tanggal' => $item->created_at,
                'tanggal_masuk' => date('d-m-Y', strtotime($item->tanggal)),
                'indikasi' => $item->indikasi,
                'catatan' => $item->catatan_diterima,
                'manager' => $item->manager?->name,
                'pengaju' => $item->user?->name ?? '-',
                'status2' => ucfirst($item->status),
                'catatan_diterima' => $item->catatan_diterima,
                'jumlah_hari' => $item->jumlah_hari,
                'tanggal_mulai_expired' => Carbon::parse($item->tanggal_mulai_expired)->translatedFormat('d F Y'),
                'tanggal_berakhir_expired' => Carbon::parse($item->tanggal_berakhir_expired)->translatedFormat('d F Y'),
                'detail_paket' => $item->detail_paket,
                'file' => $item->file ? asset($item->file) : null,
                'file2' => $item->file2 ? asset($item->file2) : null,
                'file3' => $item->file3 ? asset($item->file3) : null,
                'jaminan' => $item->penjamin?->nama ?? (\App\Models\Penjamin::find($item->lantai)?->nama ?? '-'),
                'jam_respon' => $item->tanggal_jam_respon ? date('d-m-Y H:i', strtotime($item->tanggal_jam_respon)) : '-',
            ];
        });

        return view('admin.viewer.index', compact('datas'));
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
        //
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
