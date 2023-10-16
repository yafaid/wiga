@extends('admin.master')
@section('judul_halaman', 'Siswa')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')

    <div class="section-header">
        <h1>Data Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Siswa</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4>Data Siswa</h4>
                <div>
                    <a href="#" id="tambahButton" class="btn btn-icon icon-left btn-primary"><i
                            class="fas fa-plus"></i>
                        Tambah Data
                    </a>
                    {{-- <a href="#" class="btn btn-icon icon-left btn-dark"><i class="far fa-file"></i> Print</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-siswa">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>JK</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var table = $('#table-siswa').DataTable({
                ajax: {
                    url: "{{ route('get-siswa') }}",
                    method: "GET",
                    responsive: true,
                    theme: "santafe",
                    dataSrc: ""
                },
                columns: [{
                        data: 'nisn'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'jeniskelamin'
                    },
                    {
                        data: 'kelas.kodekelas'
                    },
                    {
                        data: 'jurusan.kodejur'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-primary edit" data-id="' + data.id +
                                '">Edit</button>' +
                                '<button class="btn btn-danger delete" data-id="' + data.id +
                                '">Delete</button>';
                        }
                    }
                ]
            });

            $('#tambahButton').on('click', function() {
                // Menampilkan modal tambah
                $('#add').modal('show');

                $('#addForm')[0].reset();
            });

            $('#simpanTambah').on('click', function() {
                var nisn = $('#tambahNisn').val();
                var nama = $('#tambahNama').val();
                var is_active = $('#is_active').val();
                var jk = $('#tambahJK').val();
                var kelas = $('#tambahKelas').val();
                var jurusan = $('#tambahKodeJur').val();

                // Mengirim permintaan AJAX untuk menyimpan data Kelas baru
                $.ajax({
                    url: "{{ route('siswa.add') }}",
                    method: 'POST',
                    data: {
                        nisn: nisn,
                        nama: nama,
                        is_active: is_active,
                        jeniskelamin: jk,
                        kelas_id: kelas,
                        jurusan_id: jurusan,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.error) {
                            // Jika kombinasi sudah ada, tampilkan pesan error
                            Swal.fire({
                                title: 'Error!!',
                                text: response.message,
                                icon: 'error',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            // Jika berhasil tambahkan, tampilkan pesan berhasil
                            Swal.fire({
                                title: 'Berhasil!!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Memuat ulang tabel
                            table.ajax.reload();

                            // Menutup modal tambah
                            $('#add').modal('hide');
                        }
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
            });

            $('#table-siswa').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('siswa.show', '') }}" + "/" + id,
                    method: 'GET',
                    success: function(response) {
                        // Isi form modal edit dengan data yang diperoleh
                        $('#editId').val(response.id); 
                        $('#editNisn').val(response.nisn);                                                                
                        $('#editNama').val(response.nama);
                        $('#editJK').val(response.jeniskelamin);
                        $('#editStatus').val(response.is_active);
                        $('#editKelas').val(response.kelas_id);
                        $('#editKodeJur').val(response.jurusan_id);

                        // Tampilkan modal edit
                        $('#edit').modal('show');
                    }
                });
            });

            $('#saveEdit').click(function() {
                var id = $('#editId').val();
                var nisn = $('#editNisn').val();
                var nama = $('#editNama').val();
                var is_active = $('#editStatus').val();
                var jk = $('#editJK').val();
                var kelas = $('#editKelas').val();
                var jurusan = $('#editKodeJur').val();


                // Prepare the data to be sent
                var data = {
                        nisn: nisn,
                        nama: nama,
                        is_active: is_active,
                        jeniskelamin: jk,
                        kelas_id: kelas,
                        jurusan_id: jurusan
                };
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Send an AJAX request to update the Kelas
                $.ajax({
                    url: "{{ route('siswa.edit', '') }}" + "/" + id,
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.error) {
                            Swal.fire({
                                title: 'Error!!',
                                text: response.message,
                                icon: 'error',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Berhasil!!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000, // Menutup setelah 2 detik (2000 ms)
                                showConfirmButton: false // Menyembunyikan tombol OK
                            });

                        }
                        // Close the modal
                        $('#edit').modal('hide');

                        // // Show a success toast

                        // // Refresh the DataTable
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON.message;

                        // Show an error toast
                        Swal.fire({
                            title: 'Error!!',
                            text: errorMessage,
                            icon: 'error',
                            timer: 2000, // Menutup setelah 2 detik (2000 ms)
                            showConfirmButton: false // Menyembunyikan tombol OK
                        });
                    }
                });
            });

            $('#table-siswa').on('click', '.delete', function() {
                var id = $(this).data('id');

                // Use SweetAlert2 for confirmation
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send an AJAX request to delete the kelas
                        $.ajax({
                            url: "{{ route('siswa.delete', '') }}" + "/" + id,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Display a success toast
                                Swal.fire({
                                    title: 'Berhasil!!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000, // Menutup setelah 2 detik (2000 ms)
                                    showConfirmButton: false // Menyembunyikan tombol OK
                                });

                                // Refresh the table
                                table.ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                // Display an error toast
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
                });
            });


        });
    </script>
@endsection
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Data Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="form-group">
                        <label for="tambah">NISN</label>
                        <input type="text" class="form-control" id="tambahNisn" name="nisn">
                    </div>
                    <div class="form-group">
                        <label for="tambah">Nama</label>
                        <input type="text" class="form-control" id="tambahNama" name="nama">
                    </div>
                    <input type="text" class="form-control" id="is_active" name="is_active" value='1' hidden>
                    <div class="form-group">
                        <label for="tambahKode">Jenis Kelamin </label>
                        <select class="form-control" id="tambahJK" name="jk">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambahKode">Kelas </label>
                        <select class="form-control" id="tambahKelas" name="kelas">
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach ($kelas as $row)
                                <option value="{{ $row->id }}">{{ $row->kodekelas }}{{ $row->jurusan->kodejur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambahKode">Kode Jurusan </label>
                        <select class="form-control" id="tambahKodeJur" name="jurusan">
                            <option value="" disabled selected>Pilih Kode Jurusan</option>
                            @foreach ($jurusans as $row)
                                <option value="{{ $row->id }}">{{ $row->kodejur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="simpanTambah">Simpan</button>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambahTPForm">
                    @csrf
                    <div class="form-group">
                        <label for="editId">ID</label>
                        <input type="text" class="form-control" id="editId" name="id" disabled>
                    </div>
                    <div class="form-group">
                        <label for="edit">NISN</label>
                        <input type="text" class="form-control" id="editNisn" name="nisn">
                    </div>
                    <div class="form-group">
                        <label for="tambah">Nama</label>
                        <input type="text" class="form-control" id="editNama" name="nama">
                    </div>
                    <input type="text" class="form-control" id="is_active" name="is_active" value='1'
                        hidden>
                    <div class="form-group">
                        <label for="editKode">Jenis Kelamin </label>
                        <select class="form-control" id="editJK" name="jk">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editKode">Status </label>
                        <select class="form-control" id="editStatus" name="is_active">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editKode">Kelas </label>
                        <select class="form-control" id="editKelas" name="kelas">
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach ($kelas as $row)
                                <option value="{{ $row->id }}">{{ $row->kodekelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editKode">Kode Jurusan </label>
                        <select class="form-control" id="editKodeJur" name="jurusan">
                            <option value="" disabled selected>Pilih Kode Jurusan</option>
                            @foreach ($jurusans as $row)
                                <option value="{{ $row->id }}">{{ $row->kodejur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
