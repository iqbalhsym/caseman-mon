<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::withCount('users')->orderBy('id')->get();
        return view('admin.user.role', compact('data'));
    }

    public function create()
    {
        try {
            $data = Role::orderBy('id')->get();
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'alias' => 'required',
        ], [
            'nama.required'  => 'Nama role tidak boleh kosong',
            'alias.required' => 'Alias tidak boleh kosong',
        ]);

        DB::beginTransaction();
        try {
            if ($request->product_id) {
                $role = Role::findOrFail($request->product_id);
                $role->update([
                    'name'  => strtolower(str_replace(' ', '_', $request->nama)),
                    'alias' => $request->alias,
                ]);
                $msg = 'Role berhasil diperbarui';
            } else {
                Role::create([
                    'name'  => strtolower(str_replace(' ', '_', $request->nama)),
                    'alias' => $request->alias,
                ]);
                $msg = 'Role berhasil ditambahkan';
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => $msg]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        try {
            $data = Role::findOrFail($id);
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->id === 1) {
                return response()->json(['status' => 'error', 'message' => 'Role Administrator tidak dapat dihapus']);
            }

            if ($role->users()->count() > 0) {
                return response()->json(['status' => 'error', 'message' => 'Role masih digunakan oleh pengguna, tidak dapat dihapus']);
            }

            DB::beginTransaction();
            $role->delete();
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Role berhasil dihapus']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
