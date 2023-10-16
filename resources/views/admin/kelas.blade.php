@extends('admin.master')
@section('judul_halaman', 'Kelas')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-header">
        <h1>Data Kelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Kelas</div>
            <div class="breadcrumb-item">Kelas</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4>Data Kelas</h4>
                <div>
                    <a href="#" id="tambahKelas" class="btn btn-icon icon-left btn-primary"><i
                            class="fas fa-plus"></i>
                        Tambah
                        Data</a>
                    {{-- <a href="#" class="btn btn-icon icon-left btn-dark"><i class="far fa-file"></i> Print</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-kelas">
                <thead>
                    <tr>
                        <th>Kode Kelas</th>
                        <th>Deskripsi</th>
                        <th>Ruangan</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
            </div>
            <div class="card-footer">

            </div>
        </div>
        <script>
            $(document).ready(function() {
                var table = $('#table-kelas').DataTable({
                    ajax: {
                        url: "{{ route('get-kelas') }}",
                        method: "GET",
                        responsive: true,
                        theme: "santafe",
                        dataSrc: ""
                    },
                    columns: [{
                            data: 'kodekelas',
                            name: 'kodekelas'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi'
                        },
                        {
                            data: 'ruangan',
                            name: 'ruangan'
                        },
                        {
                            data: 'jurusan.nama',
                            name: 'jurusan.nama'
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

                $('#tambahKelas').on('click', function() {
                    // Menampilkan modal tambah
                    $('#tambahKelasModal').modal('show');
                    $('#tambahKelasForm')[0].reset();
                });

                $('#simpanTambah').on('click', function() {
                    var kodeKelas = $('#tambahKodeKelas').val();
                    var desKelas = $('#tambahDeskripsiKelas').val();
                    var ruangKelas = $('#tambahRuangKelas').val();
                    var jurusanKelas = $('#tambahJurusanKelas').val();

                    // Mengirim permintaan AJAX untuk menyimpan data Kelas baru
                    $.ajax({
                        url: "{{ route('kelas.add') }}",
                        method: 'POST',
                        data: {
                            kodekelas: kodeKelas,
                            deskripsi: desKelas,
                            ruangan: ruangKelas,
                            jurusan_id: jurusanKelas,
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
                            $('#tambahKelasModal').modal('hide');
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
                $('#table-kelas').on('click', '.edit', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('kelas.show', '') }}" + "/" + id,
                        method: 'GET',
                        success: function(response) {
                            // Isi form modal edit dengan data yang diperoleh
                            $('#editKelasId').val(response.id);
                            $('#editKodeKelas').val(response.kodekelas);
                            $('#editDeskripsiKelas').val(response.deskripsi);
                            $('#editRuangKelas').val(response.ruangan);
                            $('#editJurusanKelas').val(response.jurusan_id);
                            // Tampilkan modal edit
                            $('#editModal').modal('show');
                        }
                    });
                });

                $('#saveEdit').click(function() {
                    var id = $('#editKelasId').val();                    
                    var kodeKelas = $('#editKodeKelas').val();
                    var desKelas = $('#editDeskripsiKelas').val();
                    var ruangKelas = $('#editRuangKelas').val();
                    var jurusanKelas = $('#editJurusanKelas').val();

                    // Prepare the data to be sent
                    var data = {
                        kodekelas: kodeKelas,
                        deskripsi: desKelas,
                        ruangan: ruangKelas,
                        jurusan_id: jurusanKelas
                    };
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    // Send an AJAX request to update the Kelas
                    $.ajax({
                        url: "{{ route('kelas.edit', '') }}" + "/" + id,
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

                $('#table-kelas').on('click', '.delete', function() {
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
                                url: "{{ route('kelas.delete', '') }}" + "/" + id,
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
    </div>

@endsection
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <div class="form-group">
                        <label for="editIdKelas">ID Kelas</label>
                        <input type="text" class="form-control" id="editKelasId" name="id" disabled>
                    </div>

                    <div class="form-group">
                        <label for="editKodeKelas">Kode Kelas</label>
                        <input type="text" class="form-control" id="editKodeKelas" name="kodekelas">
                    </div>
                    <div class="form-group">
                        <label for="editDeskripsiKelas">Deskripsi Kelas</label>
                        <input type="text" class="form-control" id="editDeskripsiKelas" name="deskripsi">
                    </div>
                    <div class="form-group">
                        <label for="editDeskripsiKelas">Ruangan Kelas</label>
                        <input type="text" class="form-control" id="editRuangKelas" name="ruangan">
                    </div>
                    <div class="form-group">
                        <label for="editJurusanKelas">Jurusan </label>
                        <select class="form-control" id="editJurusanKelas" name="jurusan_id">
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahKelasModal" tabindex="-1" role="dialog" aria-labelledby="tambahKelasModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKelasModalLabel">Tambah Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambahKelasForm">
                    @csrf
                    <div class="form-group">
                        <label for="tambahKodeKelas">Kode Kelas</label>
                        <input type="text" class="form-control" id="tambahKodeKelas" name="kodekelas">
                    </div>
                    <div class="form-group">
                        <label for="tambahDeskripsiKelas">Deskripsi Kelas</label>
                        <input type="text" class="form-control" id="tambahDeskripsiKelas" name="deskripsi">
                    </div>
                    <div class="form-group">
                        <label for="tambahRuangKelas">Ruang Kelas</label>
                        <input type="text" class="form-control" id="tambahRuangKelas" name="ruangan">
                    </div>
                    <div class="form-group">
                        <label for="tambahJurusanKelas">Jurusan </label>
                        <select class="form-control" id="tambahJurusanKelas" name="jurusan_id">
                            @foreach ($jurusans as $jurusan)
                                <option value="{{ $jurusan->id }}">{{ $jurusan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="simpanTambah">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
