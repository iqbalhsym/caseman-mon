<?php

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // --- PERUBAHAN DIMULAI DI SINI ---
    public function index(Request $request)
    {
        // Mulai query builder
        $query = Permintaan::query();

        // 1. Filter berdasarkan rentang tanggal (start_date & end_date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        // 2. Filter berdasarkan satu tanggal saja (jika hanya start_date yang diisi)
        elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', $request->start_date);
        }

        // 3. Filter berdasarkan bulan (format YYYY-MM)
        if ($request->filled('month')) {
            $monthYear = Carbon::parse($request->month);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        }

        // Eksekusi query untuk mendapatkan data
        $data = $query->get();

        // Mapping data tetap sama
        $datas = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'kategori' => ucfirst(str_replace('_', ' ', $item->kategori)), // Ganti underscore dengan spasi
                'status' => $item->status,
            ];
        });

        // 4. Jika request adalah AJAX, kembalikan data dalam format JSON
        if ($request->ajax()) {
            return response()->json($datas);
        }

        // Jika bukan AJAX (request awal), tampilkan view dengan data
        return view('admin.laporan.index', compact('datas'));
    }
    // --- PERUBAHAN SELESAI DI SINI ---

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
