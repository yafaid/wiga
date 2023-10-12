<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\E_Prisensi_harian;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Presensi_harian;

class C_Prisensi_harian extends Controller
{
    function index()
    {
        $kelas = Kelas::get();

        return view('admin.prisensi_harian', ['kelas' => $kelas]);
    }

    function getSiswa(Request $request)
    {
        $data = Siswa::where('kelas_id', $request->kelas_id);

        $html = '';
        if(!$data->count() == 0){
            foreach($data->get() as $value){
                $data_user = Presensi_harian::where([ 'siswa_id' => $value->id, 'tanggal' => $request->tanggal]);

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

    function storeData(Request $request)
    {
        $inputString = $request->status;
        $parts = explode('_', $inputString);
        $id = $parts[0];  // id ("1")
        $status = $parts[1];  // status ("_")
        $count_siswa = Presensi_harian::where(['siswa_id' => $id ,'tanggal' => $request->tanggal])->count();
        
        if($count_siswa == 0){
            Presensi_harian::create([
                'siswa_id' => $id,
                'kelas_id' => $request->kode_kelas,
                'tanggal' => $request->tanggal,
                'keterangan' => $status,
            ]);
        }else{
            Presensi_harian::where(['siswa_id' => $id ,'tanggal' => $request->tanggal])->update([
                'kelas_id' => $request->kode_kelas,
                'tanggal' => $request->tanggal,
                'keterangan' => $status,
            ]);
        }
        return response()->json(['message' => 'berhasil diedit','data' => 'berhasil update'], 200);
    }
    public function exportPrisensiHarian(Request $request)
    {

      
        $dateRange = $request->tanggal_export;
        $dateParts = explode(" - ", $dateRange);
        $startDate = date('Y-m-d', strtotime($dateParts[0]));
        $endDate = date('Y-m-d', strtotime($dateParts[1]));

        
        $data = Presensi_harian::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
        ->join('kelas','presensi_harian.kelas_id','=','kelas.id')
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->where('presensi_harian.kelas_id', $request->kode_kelas)
        ->select('presensi_harian.*','siswa.nama', 'kelas.kodekelas')
        ->get(); // Gantilah YourModel dengan model yang sesuai

        return Excel::download(new E_Prisensi_harian($data), 'dataharian.xlsx');
    }

    public function viewTable(){
        return view('admin.harianview');
    }
}
