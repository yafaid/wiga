@extends('admin.master')
@section('judul_halaman', 'Pertemuan')
@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
<div class="section-header">
    <h1>Presensi</h1>
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
                        <h4>Presensi Harian</h4>
                    </div>
                    <div class="card-body">
                        Masukkan Tanggal dan Kelas
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-2 mb-2 d-flex flex-row-reverse">
            <button class="btn btn-primary" id="exportButton">Export Prisensi Harian</button>
        </div>
    </div>
    <form method="POST" action="{{ route('presensi.add') }}" id="form_prisensi">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal">Kelas:</label>
                    <select class="form-control select2" name="kode_kelas" id="kode_kelas" onchange="changeKelas(this.value)">
                        <option value="">-- Pilih Kode Kelas --</option>
                        @foreach ($kelas as $row)
                        <option value="{{$row->id}}">{{$row->kodekelas}} - {{ $row->jurusan->nama }}</option>
                        @endforeach
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
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('input[name="tanggal_export"]').daterangepicker();
    });

    $('#tanggal').on('change', function() {
        $('#kode_kelas').val('').trigger('change.select2');
    });

    function changeKelas(val) {
        var tanggal = $('#tanggal').val();

        if (tanggal == '') {
            Swal.fire({
                title: 'Error!!',
                text: 'Tanggal tidak boleh kosong',
                icon: 'error',
                timer: 2000, // Menutup setelah 2 detik (2000 ms)
                showConfirmButton: false // Menyembunyikan tombol OK
            });
            $('#kode_kelas').val('').trigger('change.select2'); // Change the value or make some change to the internal state
            return;
        }
        $.ajax({
            url: "{{ route('get.siswa') }}",
            method: 'POST',
            beforeSend: function() {
                $('#loading').show();
            },
            data: {
                tanggal: tanggal,
                kelas_id: val,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                $('#tbody').html(response.data);
                $('#loading').hide();
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

    function buttonPrisensi(id) {
        var kode_kelas = $('#kode_kelas').val();
        var tanggal = $('#tanggal').val();

        console.log(kode_kelas);

        if (kode_kelas == '' || tanggal == '') {
            Swal.fire({
                title: 'Error!!',
                text: 'Pastikan form sudah terisi semua',
                icon: 'error',
                timer: 2000, // Menutup setelah 2 detik (2000 ms)
                showConfirmButton: false // Menyembunyikan tombol OK
            });
            return;
        }
        $.ajax({
            url: "{{ route('simpan.prisensi.siswa') }}",
            method: "POST",
            data: {
                status: id,
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

    $('#exportButton').on('click', function(e) {
        e.preventDefault();
        $('#modalExport').modal('show');
    });
</script>




@endsection
<div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="modalExportLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportLabel">Export Prisensi Harian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('export.prisensi.harian') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <label for="tanggal_export">Tanggal Range</label>
                        <input type="text" class="form-control" id="tanggal_export" name="tanggal_export">
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Kelas:</label>
                        <select class="form-control select2" name="kode_kelas2" id="kode_kelas2">
                            <option value="">-- Pilih Kode Kelas --</option>
                            @foreach ($kelas as $row)
                            <option value="{{$row->id}}">{{$row->kodekelas}} - {{ $row->jurusan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="saveEdit">Export Excel</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>