@extends('admin.master')
@section('judul_halaman', 'Presensi Mapel')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-header">
        <h1>Presensi Mapel</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Absensi</div>
            <div class="breadcrumb-item">Pertemuan</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Presensi</h4>
                        </div>
                        <div class="card-body">
                            Masukkan Mapel,Tanggal dan Kelas
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-2 mb-2 d-flex flex-row-reverse">
                <a href="{{ route('absenmapel.view') }}" class="btn btn-icon icon-left btn-primary">
                    Lihat Data</a>
            </div>
        </div>
        <form method="POST" action="{{ route('presensi.add') }}" id="form_prisensi">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal">Tanggal:</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" onchange="changeTanggal()">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal">Kelas:</label>
                        <select class="form-control select2" name="kode_kelas" id="kode_kelas"
                            onchange="changeKelas(this.value)">
                            <option value="">-- Pilih Kode Kelas --</option>
                            @foreach ($kelas as $row)
                                <option value="{{ $row->id }}">{{ $row->kodekelas }} - {{ $row->jurusan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tanggal">Mapel:</label>
                        <select class="form-control select2 " name="kode_mapel" id="kode_mapel"
                            onchange="changeMapel(this.value)">
                            <option value="">-- Pilih Mapel --</option>
                        </select>
                    </div>
                </div>
            </div>


            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Hadir</th>
                        <th>Alpha</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function changeTanggal() {
            $('#kode_mapel').val('');
            $('#kode_kelas').val('').trigger('change.select2');
            $('#tbody').html('<td colspan="5" class="text-center">Tidak ada data</td>');
        };

        function changeKelas(val) {
            var tanggal = $('#tanggal').val();
            $.ajax({
                url: "{{ route('get.mapel') }}",
                method: 'POST',
                data: {
                    tanggal: tanggal,
                    kelas_id: val,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $('#kode_mapel').html(response.data);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!!',
                        text: errorMessage,
                        icon: 'error',
                        timer: 2000, // Menutup setelah 2 detik (2000 ms)
                        showConfirmButton: false // Menyembunyikan tombol OK
                    });
                }
            });
        }

        function changeMapel(val) {
            var tanggal = $('#tanggal').val();
            var kode_kelas = $('#kode_kelas').val();
            var kode_mapel = $('#kode_mapel').val();
            $.ajax({
                url: "{{ route('get.kelas') }}",
                method: 'POST',
                data: {
                    kode_mapel: kode_mapel,
                    tanggal: tanggal,
                    kelas_id: kode_kelas,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $('#tbody').html(response.data);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!!',
                        text: errorMessage,
                        icon: 'error',
                        timer: 2000, // Menutup setelah 2 detik (2000 ms)
                        showConfirmButton: false // Menyembunyikan tombol OK
                    });
                }
            });
        }
    </script>

    <script>
        function buttonPrisensi(id) {
            var mapel = $('#kode_mapel').val();
            var kode_kelas = $('#kode_kelas').val();
            var tanggal = $('#tanggal').val();

            if (mapel == '' || kode_kelas == '' || tanggal == '') {
                Swal.fire({
                    title: 'Error!!',
                    text: 'pastikan form sudah terisi semua',
                    icon: 'error',
                    timer: 2000, // Menutup setelah 2 detik (2000 ms)
                    showConfirmButton: false // Menyembunyikan tombol OK
                });
                return;
            }
            $.ajax({
                url: "{{ route('simpan.prisensi') }}",
                method: "POST",
                data: {
                    status: id,
                    mapel: mapel,
                    kode_kelas: kode_kelas,
                    tanggal: tanggal,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                },
                error: function(data) {
                    Swal.fire({
                        title: 'Error!!',
                        text: errorMessage,
                        icon: 'error',
                        timer: 2000, // Menutup setelah 2 detik (2000 ms)
                        showConfirmButton: false // Menyembunyikan tombol OK
                    });
                }
            });

        }
    </script>


@endsection
