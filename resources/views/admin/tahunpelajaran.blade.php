@extends('admin.master')
@section('judul_halaman', 'Tahun Pelajaran')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-header">
        <h1>Data Tahun Pelajaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Tahun</div>
            <div class="breadcrumb-item">Data Tahun Pelajaran</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4>Data Tahun Pelajaran</h4>
                <div>
                    <a href="#" id="tambahTP" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i>
                        Tambah
                        Data</a>
                    {{-- <a href="#" class="btn btn-icon icon-left btn-dark"><i class="far fa-file"></i> Print</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-tahun">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Semester</th>
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
            var table = $('#table-tahun').DataTable({
                ajax: {
                    url: "{{ route('get-tahun') }}",
                    method: "GET",
                    responsive: true,
                    theme: "santafe",
                    dataSrc: ""
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'semester',
                        name: 'semester'
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

            $('#tambahTP').on('click', function() {
                // Menampilkan modal tambah
                $('#tambahTPModal').modal('show');
                $('#tambahTPForm')[0].reset();
            });

            $('#simpanTambah').on('click', function() {
                var tahunPel = $('#tambahTahun').val();
                var semester = $('#tambahSemester').val();

                // Mengirim permintaan AJAX untuk menyimpan data Kelas baru
                $.ajax({
                    url: "{{ route('tp.add') }}",
                    method: 'POST',
                    data: {
                        tahun: tahunPel,
                        semester: semester,
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
                        $('#tambahTPModal').modal('hide');
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
            $('#table-tahun').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('tp.show', '') }}" + "/" + id,
                    method: 'GET',
                    success: function(response) {
                        // Isi form modal edit dengan data yang diperoleh
                        $('#editId').val(response.id);
                        $('#editTahun').val(response.tahun);
                        $('#editSemester').val(response.semester);

                        // Tampilkan modal edit
                        $('#editTPModal').modal('show');
                    }
                });
            });

            $('#saveEdit').click(function() {
                var id = $('#editId').val();
                var tahunPel = $('#editTahun').val();
                var semester = $('#editSemester').val();


                // Prepare the data to be sent
                var data = {
                    tahun: tahunPel,
                    semester: semester
                };
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Send an AJAX request to update the Kelas
                $.ajax({
                    url: "{{ route('tp.edit', '') }}" + "/" + id,
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Close the modal
                        $('#editTPModal').modal('hide');

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

            $('#table-tahun').on('click', '.delete', function() {
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
                            url: "{{ route('tp.delete', '') }}" + "/" + id,
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
<div class="modal fade" id="tambahTPModal" tabindex="-1" role="dialog" aria-labelledby="tambahTPModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahTPModalLabel">Tambah Tahun Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambahTPForm">
                    @csrf
                    <div class="form-group">
                        <label for="tambahTPJurusan">Tahun Pembelajaran</label>
                        <input type="text" class="form-control" id="tambahTahun" name="tahun">
                    </div>
                    <div class="form-group">
                        <label for="tambahSemester">Semester</label>
                        <select class="form-control" id="tambahSemester" name="semester" required>
                            <option value="" disabled selected>Pilih Salah Satu</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="simpanTambah">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editTPModal" tabindex="-1" role="dialog" aria-labelledby="editTPModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTPModalLabel">Edit Tahun Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambahTPForm">
                    @csrf
                    <div class="form-group">
                        <label for="editTPId">ID Tahun Pembelajaran</label>
                        <input type="text" class="form-control" id="editId" name="id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tambahTPJurusan">Tahun Pembelajaran</label>
                        <input type="text" class="form-control" id="editTahun" name="tahun">
                    </div>
                    <div class="form-group">
                        <label for="tambahSemester">Semester</label>
                        <select class="form-control" id="editSemester" name="semester" required>
                            <option value="" disabled selected>Pilih Salah Satu</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
