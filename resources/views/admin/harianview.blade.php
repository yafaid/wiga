@extends('admin.master')
@section('judul_halaman', 'View Data Kehadiran')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-header">
        <h1>Tabel Kehadiran Harian</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('presensi.harian') }}">Presensi Harian</a></div>
            <div class="breadcrumb-item">Tabel Kehadiran Harian</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4>Data Kehadiran</h4>
                <div>
                    {{-- <a href="#" id="tambahTP" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i>
                        Tambah
                        Data</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-tahun">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
    <script></script>
@endsection
