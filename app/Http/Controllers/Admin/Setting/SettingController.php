<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcement = Setting::where('key', 'announcement')->first()?->value ?? '';

        return view('admin.setting.index', compact('announcement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'announcement' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            Setting::updateOrCreate(
                ['key' => 'announcement'],
                ['value' => $request->announcement]
            );

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Running text berhasil diperbarui']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
