<?php

namespace App\Http\Controllers\Admin\Shift;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('role_id', 1)->with('role')->orderBy('name', 'asc')->get();

        $users = $users->map(function ($user) {
            return [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role?->alias ?? '-',
            ];
        });

        $shifts = Shift::orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc')->get();

        $shifts = $shifts->map(function ($shift) {
            return [
                'id' => $shift->id,
                'userId' => $shift->user_id,
                'date' => $shift->tanggal,
                'startTime' => date('H:i', strtotime($shift->jam_mulai)),
                'endTime' => date('H:i', strtotime($shift->jam_selesai)),
                'lantai' => $shift->lantai,
            ];
        });

        $lantais = \App\Models\Lokasi::select('lantai')->distinct()->whereNotNull('lantai')->orderBy('lantai', 'asc')->get()->pluck('lantai');

        return view('admin.shift.index', compact('users', 'shifts', 'lantais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = Shift::orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc')->get();

        $shifts = $shifts->map(function ($shift) {
            return [
                'id' => $shift->id,
                'userId' => $shift->user_id,
                'date' => $shift->tanggal,
                'startTime' => date('H:i', strtotime($shift->jam_mulai)),
                'endTime' => date('H:i', strtotime($shift->jam_selesai)),
                'lantai' => $shift->lantai,
            ];
        });

        return response()->json($shifts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // --- PERUBAHAN DIMULAI DI SINI ---
        try {
            // 1. Validasi diubah untuk menerima array tanggal
            $validatedData = $request->validate([
                'userId' => 'required|exists:users,id',
                'dates' => 'required|array', // Pastikan 'dates' adalah array
                'dates.*' => 'required|date_format:Y-m-d', // Validasi setiap item dalam array
                'startTime' => 'required',
                'endTime' => 'required',
                'lantai' => 'required',
            ]);

            DB::beginTransaction();

            // 2. Lakukan perulangan untuk setiap tanggal yang dikirim
            $lantaiStr = is_array($request->lantai) ? implode(',', array_map('trim', $request->lantai)) : trim($request->lantai);

            foreach ($request->dates as $date) {
                $jam_mulai = date('Y-m-d H:i', strtotime($date . ' ' . $request->startTime));
                $jam_selesai = date('Y-m-d H:i', strtotime($date . ' ' . $request->endTime));

                Shift::create([
                    'user_id' => $request->userId,
                    'tanggal' => $date,
                    'jam_mulai' => $jam_mulai,
                    'jam_selesai' => $jam_selesai,
                    'lantai' => $lantaiStr,
                ]);
            }

            DB::commit();

            // Respon sukses (tidak perlu mengembalikan data karena frontend akan refresh)
            return response()->json(['status' => 'success', 'message' => 'Data Shift Berhasil Ditambahkan']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
        // --- PERUBAHAN SELESAI DI SINI ---
    }

    public function store2(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'userId' => 'required|exists:users,id',
                'date' => 'required|date',
                'startTime' => 'required',
                'endTime' => 'required',
            ]);

            $jam_mulai = date('Y-m-d H:i', strtotime($request->tanggal . ' ' . $request->startTime));
            $jam_selesai = date('Y-m-d H:i', strtotime($request->tanggal . ' ' . $request->endTime));

            DB::beginTransaction();

            $shift = Shift::create([
                'user_id' => $request->userId,
                'tanggal' => $request->date,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Data Shift Berhasil Ditambahkan', 'data' => $shift]);
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
        try {
            // 1. Validasi input yang masuk
            $validatedData = $request->validate([
                'userId' => 'required|exists:users,id',
                'startTime' => 'required',
                'endTime' => 'required',
                'lantai' => 'required',
            ]);

            DB::beginTransaction();

            // 2. Cari shift yang akan diupdate berdasarkan ID
            $shift = Shift::findOrFail($id);

            // 3. Siapkan data jam mulai dan selesai yang baru
            //    Kita gunakan tanggal yang sudah ada dari shift itu sendiri
            $jam_mulai = date('Y-m-d H:i:s', strtotime($shift->tanggal . ' ' . $request->startTime));
            $jam_selesai = date('Y-m-d H:i:s', strtotime($shift->tanggal . ' ' . $request->endTime));

            // 4. Lakukan update data
            $lantaiStr = is_array($request->lantai) ? implode(',', array_map('trim', $request->lantai)) : trim($request->lantai);

            $shift->update([
                'user_id' => $request->userId,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'lantai' => $lantaiStr,
            ]);

            DB::commit();

            // 5. Kirim respon sukses
            return response()->json(['status' => 'success', 'message' => 'Data Shift Berhasil Diperbarui']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Kirim respon error jika terjadi masalah
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::beginTransaction();

            $shift = Shift::findOrFail($id);
            $shift->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Data Shift Berhasil Dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
