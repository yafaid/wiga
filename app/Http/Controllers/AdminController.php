<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\ThnPel;
use App\Models\Mapel;
use App\Models\Guru;    
use App\Models\GuruMapel;
use App\Models\Siswa;
use App\Models\Presensi;

class AdminController extends Controller
{
    // Menampilkan halaman admin
    public function index()
    {
        return view('admin.dashboard');
    }

    



    // PROFIL
    public function profil()
    {
        return view('admin.profil');
    }
    public function changePassword(Request $request)
    {
    $user = auth()->user();
    $currentPassword = $request->input('currentPassword');
    $newPassword = $request->input('newPassword');
    if (!Hash::check($currentPassword, $user->password)) {
        throw ValidationException::withMessages([
            'currentPassword' => 'Password saat ini salah.'
        ]);
    }
    $user->password = Hash::make($newPassword);
    $user->save();
    return response()->json(['message' => 'Password berhasil diubah.']);
    }
    public function changeUsername(Request $request)
    {
        $user = Auth::user();
        $currentUsername = $request->input('currentUsername');
        $newUsername = $request->input('newUsername');

        if ($currentUsername !== $user->username) {
            throw ValidationException::withMessages([
                'currentUsername' => 'Username saat ini salah.'
            ]);
        }

        $user->username = $newUsername;
        $user->save();

        return response()->json(['message' => 'Username berhasil diubah.']);
    }


    //SISWA
    public function siswa()
    {
        $jurusans = Jurusan::all();
        $kelas = kelas::all();
        return view('admin.datasiswa',compact('jurusans','kelas'));
    }
    public function getSiswa(){
        $siswa = Siswa::all();
        $siswa = Siswa::with('kelas','jurusan')->where('is_active', "1")->get();        
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

    //KELAS
    public function kelas()
    {        
        $jurusans = Jurusan::all();
        return view('admin.kelas',compact('jurusans'));
    }

    public function getKelas(){
        $kelas = Kelas::all();
        $kelas = Kelas::with('jurusan')->get();
        return response()->json($kelas);
    }
    public function showKel($id)
    {
        $kelas = Kelas::find($id);
        return response()->json($kelas);
    }
    public function storeKel(Request $request)
    {
        // Validasi data $request disini
        $kelas = new Kelas;
        $kelas->kodekelas = $request->input('kodekelas');
        $kelas->deskripsi = $request->input('deskripsi');
        $kelas->ruangan = $request->input('ruangan');
        $kelas->jurusan_id = $request->input('jurusan_id');
        $kelas->save();

        return response()->json(['message' => 'Jurusan berhasil ditambahkan']);
    }
    public function updateKel(Request $request, $id)
    {
        // Validasi data $request disini
        $kelas = Kelas::find($id);
        $kelas->kodekelas = $request->input('kodekelas');
        $kelas->deskripsi = $request->input('deskripsi');
        $kelas->ruangan = $request->input('ruangan');
        $kelas->jurusan_id = $request->input('jurusan_id');
        $kelas->save();   

        return response()->json(['message' => 'Jurusan berhasil diedit']);
    }
    public function destroyKel($id)
    {
        $kelas = Kelas::find($id);
        $kelas->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus']);
    }



    //JURUSAN
    public function jurusan()
    {
        $jurusans = Jurusan::paginate(10);
        $jurusans = Jurusan::all();
        return view('admin.jurusan',compact('jurusans'));
        
    }
    public function getJurusans()
    {
        $jurusans = Jurusan::all();
        return response()->json($jurusans);
    }
    public function showJur($id)
    {
        $jurusan = Jurusan::find($id);
        return response()->json($jurusan);
    }
    public function storeJur(Request $request)
    {
        // Validasi data $request disini
        $jurusan = new Jurusan;
        $jurusan->kodejur = $request->input('kodejur');
        $jurusan->nama = $request->input('nama');
        $jurusan->save();

        return response()->json(['message' => 'Jurusan berhasil ditambahkan']);
    }
    public function updateJur(Request $request, $id)
    {
        // Validasi data $request disini
        $jurusan = Jurusan::find($id);
        $jurusan->kodejur = $request->input('kodejur');
        $jurusan->nama = $request->input('nama');
        $jurusan->save();   

        return response()->json(['message' => 'Jurusan berhasil diedit']);
    }
    public function destroyJur($id)
    {
        $jurusan = Jurusan::find($id);
        $jurusan->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus']);
    }

    


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

    //PRESENSI
    public function presensi()
    {     
        $siswa = Siswa::all();
        $kelas = kelas::all();
        $mapel = Mapel::all();
        
        return view('admin.presensi',compact('siswa','kelas','mapel'));
    }
    public function storePresensi(Request $request)
    {        
        // Validasi data yang dikirimkan melalui formulir
        $validator = Validator::make($request->all(), [
            'siswa_id.*' => 'required|numeric',
            'mapel_id.*' => 'required|numeric',
            'kelas_id.*' => 'required|numeric',
            'tanggal.*' => 'required|date',
            'keterangan.*' => 'required|string',
        ]);

        // Jika validasi gagal, kembalikan dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Iterasi melalui data yang dikirim dari formulir dan menyimpannya ke dalam database
        foreach ($request->siswa_id as $key => $siswaId) {
            Presensi::create([
                'siswa_id' => $siswaId,
                'mapel_id' => $request->mapel_id[$key],
                'kelas_id' => $request->kelas_id[$key],
                'tanggal' => $request->tanggal[$key],
                'keterangan' => $request->keterangan[$key],
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data absensi berhasil disimpan.');
    }
}
