<?php

namespace App\Http\Controllers\Admin\Lokasi;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Lokasi::orderBy('created_at', 'desc')->get();

        return view('admin.lokasi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Lokasi::orderBy('created_at', 'desc')->get();
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
                $data = Lokasi::findOrFail($request->product_id);
                $data->update([
                    'nama' => $request->nama,
                    'lantai' => $request->lantai
                ]);
            } else {
                $data = Lokasi::create([
                    'nama' => $request->nama,
                    'lantai' => $request->lantai
                ]);
            }

            DB::commit();
            return response()->json(['status'=> 'success' ,'message' => 'Data berhasil disimpan']);

        } catch (\Throwable $th) {
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
            $data = Lokasi::findOrFail($id);
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
            $data = Lokasi::findOrFail($id);
            $data->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
