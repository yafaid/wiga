<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;

class C_absensi extends Controller
{
    function index(Request  $request)
    {
        $kelas_id  = $request->input('kelas_id');

        $data = Siswa::where('kelas_id', $kelas_id )->get();
        $html = '';
        foreach ($data as $key => $value) {
            $html .= "<tr>
                        <td>{$value->nama}</td>
                        <td><input class='form-check-input' type='radio'  id='{$value->id}_hadir' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_hadir'></td>
                        <td><input class='form-check-input' type='radio'  id='{$value->id}_alpha' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_alpha'></td>
                        <td><input class='form-check-input' type='radio'  id='{$value->id}_izin'  onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_izin'></td>
                        <td><input class='form-check-input' type='radio'  id='{$value->id}_sakit' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_sakit'></td>
                     </tr>";
        }

        return response()->json(['message' => 'berhasil ambil data','data' => $html], 200);

    }

    function simpanData(Request $request)
    {
        $inputString = $request->status;
        $parts = explode('_', $inputString);
        $id = $parts[0];  // id ("1")
        $status = $parts[1];  // status ("_")

        $count_siswa = Presensi::where(['siswa_id' => $id ,'tanggal' => $request->tanggal])->count();
        
        if($count_siswa == 0){
                Presensi::create([
                    'siswa_id' => $id,
                    'mapel_id' => $request->mapel,
                    'kelas_id' => $request->kode_kelas,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $status,
                ]);
        }else{
            Presensi::where(['siswa_id' => $id ,'tanggal' => $request->tanggal])->update([
                'mapel_id' => $request->mapel,
                'kelas_id' => $request->kode_kelas,
                'tanggal' => $request->tanggal,
                'keterangan' => $status,
            ]);
        }
        return response()->json(['message' => 'berhasil diedit','data' => 'berhasil update'], 200);
        
    }
}
