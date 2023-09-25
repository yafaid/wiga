<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;

class C_admin extends Controller
{
    public function admin()
    {
        $user = User::all();
        $userR = UserRole::all();
        return view('admin.adminacc', compact('user', 'userR'));
    }

    public function getUser()
    {
        $users = User::all();
        $users = User::where('role_id', 1)->get();
        return response()->json($users);
    }

    public function addUser(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|string|max:255',
            'role_id' => 'required|integer',
        ]);

        // Buat instance model User dan isi dengan data dari form
        $user = new User([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')), // Hash password
            'role_id' => $request->input('role_id'),
        ]);

        // Simpan data ke database
        $user->save();

        return response()->json(['message' => 'Akun berhasil ditambahkan']);
    }
    public function destroyUser($id)
    {
        $users = User::find($id);
        $users->delete();

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }
}
