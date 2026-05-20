<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\JenisCuti;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permintaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role_id == 3){
            return to_route('admin.permintaan.index');
        } else if ($user->role_id == 4){
            return to_route('admin.list-permintaan.index');
        } else if ($user->role_id == 5){
            return to_route('admin.viewer.index');
        } else {
            // Limit dashboard to last 7 days (including today)
            $since = Carbon::now()->subDays(6)->startOfDay();

            $totalRequests = Permintaan::where('created_at', '>=', $since)->count();
            $approved = Permintaan::where('status', 'disetujui')->where('created_at', '>=', $since)->count();
            $rejected = Permintaan::where('status', 'ditolak')->where('created_at', '>=', $since)->count();
            $pending = Permintaan::where('status', 'menunggu')->where('created_at', '>=', $since)->count();
            $cancelled = Permintaan::where('status', 'batal')->where('created_at', '>=', $since)->count();

            // Recent 6 requests within last 7 days
            $recent = Permintaan::with('lokasi')
                ->where('created_at', '>=', $since)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function($p){
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama,
                        'no_rm' => $p->no_rm,
                        'lokasi' => $p->lokasi?->nama . ' Lt. ' . $p->lokasi?->lantai,
                        'kategori' => $p->kategori,
                        'status' => $p->status,
                        'tanggal' => $p->created_at->format('d-m-Y H:i')
                    ];
                });

            // Counts per category within last 7 days
            $byCategory = Permintaan::where('created_at', '>=', $since)
                ->select('kategori', DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->orderBy('total', 'desc')
                ->pluck('total', 'kategori')
                ->toArray();

            // Trend last 7 days
            $labels = [];
            $trend = [];
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $labels[] = $day->format('d M');
                $count = Permintaan::whereDate('created_at', $day->toDateString())->where('created_at', '>=', $since)->count();
                $trend[] = $count;
            }

            return view('admin.dashboard.index', compact(
                'totalRequests','approved','rejected','pending','cancelled','recent','byCategory','labels','trend'
            ));
        }
    }
}
