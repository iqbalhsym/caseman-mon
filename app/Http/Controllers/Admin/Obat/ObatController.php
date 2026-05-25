<?php

namespace App\Http\Controllers\Admin\Obat;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ObatImport;
use App\Exports\ObatExport;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Obat::orderBy('created_at', 'desc')->get();

        return view('admin.obat.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Obat::orderBy('created_at', 'desc')->get();
        return response()->json(['status'=> 'success' ,'data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            if ($request->product_id) {
                $data = Obat::findOrFail($request->product_id);
                $data->update([
                    'f_nf' => $request->f_nf,
                    'nama_generik' => $request->nama_generik,
                    'kode_item' => $request->kode_item,
                    'nama_item' => $request->nama_item,
                    'warna' => $request->warna
                ]);
            } else {
                $data = Obat::create([
                    'f_nf' => $request->f_nf,
                    'nama_generik' => $request->nama_generik,
                    'kode_item' => $request->kode_item,
                    'nama_item' => $request->nama_item,
                    'warna' => $request->warna
                ]);
            }

            DB::commit();
            return response()->json(['status'=> 'success' ,'message' => 'Data berhasil disimpan']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status'=> 'error' ,'message' => $th->getMessage()]);
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
            $data = Obat::findOrFail($id);
            return response()->json(['status'=> 'success' ,'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status'=> 'error' ,'message' => $th->getMessage()]);
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
        try {
            DB::beginTransaction();
            $data = Obat::findOrFail($id);
            $data->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'warna' => 'required|in:hijau,kuning,merah,none'
        ]);

        try {
            $warna = $request->warna == 'none' ? null : $request->warna;
            Excel::import(new ObatImport($warna), $request->file('file'));
            return response()->json(['status' => 'success', 'message' => 'Data Obat berhasil diimport']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat import: ' . $e->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new ObatExport, 'Data_Obat.xlsx');
    }

    public function searchObat(Request $request)
    {
        $query = strtolower($request->get('q'));
        if(!$query) {
            return response()->json([]);
        }

        $obats = Obat::where(DB::raw('lower(nama_item)'), 'like', "%{$query}%")
                     ->orWhere(DB::raw('lower(kode_item)'), 'like', "%{$query}%")
                     ->orWhere(DB::raw('lower(nama_generik)'), 'like', "%{$query}%")
                     ->limit(20)
                     ->get(['id', 'kode_item', 'nama_item', 'nama_generik', 'warna']);

        return response()->json($obats);
    }
}
