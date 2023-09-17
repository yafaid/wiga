<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Kelas;

class C_kelas extends Controller
{
    ////KELAS
    public function kelas()
    {        
        $jurusans = Jurusan::all();
        return view('admin.kelas',compact('jurusans'));
    }

    public function getKelas(){
        $kelas = Kelas::all();
        $kelas = Kelas::with('jurusan')->get();
        return response()->json($kelas);
    }
    public function showKel($id)
    {
        $kelas = Kelas::find($id);
        return response()->json($kelas);
    }
    public function storeKel(Request $request)
    {
        // Validasi data $request disini
        $kelas = new Kelas;
        $kelas->kodekelas = $request->input('kodekelas');
        $kelas->deskripsi = $request->input('deskripsi');
        $kelas->ruangan = $request->input('ruangan');
        $kelas->jurusan_id = $request->input('jurusan_id');
        $kelas->save();

        return response()->json(['message' => 'Jurusan berhasil ditambahkan']);
    }
    public function updateKel(Request $request, $id)
    {
        // Validasi data $request disini
        $kelas = Kelas::find($id);
        $kelas->kodekelas = $request->input('kodekelas');
        $kelas->deskripsi = $request->input('deskripsi');
        $kelas->ruangan = $request->input('ruangan');
        $kelas->jurusan_id = $request->input('jurusan_id');
        $kelas->save();   

        return response()->json(['message' => 'Jurusan berhasil diedit']);
    }
    public function destroyKel($id)
    {
        $kelas = Kelas::find($id);
        $kelas->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus']);
    }
}
