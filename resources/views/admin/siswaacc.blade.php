@extends('admin.master')
@section('judul_halaman', 'Akun Admin')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')

    <div class="section-header">
        <h1>Akun Siswa</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">Akun Siswa</div>

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
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
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
            var table = $('#table').DataTable({
                ajax: {
                    url: "{{ route('get-siswaacc') }}",
                    method: "GET",
                    responsive: true,
                    theme: "santafe",
                    dataSrc: ""
                },
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="btn btn-danger delete" data-id="' + data.id +
                                '">Delete</button>';
                        }
                    }
                ]
            });

            $('#tambahButton').on('click', function() {
                // Menampilkan modal tambah
                $('#add').modal('show');
                $('#userForm')[0].reset();
            });

            $('#simpanTambah').on('click', function() {
                var name = $('#name').val();
                var username = $('#username').val();
                var password = $('#password').val();
                var role_id = $('#role_id').val();


                // Mengirim permintaan AJAX untuk menyimpan data Kelas baru
                $.ajax({
                    url: "{{ route('admin.add') }}",
                    method: 'POST',
                    data: {
                        name: name,
                        username: username,
                        password: password,
                        role_id: role_id,
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

            $('#table').on('click', '.delete', function() {
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
                            url: "{{ route('admin.delete', '') }}" + "/" + id,
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
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Form Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Pilih Siswa</label>
                        <select class="form-control" id="name" name="name">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($siswa as $row)
                                <option value="{{ $row->nama }}">{{ $row->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="role_id" name="role_id" style="display: none;">
                            <option value="3" selected>3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="simpanTambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
