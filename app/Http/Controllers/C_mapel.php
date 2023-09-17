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
use App\Models\Presensi;

class C_mapel extends Controller
{
    //MATA PELAJARAN
    public function mapel(){
        $mapel = Mapel::all();
        $kelas = kelas::all();
        return view('admin.mapel',compact('mapel','kelas'));
    }
    public function getMapel(){
        $mapel = Mapel::all();
        $mapel = Mapel::with('kelas')->get();
        return response()->json($mapel);
    }
    public function showMapel($id)
    {
        $mapel = Mapel::find($id);
        return response()->json($mapel);
    }
    public function storeMapel(Request $request)
    {
        // Validasi data $request disini
        $mapel = new Mapel;
        $mapel->kodemapel = $request->input('kodemapel');
        $mapel->mapel = $request->input('mapel');
        $mapel->kelas_id = $request->input('kelas_id');
        $mapel->save();

        return response()->json(['message' => 'Mata Pelajaran berhasil ditambahkan']);
    }
    public function updateMapel(Request $request, $id)
    {
        // Validasi data $request disini
        $mapel = Mapel::find($id);
        $mapel->kodemapel = $request->input('kodemapel');
        $mapel->mapel = $request->input('mapel');
        $mapel->kelas_id = $request->input('kelas_id');
        $mapel->save();  

        return response()->json(['message' => 'Mata Pelajaran berhasil diedit']);
    }
    public function destroyMapel($id)
    {
        $mapel = Mapel::find($id);
        $mapel->delete();

        return response()->json(['message' => 'Mata Pelajaran berhasil dihapus']);
    }
}
