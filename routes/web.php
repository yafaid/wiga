<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;   
use App\Http\Controllers\C_absensi;
use App\Http\Controllers\C_siswa;
use App\Http\Controllers\C_gm;
use App\Http\Controllers\C_guru;
use App\Http\Controllers\C_jurusan;
use App\Http\Controllers\C_mapel;
use App\Http\Controllers\C_tahunpel;
use App\Http\Controllers\C_kelas;
use App\Http\Controllers\C_admin;
use App\Http\Controllers\C_Prisensi_harian;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        // Jika pengguna sudah login, arahkan ke halaman beranda atau dashboard
        return redirect('/admin'); // Ganti '/dashboard' dengan halaman yang sesuai
    } else {
        // Jika pengguna belum login, arahkan ke halaman login
        return redirect('/login'); // Ganti '/login' dengan halaman login Anda
    }
});

error_reporting(E_ALL);
ini_set('display_errors', 1);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['checkrole:1'])->group(function (){
    Route::get('/admin', [AdminController::class, 'index'])->name('dbadmin');
    Route::get('/adminacc', [C_admin::class, 'admin'])->name('admin');
    Route::get('/get-admin', [C_admin::class, 'getUser'])->name('get-admin');
    Route::post('/adminadd', [C_admin::class, 'addUser'])->name('admin.add');
    Route::delete('/admin/{id}', [C_admin::class, 'destroyUser'])->name('admin.delete');
    
    
    //KELAS
    Route::get('/kelas', [C_kelas::class, 'kelas'])->name('kelas');
    Route::get('/get-kelas', [C_kelas::class, 'getKelas'])->name('get-kelas');
    Route::get('/kelas/{id}', [C_kelas::class, 'showKel'])->name('kelas.show');
    Route::post('/kelas/{id}', [C_kelas::class, 'updateKel'])->name('kelas.edit');
    Route::delete('/kelas/{id}', [C_kelas::class, 'destroyKel'])->name('kelas.delete');
    Route::post('/kelasadd', [C_kelas::class, 'storeKel'])->name('kelas.add');


    // JURUSAN
    Route::get('/jurusan', [C_jurusan::class, 'jurusan'])->name('jurusan');
    Route::get('/get-jurusans', [C_jurusan::class, 'getJurusans'])->name('get-jurusans');
    Route::get('/jurusans/{id}', [C_jurusan::class, 'showJur'])->name('jurusans.show');
    Route::post('/jurusans/{id}', [C_jurusan::class, 'updateJur'])->name('jurusans.edit');
    Route::delete('/jurusans/{id}', [C_jurusan::class, 'destroyJur'])->name('jurusans.delete');
    Route::post('/jurusansadd', [C_jurusan::class, 'storeJur'])->name('jurusans.add');


    //MATA PELAJARAN
    Route::get('/mapel', [C_mapel::class, 'mapel'])->name('matapelajaran');
    Route::get('/get-mapel', [C_mapel::class, 'getMapel'])->name('get-mapel');
    Route::get('/mapel/{id}', [C_mapel::class, 'showMapel'])->name('mapel.show');
    Route::post('/mapel/{id}', [C_mapel::class, 'updateMapel'])->name('mapel.edit');
    Route::delete('/mapel/{id}', [C_mapel::class, 'destroyMapel'])->name('mapel.delete');
    Route::post('/mapeladd', [C_mapel::class, 'storeMapel'])->name('mapel.add'); 

    //TAHUN PELAJARAN
    Route::get('/thnpelajaran', [C_tahunpel::class, 'tahunpelajaran'])->name('tahunpelajaran');
    Route::get('/get-tahun', [C_tahunpel::class, 'getTP'])->name('get-tahun');
    Route::get('/tahuns/{id}', [C_tahunpel::class, 'showTP'])->name('tp.show');
    Route::post('/tahuns/{id}', [C_tahunpel::class, 'updateTP'])->name('tp.edit');
    Route::delete('/tahuns/{id}', [C_tahunpel::class, 'destroyTP'])->name('tp.delete');
    Route::post('/tahunsadd', [C_tahunpel::class, 'storeTP'])->name('tp.add'); 
    

    //PROFIL
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::post('/gantipw', [AdminController::class, 'changePassword'])->name('gantipw');
    Route::post('/gantiuname', [AdminController::class, 'changeUsername'])->name('gantiuname');

    //GURU
    Route::get('/guru', [C_guru::class, 'guru'])->name('guru');
    Route::get('/get-guru', [C_guru::class, 'getGuru'])->name('get-guru');
    Route::get('/guru/{id}', [C_guru::class, 'showGuru'])->name('guru.show');
    Route::post('/guru/{id}', [C_guru::class, 'updateGuru'])->name('guru.edit');
    Route::delete('/guru/{id}', [C_guru::class, 'destroyGuru'])->name('guru.delete');
    Route::post('/guruadd', [C_guru::class, 'storeGuru'])->name('guru.add'); 
    Route::get('/guruacc', [C_guru::class, 'guruacc'])->name('guruacc');
    Route::get('/get-guruacc', [C_guru::class, 'getUser'])->name('get-guruacc');

    //GURU MAPEL
    Route::get('/gm', [C_gm::class, 'gm'])->name('gm');
    Route::get('/get-gm', [C_gm::class, 'getGM'])->name('get-gm');
    Route::get('/gm/{id}', [C_gm::class, 'showGM'])->name('gm.show');
    Route::post('/gm/{id}', [C_gm::class, 'updateGM'])->name('gm.edit');
    Route::delete('/gm/{id}', [C_gm::class, 'destroyGM'])->name('gm.delete');
    Route::post('/gmadd', [C_gm::class, 'storeGM'])->name('gm.add'); 

    //SISWA
    Route::get('/siswa', [C_siswa::class, 'siswa'])->name('siswa');
    Route::get('/get-siswa', [C_siswa::class, 'getSiswa'])->name('get-siswa');
    Route::post('/siswaadd', [C_siswa::class, 'storeSiswa'])->name('siswa.add'); 
    Route::get('/siswa/{id}', [C_siswa::class, 'showSiswa'])->name('siswa.show');
    Route::post('/siswa/{id}', [C_siswa::class, 'updateSiswa'])->name('siswa.edit');
    Route::delete('/siswa/{id}', [C_siswa::class, 'destroySiswa'])->name('siswa.delete');
    Route::get('/siswaacc', [C_siswa::class, 'siswaacc'])->name('siswaacc');
    Route::get('/get-siswaacc', [C_siswa::class, 'getUser'])->name('get-siswaacc');

    //PRESENSI
    Route::get('/presensi', [AdminController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [AdminController::class, 'storePresensi'])->name('presensi.add'); 
    Route::Post('/presensi/get-kelas', [C_absensi::class, 'index'])->name('get.kelas'); 
    Route::Post('/presensi/get-mapel', [C_absensi::class, 'selectMapel'])->name('get.mapel'); 
    Route::Post('/presensi/simpan-prisensi', [C_absensi::class, 'simpanData'])->name('simpan.prisensi'); 
    
    Route::get('/presensi/harian', [C_Prisensi_harian::class, 'index'])->name('presensi.harian');
    Route::Post('/presensi/get-siswa', [C_Prisensi_harian::class, 'getSiswa'])->name('get.siswa'); 
    Route::Post('/presensi/simpan-prisensi-siswa', [C_Prisensi_harian::class, 'storeData'])->name('simpan.prisensi.siswa'); 
    
    
    
    Route::get('/presensi/harian/export', [C_Prisensi_harian::class, 'exportPrisensiHarian'])->name('export.prisensi.harian');
    Route::get('/presensi/mapel/export', [C_absensi::class, 'exportPrisensiMapel'])->name('export.prisensi.mapel');
});