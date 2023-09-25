<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\ThnPel;
use App\Models\Mapel;
use App\Models\Guru;    
use App\Models\GuruMapel;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Presensi;

class C_siswa extends Controller
{
    //SISWA
    public function siswa()
    {
        $jurusans = Jurusan::all();
        $kelas = Kelas::all();
        return view('admin.datasiswa',compact('jurusans','kelas'));
    }
    public function getSiswa(){
        $siswa = Siswa::all();
        $siswa = Siswa::with('kelas','jurusan')->where('is_active', "1")->get();        
        return response()->json($siswa);
    }
    public function storeSiswa(Request $request)
    {
        // Validasi data $request disini
        $siswa = new Siswa;
        $siswa->nisn = $request->input('nisn');
        $siswa->nama = $request->input('nama');
        $siswa->is_active = $request->input('is_active');
        $siswa->jeniskelamin = $request->input('jeniskelamin');
        $siswa->kelas_id = $request->input('kelas_id');
        $siswa->jurusan_id = $request->input('jurusan_id');
        $siswa->save();

        return response()->json(['message' => 'Siswa berhasil ditambahkan']);
    }
    public function showSiswa($id)
    {
        $siswa = Siswa::find($id);
        return response()->json($siswa);
    }
    public function updateSiswa(Request $request, $id)
    {
        // Validasi data $request disini
        $siswa = Siswa::find($id);
        $siswa->nisn = $request->input('nisn');
        $siswa->nama = $request->input('nama');
        $siswa->is_active = $request->input('is_active');
        $siswa->jeniskelamin = $request->input('jeniskelamin');
        $siswa->kelas_id = $request->input('kelas_id');
        $siswa->jurusan_id = $request->input('jurusan_id');
        $siswa->save();   

        return response()->json(['message' => 'Siswa berhasil diedit']);
    }
    public function destroySiswa($id)
    {
        $siswa = Siswa::find($id);
        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }

    public function siswaacc()
    {
        $siswa = Siswa::all();
        $jurusans = Jurusan::all();
        $kelas = Kelas::all();
        return view('admin.siswaacc',compact('jurusans','kelas','siswa'));
    }
    public function getUser()
    {
        $users = User::all();
        $users = User::where('role_id', 3)->get();
        return response()->json($users);
    }
}
