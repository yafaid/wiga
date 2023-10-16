@extends('admin.master')
@section('judul_halaman', 'Jurusan')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')

    <div class="section-header">
        <h1>Data Jurusan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Kelas</div>
            <div class="breadcrumb-item">Data Jurusan</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h4>Data Jurusan</h4>
                </div>
                <div>
                    <a href="#" id="tambahJurusan" class="btn btn-icon icon-left btn-primary"><i
                            class="fas fa-plus"></i>
                        Tambah
                        Data</a>
                    {{-- <a href="#" class="btn btn-icon icon-left btn-dark"><i class="far fa-file"></i> Print</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-jurusan">
                    <thead>
                        <tr>
                            <th>Kode Jurusan</th>
                            <th>Nama Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer text-right">
                
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var table = $('#table-jurusan').DataTable({
                ajax: {
                    url: "{{ route('get-jurusans') }}",
                    method: "GET",
                    responsive: true,
                    theme: "santafe",
                    dataSrc: ""
                },
                columns: [{
                        data: 'kodejur',
                        name: 'kodejur'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
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

            $('#tambahJurusan').on('click', function() {
                // Menampilkan modal tambah
                $('#tambahJurusanModal').modal('show');
                $('#addForm')[0].reset();
            });

            $('#simpanTambah').on('click', function() {
                var kodeJurusan = $('#tambahKodeJurusan').val();
                var namaJurusan = $('#tambahNamaJurusan').val();

                // Mengirim permintaan AJAX untuk menyimpan data jurusan baru
                $.ajax({
                    url: "{{ route('jurusans.add') }}",
                    method: 'POST',
                    data: {
                        kodejur: kodeJurusan,
                        nama: namaJurusan,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000, // Menutup setelah 2 detik (2000 ms)
                            showConfirmButton: false // Menyembunyikan tombol OK
                        });

                        // Memuat ulang tabel
                        table.ajax.reload();

                        // Menutup modal tambah
                        $('#tambahJurusanModal').modal('hide');
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

            // Event handler untuk tombol Edit
            $('#table-jurusan').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('jurusans.show', '') }}" + "/" + id,
                    method: 'GET',
                    success: function(response) {
                        // Isi form modal edit dengan data yang diperoleh
                        $('#editJurusanId').val(response.id);
                        $('#editKodeJurusan').val(response.kodejur);
                        $('#editNamaJurusan').val(response.nama);
                        // Tampilkan modal edit
                        $('#editModal').modal('show');
                    }
                });
            });

            $('#saveEdit').click(function() {
                var id = $('#editJurusanId').val();
                var kodejur = $('#editKodeJurusan').val();
                var nama = $('#editNamaJurusan').val();

                // Prepare the data to be sent
                var data = {
                    kodejur: kodejur,
                    nama: nama
                };
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Send an AJAX request to update the jurusan
                $.ajax({
                    url: "{{ route('jurusans.edit', '') }}" + "/" + id,
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Close the modal
                        $('#editModal').modal('hide');

                        // Show a success toast
                        Swal.fire({
                            title: 'Berhasil!!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000, // Menutup setelah 2 detik (2000 ms)
                            showConfirmButton: false // Menyembunyikan tombol OK
                        });

                        // Refresh the DataTable
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

            $('#table-jurusan').on('click', '.delete', function() {
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
                        // Send an AJAX request to delete the jurusan
                        $.ajax({
                            url: "{{ route('jurusans.delete', '') }}" + "/" + id,
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="editIdJurusan">ID Jurusan</label>
                        <input type="text" class="form-control" id="editJurusanId" name="id" disabled>
                    </div>

                    <div class="form-group">
                        <label for="editKodeJurusan">Kode Jurusan</label>
                        <input type="text" class="form-control" id="editKodeJurusan" name="kodejur">
                    </div>
                    <div class="form-group">
                        <label for="editNamaJurusan">Nama Jurusan</label>
                        <input type="text" class="form-control" id="editNamaJurusan" name="nama">
                    </div>
                    <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahJurusanModal" tabindex="-1" role="dialog" aria-labelledby="tambahJurusanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahJurusanModalLabel">Tambah Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="form-group">
                        <label for="tambahKodeJurusan">Kode Jurusan</label>
                        <input type="text" class="form-control" id="tambahKodeJurusan" name="kodejur">
                    </div>
                    <div class="form-group">
                        <label for="tambahNamaJurusan">Nama Jurusan</label>
                        <input type="text" class="form-control" id="tambahNamaJurusan" name="nama">
                    </div>
                    <button type="button" class="btn btn-primary" id="simpanTambah">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
