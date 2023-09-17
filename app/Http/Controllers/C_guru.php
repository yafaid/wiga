<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Guru;    

class C_guru extends Controller
{
    //GURU
    public function guru()
    {        
        $guru = Guru::all();
        return view('admin.guru',compact('guru'));
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
}
