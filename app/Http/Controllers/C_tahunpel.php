<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ThnPel;


class C_tahunpel extends Controller
{
     //TAHUN PELAJARAN
     function tahunpelajaran(){
        $tahun = ThnPel::all();
        return view('admin.tahunpelajaran',compact('tahun'));
    }
    public function getTP()
    {
        $tahun = ThnPel::all();
        return response()->json($tahun);
    }
    public function showTP($id)
    {
        $tahun = ThnPel::find($id);
        return response()->json($tahun);
    }
    public function storeTP(Request $request)
    {
        // Validasi data $request disini
        $tahun = new ThnPel;
        $tahun->tahun = $request->input('tahun');
        $tahun->semester = $request->input('semester');
        $tahun->save();

        return response()->json(['message' => 'Tahun berhasil ditambahkan']);
    }
    public function updateTP(Request $request, $id)
    {
        // Validasi data $request disini
        $tahun = ThnPel::find($id);
        $tahun->tahun = $request->input('tahun');
        $tahun->semester = $request->input('semester');
        $tahun->save();   

        return response()->json(['message' => 'Tahun berhasil diedit']);
    }
    public function destroyTP($id)
    {
        $tahun = ThnPel::find($id);
        $tahun->delete();

        return response()->json(['message' => 'Tahun berhasil dihapus']);
    }
}
