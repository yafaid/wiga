<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\ThnPel;
use App\Models\Mapel;
use App\Models\Guru;
use App\Models\Presensi_harian;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Presensi;

class C_siswa extends Controller
{
    //SISWA
    public function siswa()
    {
        $jurusans = Jurusan::all();
        $kelas = Kelas::all();
        return view('admin.datasiswa', compact('jurusans', 'kelas'));
    }
    public function getSiswa()
    {
        $siswa = Siswa::all();
        $siswa = Siswa::with('kelas', 'jurusan')->where('is_active', "1")->get();
        return response()->json($siswa);
    }
    public function storeSiswa(Request $request)
    {
        // Validasi data $request disini
        $siswa = new Siswa;
        $siswa->nisn = $request->input('nisn');
        $siswa->nama = $request->input('nama');
        $siswa->is_active = $request->input('is_active');
        $siswa->jeniskelamin = $request->input('jeniskelamin');
        $siswa->kelas_id = $request->input('kelas_id');
        $siswa->jurusan_id = $request->input('jurusan_id');
        $siswa->save();

        return response()->json(['message' => 'Siswa berhasil ditambahkan']);
    }
    public function showSiswa($id)
    {
        $siswa = Siswa::find($id);
        return response()->json($siswa);
    }
    public function updateSiswa(Request $request, $id)
    {
        // Validasi data $request disini
        $siswa = Siswa::find($id);
        $siswa->nisn = $request->input('nisn');
        $siswa->nama = $request->input('nama');
        $siswa->is_active = $request->input('is_active');
        $siswa->jeniskelamin = $request->input('jeniskelamin');
        $siswa->kelas_id = $request->input('kelas_id');
        $siswa->jurusan_id = $request->input('jurusan_id');
        $siswa->save();

        return response()->json(['message' => 'Siswa berhasil diedit']);
    }
    public function destroySiswa($id)
    {
        $siswa = Siswa::find($id);
        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }

    public function siswaacc()
    {
        $siswa = Siswa::all();
        $jurusans = Jurusan::all();
        $kelas = Kelas::all();
        return view('admin.siswaacc', compact('jurusans', 'kelas', 'siswa'));
    }
    public function getUser()
    {
        $users = User::all();
        $users = User::where('role_id', 3)->get();
        return response()->json($users);
    }
    public function dbsiswa()
    {
        $kelas = Kelas::all();
        $guru = Guru::all();
        return view('siswa.dashboard', compact('guru', 'kelas'));
    }
    public function profil()
    {
        return view('siswa.profil');
    }
    // public function showPrisensi(Request $request)
    // {
    //     $id = $request->id;
    //     $username = $request->username;
    //     $dateRange = $request->dates;
    //     if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4}) - (\d{1,2}\/\d{1,2}\/\d{4})/', $dateRange, $matches)) {

    //         $startDate = date('Y-m-d', strtotime($matches[1]));
    //         $endDate = date('Y-m-d', strtotime($matches[2]));
    //     }

    //     $data = Presensi_harian::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
    //         ->where('presensi_harian.siswa_id', $username)
    //         ->whereBetween('presensi_harian.tanggal', [$startDate, $endDate])
    //         ->orderBy('presensi_harian.tanggal', 'ASC');
    //     $total = 0;
    //     $arrayData = [];


    //     foreach ($data->get() as $key => $val) {
    //         $arrayData[$key]['id'] = $val->id;
    //         $arrayData[$key]['tanggal'] = $val->tanggal;
    //         $arrayData[$key]['keterangan'] = $val->keterangan;
    //         $arrayData[$key]['siswa_id'] = $val->siswa_id;
    //         $arrayData[$key]['kelas_id'] = $val->kelas_id;
    //         $arrayData[$key]['keterangan'] = $val->keterangan;
    //         $arrayData[$key]['nisn'] = $val->nisn;
    //         $arrayData[$key]['nama'] = $val->nama;
    //         $arrayData[$key]['jeniskelamin'] = $val->jeniskelamin;
    //         $dataCount = Presensi_harian::select('siswa_id')
    //             ->selectRaw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END) AS total_hadir')
    //             ->selectRaw('SUM(CASE WHEN keterangan = "izin" THEN 1 ELSE 0 END) AS total_izin')
    //             ->selectRaw('SUM(CASE WHEN keterangan = "sakit" THEN 1 ELSE 0 END) AS total_sakit')
    //             ->selectRaw('SUM(CASE WHEN keterangan = "alpha" THEN 1 ELSE 0 END) AS total_alpha')
    //             ->selectRaw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END +
    //                          CASE WHEN keterangan = "izin" THEN 1 ELSE 0 END +
    //                          CASE WHEN keterangan = "sakit" THEN 1 ELSE 0 END +
    //                          CASE WHEN keterangan = "alpha" THEN 1 ELSE 0 END) AS total')
    //             ->where('siswa_id', $val->siswa_id)
    //             ->whereBetween('tanggal', [$startDate, $endDate])
    //             ->groupBy('siswa_id')
    //             ->first();

