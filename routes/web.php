<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;   
use App\Http\Controllers\JurusanController;   
use App\Http\Controllers\C_absensi;
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
    
    
    //KELAS
    Route::get('/kelas', [AdminController::class, 'kelas'])->name('kelas');
    Route::get('/get-kelas', [AdminController::class, 'getKelas'])->name('get-kelas');
    Route::get('/kelas/{id}', [AdminController::class, 'showKel'])->name('kelas.show');
    Route::post('/kelas/{id}', [AdminController::class, 'updateKel'])->name('kelas.edit');
    Route::delete('/kelas/{id}', [AdminController::class, 'destroyKel'])->name('kelas.delete');
    Route::post('/kelas', [AdminController::class, 'storeKel'])->name('kelas.add');


    // JURUSAN
    Route::get('/jurusan', [AdminController::class, 'jurusan'])->name('jurusan');
    Route::get('/get-jurusans', [AdminController::class, 'getJurusans'])->name('get-jurusans');
    Route::get('/jurusans/{id}', [AdminController::class, 'showJur'])->name('jurusans.show');
    Route::post('/jurusans/{id}', [AdminController::class, 'updateJur'])->name('jurusans.edit');
    Route::delete('/jurusans/{id}', [AdminController::class, 'destroyJur'])->name('jurusans.delete');
    Route::post('/jurusans', [AdminController::class, 'storeJur'])->name('jurusans.add');


    //MATA PELAJARAN
    Route::get('/mapel', [AdminController::class, 'mapel'])->name('matapelajaran');
    Route::get('/get-mapel', [AdminController::class, 'getMapel'])->name('get-mapel');
    Route::get('/mapel/{id}', [AdminController::class, 'showMapel'])->name('mapel.show');
    Route::post('/mapel/{id}', [AdminController::class, 'updateMapel'])->name('mapel.edit');
    Route::delete('/mapel/{id}', [AdminController::class, 'destroyMapel'])->name('mapel.delete');
    Route::post('/mapel', [AdminController::class, 'storeMapel'])->name('mapel.add'); 

    //TAHUN PELAJARAN
    Route::get('/thnpelajaran', [AdminController::class, 'tahunpelajaran'])->name('tahunpelajaran');
    Route::get('/get-tahun', [AdminController::class, 'getTP'])->name('get-tahun');
    Route::get('/tahuns/{id}', [AdminController::class, 'showTP'])->name('tp.show');
    Route::post('/tahuns/{id}', [AdminController::class, 'updateTP'])->name('tp.edit');
    Route::delete('/tahuns/{id}', [AdminController::class, 'destroyTP'])->name('tp.delete');
    Route::post('/tahuns', [AdminController::class, 'storeTP'])->name('tp.add'); 
    

    //PROFIL
    Route::get('/profil', [AdminController::class, 'profil'])->name('profil');
    Route::post('/gantipw', [AdminController::class, 'changePassword'])->name('gantipw');
    Route::post('/gantiuname', [AdminController::class, 'changeUsername'])->name('gantiuname');

    //GURU
    Route::get('/guru', [AdminController::class, 'guru'])->name('guru');
    Route::get('/get-guru', [AdminController::class, 'getGuru'])->name('get-guru');
    Route::get('/guru/{id}', [AdminController::class, 'showGuru'])->name('guru.show');
    Route::post('/guru/{id}', [AdminController::class, 'updateGuru'])->name('guru.edit');
    Route::delete('/guru/{id}', [AdminController::class, 'destroyGuru'])->name('guru.delete');
    Route::post('/guru', [AdminController::class, 'storeGuru'])->name('guru.add'); 

    //GURU MAPEL
    Route::get('/gm', [AdminController::class, 'gm'])->name('gm');
    Route::get('/get-gm', [AdminController::class, 'getGM'])->name('get-gm');
    Route::get('/gm/{id}', [AdminController::class, 'showGM'])->name('gm.show');
    Route::post('/gm/{id}', [AdminController::class, 'updateGM'])->name('gm.edit');
    Route::delete('/gm/{id}', [AdminController::class, 'destroyGM'])->name('gm.delete');
    Route::post('/gm', [AdminController::class, 'storeGM'])->name('gm.add'); 

    //SISWA
    Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
    Route::get('/get-siswa', [AdminController::class, 'getSiswa'])->name('get-siswa');
    Route::post('/siswa', [AdminController::class, 'storeSiswa'])->name('siswa.add'); 
    Route::get('/siswa/{id}', [AdminController::class, 'showSiswa'])->name('siswa.show');
    Route::post('/siswa/{id}', [AdminController::class, 'updateSiswa'])->name('siswa.edit');
    Route::delete('/siswa/{id}', [AdminController::class, 'destroySiswa'])->name('siswa.delete');

    //PRESENSI
    Route::get('/presensi', [AdminController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [AdminController::class, 'storePresensi'])->name('presensi.add'); 
    Route::Post('/presensi/get-kelas', [C_absensi::class, 'index'])->name('get.kelas'); 
    Route::Post('/presensi/simpan-prisensi', [C_absensi::class, 'simpanData'])->name('simpan.prisensi'); 

});