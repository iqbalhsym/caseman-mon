<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\LogNotif;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userRole = \Illuminate\Support\Facades\Auth::user()->role_id;
        
        if ($userRole != 1) {
            $data = User::where('id', \Illuminate\Support\Facades\Auth::user()->id)->get();
        } else {
            $data = User::orderBy('role_id', 'asc')->get();
        }

        $role = Role::get();

        return view('admin.user.index', compact('data', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $userRole = \Illuminate\Support\Facades\Auth::user()->role_id;
            
            $query = User::with('role')->orderBy('role_id', 'asc');
            
            if ($userRole != 1) {
                $query->where('id', \Illuminate\Support\Facades\Auth::user()->id);
            }
            
            $data = $query->get();
            
            $formattedData = $data->map(function($user) {
                $userData = $user->toArray();
                $userData['role_alias'] = $user->role->alias ?? '-';
                return $userData;
            });

            return response()->json(['status'=> 'success' ,'data' => $formattedData]);
        } catch (\Throwable $th) {
            return response()->json(['status'=> 'error' ,'message' => $th->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userRole = \Illuminate\Support\Facades\Auth::user()->role_id;

        if ($userRole != 1) {
            $request->validate([
                'phone' => 'required',
            ], [
                'phone.required' => 'Nomor telepon tidak boleh kosong',
            ]);

            try {
                $data = User::findOrFail(\Illuminate\Support\Facades\Auth::user()->id);
                if ($data->phone != $request->phone) {
                    $data->update(['phone' => $request->phone, 'telegram_chat_id' => null]);
                }
                return response()->json(['status' => 'success', 'message' => 'Nomor Telepon Berhasil Diperbarui', 'data' => $data]);
            } catch (\Throwable $th) {
                return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
            }
        }

        $request->validate([
            'username' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'nama.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'phone.required' => 'Nomor telepon tidak boleh kosong',
            'role.required' => 'Role tidak boleh kosong',
        ]);

        DB::beginTransaction();

        try {
            $attributes = [
                'username' => $request->username,
                'name' => ucwords(strtolower($request->nama)),
                'email' => $request->email,
                'role_id' => $request->role,
                'phone' => $request->phone,
                'status' => $request->status,
            ];

            if ($request->create === 'create') {
                $attributes['password'] =  env('APP_PASSWORD').'123456'.env('APP_PASSWORD');
                // Do NOT set 'id' if it's a new record to let DB handle it
                $data = User::create($attributes);

            } else {
                // For update, ensure product_id exists
                $data = User::findOrFail($request->product_id);
                if ($data->phone != $request->phone) {
                    $attributes['telegram_chat_id'] = null;
                }
                $data->update($attributes);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data User Berhasil Ditambahkan', 'data' => $data]);
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
        try {
            $data = User::findOrFail($id);
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
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $data = User::find($id);

            $password = env('APP_PASSWORD').'123456'.env('APP_PASSWORD');
            $data->update([
                'password' => Hash::make($password),
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Password Berhasil Direset']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = User::findOrFail($id);

            if ($data->permintaan || $data->manager){
                return response()->json(['status' => 'error', 'message' => 'User Tidak Dapat Dihapus']);
            }

            DB::beginTransaction();

            $data->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'User Berhasil Dihapus']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