    //         $arrayData[$key]['total_hadir'] = $dataCount->total_hadir;
    //         $arrayData[$key]['total_izin'] = $dataCount->total_izin;
    //         $arrayData[$key]['total_sakit'] = $dataCount->total_sakit;
    //         $arrayData[$key]['total_alpha'] = $dataCount->total_alpha;
    //         $arrayData[$key]['total'] = $dataCount->total;
    //         $total++;
    //     }
    //     $thead = '';
    //     $dataSiswa = '';
    //     $nisnProcessed = [];
    //     $nisnTanggal = [];
    //     $total_pertemuan = 0;
    //     foreach ($arrayData as $key => $val) {

    //         if (!in_array($val['tanggal'], $nisnTanggal)) {
    //             $thead .=   '<td class="pertemuan">' . $val['tanggal'] . '</td>';
    //             $total_pertemuan++;
    //             $nisnTanggal[] = $val['tanggal'];
    //         }
    //     }
    //     foreach ($arrayData as $key => $val) {

    //         if (!in_array($val['nisn'], $nisnProcessed)) {

    //             $data2 = Presensi_harian::join('siswa', 'presensi_harian.siswa_id', '=', 'siswa.id')
    //                 ->where(['presensi_harian.siswa_id' => $username, 'nisn' => $val['nisn']])
    //                 ->whereBetween('presensi_harian.tanggal', [$startDate, $endDate])
    //                 ->orderBy('presensi_harian.tanggal', 'ASC');

    //             $dataKehadiran = trim('');
    //             foreach ($data2->get() as $val2) {
    //                 $dataKehadiran .= '<td>' . $val2->keterangan . '</td>';
    //             }

    //             $dataSiswa .= '<tr>
    //                 <td>' . $val['nisn'] . '</td>
    //                 <td>' . $val['nama'] . '</td>
    //                 <td>' . $val['jeniskelamin'] . '</td>
    //                 ' . $dataKehadiran . '
    //                 <td>' . $val['total_hadir'] . '</td>
    //                 <td>' . $val['total_izin'] . '</td>
    //                 <td>' . $val['total_sakit'] . '</td>
    //                 <td>' . $val['total_alpha'] . '</td>
    //                 <td>' . $val['total'] . '</td>
    //             </tr>';

    //             $nisnTanggal[] = $val['tanggal'];
    //             $nisnProcessed[] = $val['nisn'];
    //         }
    //     }


    //     $tabel_data = '      <table class="table table-bordered" id="table-tahun">
    //     <thead>
    //         <tr>
    //             <td class="head" rowspan="2">NIS</td>
    //             <td class="head" rowspan="2">Nama Siswa</td>
    //             <td class="head" rowspan="2">L/P</td>
    //             <td class="head" colspan="' . $total_pertemuan . '" class="text-center">Pertemuan</td>
    //             <td class="head" colspan="5" class="text-center">Jumlah</td>
    //         </tr>
    //         <tr>
    //         ' . $thead . '
    //             <td class="h">H</td>
    //             <td class="head">S</td>
    //             <td class="i">I</td>
    //             <td class="a">A</td>
    //             <td class="t">T</td>
    //         </tr>
    //     </thead>
    //     <tbody>
    //     ' . $dataSiswa . '
    //     </tbody>
    // </table>';

    //     return response()->json(['message' => 'Jurusan berhasil diedit', 'data' => $tabel_data]);
    // }
}
