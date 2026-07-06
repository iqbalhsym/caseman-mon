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

    public function export(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Export parameters received:', $request->all());

        $query = Permintaan::with('user', 'lokasi', 'penjamin', 'manager');

        $filterBy = $request->get('filter_by', 'created_at');
        $column = ($filterBy === 'tanggal') ? 'tanggal' : 'created_at';

        // Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            if ($column === 'tanggal') {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            } else {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        } elseif ($request->filled('start_date')) {
            if ($column === 'tanggal') {
                $query->where('tanggal', $request->start_date);
            } else {
                $query->whereDate('created_at', $request->start_date);
            }
        }

        if ($request->filled('month')) {
            $monthYear = Carbon::parse($request->month);
            $query->whereYear($column, $monthYear->year)
                  ->whereMonth($column, $monthYear->month);
        }

        \Illuminate\Support\Facades\Log::info('Export query SQL:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $data = $query->orderBy($column, 'asc')->get();

        \Illuminate\Support\Facades\Log::info('Export results count:', ['count' => $data->count()]);
        $format = $request->get('format', 'excel');
        $filename = 'laporan_permintaan_' . date('Ymd_His');

        if ($format === 'csv') {
            // Ekspor CSV
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
            ];

            return response()->stream(function () use ($data) {
                $handle = fopen('php://output', 'w');
                
                // Kolom Header
                fputcsv($handle, [
                    'No', 'Tanggal Pengajuan', 'No. RM', 'Nama Pasien', 'Umur', 'Jaminan',
                    'Ruangan/Lokasi', 'Diagnosis', 'Kategori', 'Keterangan/Obat', 'Indikasi', 
                    'Status', 'Case Manager', 'Catatan CM', 'Waktu Respon'
                ]);

                foreach ($data as $idx => $item) {
                    $lokasiText = $item->lokasi ? ($item->lokasi->nama . ' Lt. ' . $item->lokasi->lantai) : ($item->ruangan ? ($item->ruangan . ' Lt. ' . $item->lantai) : '-');
                    $detailObatText = $item->detail_paket ? json_encode($item->detail_paket) : ($item->detail_obat ? strip_tags($item->detail_obat) : $item->keterangan);
                    
                    fputcsv($handle, [
                        $idx + 1,
                        $item->created_at->format('d-m-Y H:i'),
                        $item->no_rm,
                        $item->nama,
                        $item->umur ?? '-',
                        $item->penjamin?->nama ?? '-',
                        $lokasiText,
                        $item->diagnosis ?? '-',
                        ucfirst($item->kategori),
                        $detailObatText,
                        $item->indikasi ?? '-',
                        ucfirst($item->status),
                        $item->manager?->name ?? '-',
                        $item->catatan_diterima ?? '-',
                        $item->tanggal_jam_respon ?? '-'
                    ]);
                }
                fclose($handle);
            }, 200, $headers);
        } else {
            // Ekspor Excel (HTML format)
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
            ];

            return response()->stream(function () use ($data) {
                echo '<table border="1">';
                echo '<tr>';
                echo '<th>No</th><th>Tanggal Pengajuan</th><th>No. RM</th><th>Nama Pasien</th><th>Umur</th><th>Jaminan</th>';
                echo '<th>Ruangan/Lokasi</th><th>Diagnosis</th><th>Kategori</th><th>Keterangan/Obat</th><th>Indikasi</th>';
                echo '<th>Status</th><th>Case Manager</th><th>Catatan CM</th><th>Waktu Respon</th>';
                echo '</tr>';

                foreach ($data as $idx => $item) {
                    $lokasiText = $item->lokasi ? ($item->lokasi->nama . ' Lt. ' . $item->lokasi->lantai) : ($item->ruangan ? ($item->ruangan . ' Lt. ' . $item->lantai) : '-');
                    $detailObatText = $item->detail_paket ? json_encode($item->detail_paket) : ($item->detail_obat ? strip_tags($item->detail_obat) : $item->keterangan);
                    
                    echo '<tr>';
                    echo '<td>' . ($idx + 1) . '</td>';
                    echo '<td>' . $item->created_at->format('d-m-Y H:i') . '</td>';
                    echo '<td>' . htmlspecialchars($item->no_rm) . '</td>';
                    echo '<td>' . htmlspecialchars($item->nama) . '</td>';
                    echo '<td>' . htmlspecialchars($item->umur ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars($item->penjamin?->nama ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars($lokasiText) . '</td>';
                    echo '<td>' . htmlspecialchars($item->diagnosis ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars(ucfirst($item->kategori)) . '</td>';
                    echo '<td>' . htmlspecialchars($detailObatText) . '</td>';
                    echo '<td>' . htmlspecialchars($item->indikasi ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars(ucfirst($item->status)) . '</td>';
                    echo '<td>' . htmlspecialchars($item->manager?->name ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars($item->catatan_diterima ?? '-') . '</td>';
                    echo '<td>' . htmlspecialchars($item->tanggal_jam_respon ?? '-') . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }, 200, $headers);
        }
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
