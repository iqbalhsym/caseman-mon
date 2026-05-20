<?php

namespace App\Http\Controllers\Admin\Penjamin;

use App\Http\Controllers\Controller;
use App\Models\Penjamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjaminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Penjamin::all();

        return view('admin.penjamin.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Penjamin::all();
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
                $data = Penjamin::findOrFail($request->product_id);
                $data->update([
                    'nama' => $request->nama,
                    'keterangan' => $request->keterangan,
                    'status' => $request->status
                ]);
            } else {
                $data = Penjamin::create([
                    'nama' => $request->nama,
                    'keterangan' => $request->keterangan,
                    'status' => $request->status
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
            $data = Penjamin::findOrFail($id);
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

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $data = Penjamin::findOrFail($id);
            $data->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
