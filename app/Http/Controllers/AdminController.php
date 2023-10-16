<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\User;

class AdminController extends Controller
{
    // Menampilkan halaman admin
    public function index()
    {
        $totalsiswa = Siswa::where('is_active', 1)->count();
        $totalsiswal = Siswa::where('jeniskelamin', 'L')
            ->where('is_active', 1)
            ->count();

        $totalsiswap = Siswa::where('jeniskelamin', 'P')
            ->where('is_active', 1)
            ->count();
        $totalguru = Guru::count();
        $totalmapel = Mapel::count();
        $totalkelas = Kelas::count();
        $totaladmin = User::where('role_id', 1)->count();
        return view('admin.dashboard', compact('totalsiswa', 'totalguru', 'totaladmin', 'totalsiswal', 'totalsiswap'));
    }





    // PROFIL
    public function profil()
    {
        return view('admin.profil');
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

    // Metode untuk mengganti username
    public function changeUsername(Request $request)
    {
        $user = auth()->user();
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

    

    //PRESENSI
    public function presensi()
    {
        $siswa = Siswa::all();
        $kelas = kelas::all();
        $mapel = Mapel::all();

        return view('admin.presensi', compact('siswa', 'kelas', 'mapel'));
    }
    public function storePresensi(Request $request)
    {
        // Validasi data yang dikirimkan melalui formulir
        $validator = Validator::make($request->all(), [
            'siswa_id.*' => 'required|numeric',
            'mapel_id.*' => 'required|numeric',
            'kelas_id.*' => 'required|numeric',
            'tanggal.*' => 'required|date',
            'keterangan.*' => 'required|string',
        ]);

        // Jika validasi gagal, kembalikan dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Iterasi melalui data yang dikirim dari formulir dan menyimpannya ke dalam database
        foreach ($request->siswa_id as $key => $siswaId) {
            Presensi::create([
                'siswa_id' => $siswaId,
                'mapel_id' => $request->mapel_id[$key],
                'kelas_id' => $request->kelas_id[$key],
                'tanggal' => $request->tanggal[$key],
                'keterangan' => $request->keterangan[$key],
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data absensi berhasil disimpan.');
    }
}
