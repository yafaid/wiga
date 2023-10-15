<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\E_Prisensi_harian;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Presensi_harian;
use Illuminate\Support\Facades\DB;

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

    public function showPrisensi(Request $request)
    {
        $id = $request->id;
        $dateRange = $request->dates;
        if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4}) - (\d{1,2}\/\d{1,2}\/\d{4})/', $dateRange, $matches)) {

            $startDate = date('Y-m-d', strtotime($matches[1]));
            $endDate = date('Y-m-d', strtotime($matches[2]));
        }

        $data = Presensi_harian::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
                ->where('presensi_harian.kelas_id', $id)
                ->whereBetween('presensi_harian.tanggal', [$startDate, $endDate])
                ->orderBy('presensi_harian.tanggal', 'ASC');
        $total= 0;
        $arrayData = [];


        foreach($data->get() as $key => $val)
        {
            $arrayData[$key]['id'] = $val->id;
            $arrayData[$key]['tanggal'] = $val->tanggal;
            $arrayData[$key]['keterangan'] = $val->keterangan;
            $arrayData[$key]['siswa_id'] = $val->siswa_id;
            $arrayData[$key]['kelas_id'] = $val->kelas_id;
            $arrayData[$key]['keterangan'] = $val->keterangan;
            $arrayData[$key]['nisn'] = $val->nisn;
            $arrayData[$key]['nama'] = $val->nama;
            $arrayData[$key]['jeniskelamin'] = $val->jeniskelamin;
            $dataCount = Presensi_harian::select('siswa_id')
                ->selectRaw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END) AS total_hadir')
                ->selectRaw('SUM(CASE WHEN keterangan = "izin" THEN 1 ELSE 0 END) AS total_izin')
                ->selectRaw('SUM(CASE WHEN keterangan = "sakit" THEN 1 ELSE 0 END) AS total_sakit')
                ->selectRaw('SUM(CASE WHEN keterangan = "alpha" THEN 1 ELSE 0 END) AS total_alpha')
                ->where('siswa_id', $val->siswa_id)
                ->groupBy('siswa_id')
                ->first();

                $arrayData[$key]['total_hadir'] = $dataCount->total_hadir;
                $arrayData[$key]['total_izin'] = $dataCount->total_izin;
                $arrayData[$key]['total_sakit'] = $dataCount->total_sakit;
                $arrayData[$key]['total_alpha'] = $dataCount->total_alpha;
            $total++;
        }



        

        $thead = '';
        $dataSiswa = '';
        $nisnProcessed = [];
        $nisnTanggal= [];
        $total_pertemuan = 0;
        foreach ($arrayData as $key => $val) {

            if (!in_array($val['tanggal'], $nisnTanggal)) {
                $thead .=   '<td class="pertemuan">'.$val['tanggal'].'</td>';
                $total_pertemuan++;
                $nisnTanggal[] = $val['tanggal'];
            }

        }
        foreach ($arrayData as $key => $val) {

            if (!in_array($val['nisn'], $nisnProcessed)) {

                $data2 = Presensi_harian::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
                ->where(['presensi_harian.kelas_id' => $id, 'nisn' => $val['nisn']])
                ->whereBetween('presensi_harian.tanggal', [$startDate, $endDate])
                ->orderBy('presensi_harian.tanggal', 'ASC');

                $dataKehadiran = trim('');
                foreach($data2->get() as $val2){
                    $dataKehadiran .= '<td>' . $val2->keterangan . '</td>';
                }

                $dataSiswa .= '<tr>
                    <td>' . $val['nisn'] . '</td>
                    <td>' . $val['nama'] . '</td>
                    <td>' . $val['jeniskelamin'] . '</td>
                    ' . $dataKehadiran . '
                    <td>' . $val['total_hadir'] . '</td>
                    <td>' . $val['total_izin'] . '</td>
                    <td>' . $val['total_sakit'] . '</td>
                    <td>' . $val['total_alpha'] . '</td>
                </tr>';
        
                $nisnTanggal[] = $val['tanggal'];
                $nisnProcessed[] = $val['nisn'];
            }

        }


        $tabel_data = '      <table class="table table-bordered" id="table-tahun">
        <thead>
            <tr>
                <td class="head" rowspan="2">NIS</td>
                <td class="head" rowspan="2">Nama Siswa</td>
                <td class="head" rowspan="2">L/P</td>
                <td class="head" colspan="'.$total_pertemuan.'" class="text-center">Pertemuan</td>
                <td class="head" colspan="5" class="text-center">Jumlah</td>
            </tr>
            <tr>
            '.$thead.'
                <td class="h">H</td>
                <td class="head">S</td>
                <td class="i">I</td>
                <td class="a">A</td>
            </tr>
        </thead>
        <tbody>
        '.$dataSiswa.'
        </tbody>
    </table>';

    return response()->json(['message' => 'Jurusan berhasil diedit', 'data' => $tabel_data]);
    }

    public function viewTable(){
        $kelas = Kelas::get();
        return view('admin.harianview',['kelas' => $kelas]);
    }
}
