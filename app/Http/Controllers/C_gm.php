<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Mapel;
use App\Models\Guru;    
use App\Models\GuruMapel;

class C_gm extends Controller
{
    //GURU DAN MAPEL
    public function gm()
    {        
        $guru = Guru::all();
        $mapel = Mapel::all();
        $gm = GuruMapel::all();
        return view('admin.gm',compact('gm','guru','mapel'));
    }
    public function getGM()
    {
        $gm = GuruMapel::all();
        $gm = GuruMapel::with('guru','mapel')->get();
        return response()->json($gm);
    }
    public function storeGM(Request $request)
    {
        $existingGM = GuruMapel::where('kodeguru', $request->input('kodeguru'))
                            ->where('kodemapel', $request->input('kodemapel'))
                            ->first();

        if ($existingGM) {
            return response()->json(['error'=>true,'message' => 'Kombinasi Kode Guru dan Kode Mapel sudah ada']); 
        }
        // Validasi data $request disini
        $gm = new GuruMapel;
        $gm->kodeguru = $request->input('kodeguru');
        $gm->kodemapel = $request->input('kodemapel');
        $gm->save();

        return response()->json(['message' => 'Guru dan Mapel berhasil ditambahkan']);
    }
    public function showGM($id)
    {
        $gm = GuruMapel::find($id);
        return response()->json($gm);
    }
    public function updateGM(Request $request, $id)
    {
        $gm = GuruMapel::find($id);
        $newKodeGuru = $request->input('kodeguru');
        $newKodeMapel = $request->input('kodemapel');
    
        // Cek apakah kombinasi kode guru dan kode mapel sudah ada dalam database
        $existingGM = GuruMapel::where('kodeguru', $newKodeGuru)
                           ->where('kodemapel', $newKodeMapel)
                           ->where('id', '!=', $id) // Kecuali untuk data yang sedang diupdate
                           ->first();
        
        if ($existingGM) {
            return response()->json(['error'=>true,'message' => 'Kombinasi kode guru dan kode mapel sudah ada']);
         }

        $gm->kodeguru = $newKodeGuru;
        $gm->kodemapel = $newKodeMapel;
        $gm->save();  

        return response()->json(['message' => 'Berhasil diedit']);
    }
    public function destroyGM($id)
    {
        $gm = GuruMapel::find($id);
        $gm->delete();

        return response()->json(['message' => 'Berhasil dihapus']);
    }
}
