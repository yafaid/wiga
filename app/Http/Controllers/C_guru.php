<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;

class C_guru extends Controller
{
    //GURU
    public function guru()
    {
        $guru = Guru::all();
        return view('admin.guru', compact('guru'));
    }
    public function getGuru()
    {
        $guru = Guru::all();
        return response()->json($guru);
    }
    public function showGuru($id)
    {
        $guru = Guru::find($id);
        return response()->json($guru);
    }
    public function storeGuru(Request $request)
    {
        // Validasi data $request disini
        $guru = new Guru;
        $guru->kodeguru = $request->input('kodeguru');
        $guru->noinduk = $request->input('noinduk');
        $guru->nama = $request->input('nama');
        $guru->save();

        return response()->json(['message' => 'Guru berhasil ditambahkan']);
    }
    public function updateGuru(Request $request, $id)
    {
        // Validasi data $request disini
        $guru = Guru::find($id);
        $guru->kodeguru = $request->input('kodeguru');
        $guru->noinduk = $request->input('noinduk');
        $guru->nama = $request->input('nama');
        $guru->save();

        return response()->json(['message' => 'Guru berhasil diedit']);
    }
    public function destroyGuru($id)
    {
        $tahun = Guru::find($id);
        $tahun->delete();

        return response()->json(['message' => 'Guru berhasil dihapus']);
    }

    public function guruacc()
    {
        $guru = Guru::all();
        return view('admin.guruacc', compact('guru'));
    }
    public function getUser()
    {
        $users = User::all();
        $users = User::where('role_id', 2)->get();
        return response()->json($users);
    }


    public function dbguru()
    {
        $kelas = Kelas::all();
        $guru = Guru::all();
        return view('guru.dashboard', compact('guru', 'kelas'));
    }
    public function profil()
    {
        return view('guru.profil');
    }
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $currentPassword = $request->input('currentPassword');
        $newPassword = $request->input('newPassword');
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'currentPassword' => 'Password saat ini salah.'
            ]);
        }
        $user->password = Hash::make($newPassword);
        $user->save();
        return response()->json(['message' => 'Password berhasil diubah.']);
    }
    public function changeUsername(Request $request)
    {
        $user = Auth::user();
        $currentUsername = $request->input('currentUsername');
        $newUsername = $request->input('newUsername');

        if ($currentUsername !== $user->username) {
            throw ValidationException::withMessages([
                'currentUsername' => 'Username saat ini salah.'
            ]);
        }

        $user->username = $newUsername;
        $user->save();

        return response()->json(['message' => 'Username berhasil diubah.']);
    }

    public function viewabsen()
    {
        $kelas = Kelas::get();
        return view('guru.viewabsen', compact('kelas'));
    }
}
