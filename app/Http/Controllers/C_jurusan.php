<?php

namespace App\Http\Controllers;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class C_jurusan extends Controller
{
    //JURUSAN
    public function jurusan()
    {
        $jurusans = Jurusan::paginate(10);
        $jurusans = Jurusan::all();
        return view('admin.jurusan',compact('jurusans'));
        
    }
    public function getJurusans()
    {
        $jurusans = Jurusan::all();
        return response()->json($jurusans);
    }
    public function showJur($id)
    {
        $jurusan = Jurusan::find($id);
        return response()->json($jurusan);
    }
    public function storeJur(Request $request)
    {
        // Validasi data $request disini
        $jurusan = new Jurusan;
        $jurusan->kodejur = $request->input('kodejur');
        $jurusan->nama = $request->input('nama');
        $jurusan->save();

        return response()->json(['message' => 'Jurusan berhasil ditambahkan']);
    }
    public function updateJur(Request $request, $id)
    {
        // Validasi data $request disini
        $jurusan = Jurusan::find($id);
        $jurusan->kodejur = $request->input('kodejur');
        $jurusan->nama = $request->input('nama');
        $jurusan->save();   

        return response()->json(['message' => 'Jurusan berhasil diedit']);
    }
    public function destroyJur($id)
    {
        $jurusan = Jurusan::find($id);
        $jurusan->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus']);
    }
}
