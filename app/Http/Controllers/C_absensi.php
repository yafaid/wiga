<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\E_Prisens_mapel;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Mapel;

class C_absensi extends Controller
{
    function index(Request  $request)
    {
        $kelas_id  = $request->input('kelas_id');

        $data = Siswa::where('kelas_id', $kelas_id );
        $html = '';

        if(!$data->count() == 0){
            foreach ($data->get() as $key => $value) {
                $data_user = Presensi::where([ 'siswa_id' => $value->id,'mapel_id' => $request->kode_mapel, 'tanggal' => $request->tanggal]);

                if(!$data_user->count() == 0){
                    $user_status = $data_user->first();

                
                    $html .= "<tr>
                                <td>{$value->nama}</td>
                                <td><input class='form-check-input' type='radio' " . ($user_status->keterangan == 'hadir' ? 'checked' : '') . " id='{$value->id}_hadir' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_hadir'></td>
                                <td><input class='form-check-input' type='radio' " . ($user_status->keterangan == 'alpha' ? 'checked' : '') . " id='{$value->id}_alpha' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_alpha'></td>
                                <td><input class='form-check-input' type='radio' " . ($user_status->keterangan == 'izin' ? 'checked' : '') . " id='{$value->id}_izin' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_izin'></td>
                                <td><input class='form-check-input' type='radio' " . ($user_status->keterangan == 'sakit' ? 'checked' : '') . " id='{$value->id}_sakit' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_sakit'></td>
                            </tr>";
                    
                }else{
                    $html .= "<tr>
                                <td>{$value->nama}</td>
                                <td><input class='form-check-input' type='radio'  id='{$value->id}_hadir' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_hadir'></td>
                                <td><input class='form-check-input' type='radio'  id='{$value->id}_alpha' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_alpha'></td>
                                <td><input class='form-check-input' type='radio'  id='{$value->id}_izin'  onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_izin'></td>
                                <td><input class='form-check-input' type='radio'  id='{$value->id}_sakit' onclick='buttonPrisensi(this.value)' name='{$value->id}_prisensi' value='{$value->id}_sakit'></td>
                            </tr>";
                }
    
            }

        }else{
            $html .= "<tr>
                        <td colspan='5' class='text-center'>Tidak ada data</td>
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

        $count_siswa = Presensi::where(['siswa_id' => $id ,'tanggal' => $request->tanggal, 'mapel_id' => $request->mapel ])->count();
        
        if($count_siswa == 0){
                Presensi::create([
                    'siswa_id' => $id,
                    'mapel_id' => $request->mapel,
                    'kelas_id' => $request->kode_kelas,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $status,
                ]);
        }else{
            Presensi::where(['siswa_id' => $id ,'tanggal' => $request->tanggal, 'mapel_id' => $request->mapel])->update([
                'mapel_id' => $request->mapel,
                'kelas_id' => $request->kode_kelas,
                'tanggal' => $request->tanggal,
                'keterangan' => $status,
            ]);
        }
        return response()->json(['message' => 'berhasil diedit','data' => 'berhasil update'], 200);
        
    }

    function selectMapel(Request $request)
    {
        $data = Mapel::where('kelas_id', $request->kelas_id)->get();
        $html = '<option value="">-- Pilih Mapel --</option>';
        foreach ($data as $key => $value) {
            $html .= "<option value='{$value->id}'>{$value->mapel}</option>";
        }

        return response()->json(['message' => 'berhasil ambil data','data' => $html], 200);
    }

    public function exportPrisensiMapel(Request $request)
{
    $dateRange = $request->tanggal_export;
    $dateParts = explode(" - ", $dateRange);
    $startDate = date('Y-m-d', strtotime($dateParts[0]));
    $endDate = date('Y-m-d', strtotime($dateParts[1]));

    $data = Presensi::join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
        ->join('kelas', 'absensi.kelas_id', '=', 'kelas.id')
        ->join('mapels', 'absensi.mapel_id', '=', 'mapels.id')
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->where('absensi.kelas_id', $request->kode_kelas)
        ->select('absensi.*', 'siswa.nama', 'kelas.kodekelas', 'mapels.mapel')
        ->get();

    return Excel::download(new E_Prisens_mapel($data), 'dataabsensimapel.xlsx');
}
    public function viewTable(){
        return view('admin.profil');
    }
 
}
