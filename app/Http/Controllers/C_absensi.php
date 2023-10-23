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
    public function showPrisensi(Request $request)
    {
        $id = $request->id;
        $dateRange = $request->dates;
        if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4}) - (\d{1,2}\/\d{1,2}\/\d{4})/', $dateRange, $matches)) {

            $startDate = date('Y-m-d', strtotime($matches[1]));
            $endDate = date('Y-m-d', strtotime($matches[2]));
        }

        $data = Presensi::join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
                ->where('absensi.kelas_id', $id)
                ->whereBetween('absensi.tanggal', [$startDate, $endDate])
                ->orderBy('absensi.tanggal', 'ASC');
        $total= 0;
        $arrayData = [];


        foreach($data->get() as $key => $val)
        {
            $arrayData[$key]['id'] = $val->id;
            $arrayData[$key]['tanggal'] = $val->tanggal;
            $arrayData[$key]['keterangan'] = $val->keterangan;
            $arrayData[$key]['siswa_id'] = $val->siswa_id;
            $arrayData[$key]['mapel_id'] = $val->mapel_id;
            $arrayData[$key]['kelas_id'] = $val->kelas_id;
            $arrayData[$key]['keterangan'] = $val->keterangan;
            $arrayData[$key]['nisn'] = $val->nisn;
            $arrayData[$key]['nama'] = $val->nama;
            $arrayData[$key]['jeniskelamin'] = $val->jeniskelamin;
            $dataCount = Presensi::select('siswa_id')
            ->selectRaw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END) AS total_hadir')
            ->selectRaw('SUM(CASE WHEN keterangan = "izin" THEN 1 ELSE 0 END) AS total_izin')
            ->selectRaw('SUM(CASE WHEN keterangan = "sakit" THEN 1 ELSE 0 END) AS total_sakit')
            ->selectRaw('SUM(CASE WHEN keterangan = "alpha" THEN 1 ELSE 0 END) AS total_alpha')
            ->selectRaw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END +
                             CASE WHEN keterangan = "izin" THEN 1 ELSE 0 END +
                             CASE WHEN keterangan = "sakit" THEN 1 ELSE 0 END +
                             CASE WHEN keterangan = "alpha" THEN 1 ELSE 0 END) AS total')
            ->where('siswa_id', $val->siswa_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('siswa_id')
            ->first();

                $arrayData[$key]['total_hadir'] = $dataCount->total_hadir;
                $arrayData[$key]['total_izin'] = $dataCount->total_izin;
                $arrayData[$key]['total_sakit'] = $dataCount->total_sakit;
                $arrayData[$key]['total_alpha'] = $dataCount->total_alpha;
                $arrayData[$key]['total'] = $dataCount->total;
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

                $data2 = Presensi::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
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
                    <td>' . $val['total'] . '</td>
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
                <td class="t">T</td>
            </tr>
        </thead>
        <tbody>
        '.$dataSiswa.'
        </tbody>
    </table>';

    return response()->json(['message' => 'Jurusan berhasil diedit', 'data' => $tabel_data]);
    }
}
