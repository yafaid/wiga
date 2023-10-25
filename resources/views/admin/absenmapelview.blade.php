@extends('admin.master')
@section('judul_halaman', 'Data Kehadiran Mapel')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('konten')
    <style>
        .pertemuan {
            background-color: gray;
            color: white;
            text-align: center;

        }

        .head {
            background-color: #4287f5;
            color: white;
            text-align: center;
        }

        .h {
            background-color: #73e693;
            color: white;
            text-align: center;
        }

        .i {
            background-color: #b681e6;
            color: white;
            text-align: center;
        }

        .a {
            background-color: #e6634c;
            color: white;
            text-align: center;
        }

        .t {
            background-color: #e3d449;
            color: white;
            text-align: center;
        }
    </style>
    <div class="section-header">
        <h1>Tabel Kehadiran Mapel</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('presensi.harian') }}">Presensi Harian</a></div>
            <div class="breadcrumb-item">Tabel Kehadiran Mapel</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <div>
                    <h4>Data Kehadiran</h4>
                </div>

            </div>
            <div class="card-body">
                <div class="d-flex justify-content-start mb-2 mt-2">
                    <div>
                        <select name="kelas" class="form-control select2" id="kelas" onchange="kelasChange()">
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach ($kelas as $row)
                                <option value="{{ $row->id }}">{{ $row->kodekelas }} - {{ $row->jurusan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="mapel" class="form-control select2" id="mapel" onchange="kelasChange()">
                            <option value="" disabled selected>Pilih Mapel</option>
                            @foreach ($mapel as $row)
                                <option value="{{ $row->id }}">{{ $row->kodemapel }} - {{ $row->mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <input type="text" class="form-control mx-3" name="dates" id="dates"
                            onchange="kelasChange()">
                    </div>
                </div>
                <div class="table-responsive " id="tabel_id">
                    <table class="table table-bordered" id="table-tahun">
                        <thead>
                            <tr>
                                <td class="head" rowspan="2">NIS</td>
                                <td class="head" rowspan="2">Nama Siswa</td>
                                <td class="head" rowspan="2">L/P</td>
                                <td class="head" colspan="4" class="text-center">Pertemuan</td>
                                <td class="head" colspan="5" class="text-center">Jumlah</td>
                            </tr>
                            <tr>
                                <td class="pertemuan">1</td>
                                <td class="pertemuan">2</td>
                                <td class="pertemuan">3</td>
                                <td class="pertemuan">4</td>
                                <td class="h">H</td>
                                <td class="head">S</td>
                                <td class="i">I</td>
                                <td class="a">A</td>
                                <!-- <td class="t">T</td> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="12" class="text-center">data tidak ada</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="card-footer">
                <h6>Tabel tersebut dapat berubah sewaktu waktu</h6>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('input[name="dates"]').daterangepicker();
        });

        function kelasChange() {
            var dates = $('#dates').val();
            var val = $('#kelas').val();
            var mapel = $('#mapel').val();

            if (dates == '') {
                Swal.fire({
                    title: 'Error!!',
                    text: 'Tanggal tidak boleh kosong',
                    icon: 'error',
                    timer: 2000, // Menutup setelah 2 detik (2000 ms)
                    showConfirmButton: false // Menyembunyikan tombol OK
                });
            }
            $.ajax({
                url: "{{ route('absenmapel.show') }}",
                method: 'POST',
                data: {
                    id: val,
                    dates: dates,
                    mapel: mapel,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $('#tabel_id').html(response.data);
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
@endsection
