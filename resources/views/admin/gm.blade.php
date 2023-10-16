@extends('admin.master')
@section('judul_halaman', 'Guru dan Mapelnya')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-header">
        <h1>Data Guru dan Mapelnya</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dbadmin') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Data Guru</div>
        </div>
    </div>
    <div class="section-body">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h4>Data Guru</h4>
                <div>
                    <a href="#" id="tambahButton" class="btn btn-icon icon-left btn-primary"><i
                            class="fas fa-plus"></i>
                        Tambah Data
                    </a>
                    {{-- <a href="#" class="btn btn-icon icon-left btn-dark"><i class="far fa-file"></i> Print</a> --}}
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="table-gm">
                    <thead>
                        <tr>
                            <th>Kode Guru</th>
                            <th>Kode Mapel</th>
                            <th>Nama Guru</th>
                            <th>Nama Mapel</th>
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
            var table = $('#table-gm').DataTable({
                ajax: {
                    url: "{{ route('get-gm') }}",
                    method: "GET",
                    responsive: true,
                    theme: "santafe",
                    dataSrc: ""
                },
                columns: [{
                        data: 'guru.kodeguru'
                    },
                    {
                        data: 'mapel.kodemapel'
                    },
                    {
                        data: 'guru.nama'
                    },
                    {
                        data: 'mapel.mapel'
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
                var kodeGuru = $('#tambahKodeG').val();
                var mapel = $('#tambahKodeM').val();

                // Mengirim permintaan AJAX untuk menyimpan data Kelas baru
                $.ajax({
                    url: "{{ route('gm.add') }}",
                    method: 'POST',
                    data: {
                        kodeguru: kodeGuru,
                        kodemapel: mapel,
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

            $('#table-gm').on('click', '.edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('gm.show', '') }}" + "/" + id,
                    method: 'GET',
                    success: function(response) {
                        // Isi form modal edit dengan data yang diperoleh
                        $('#editId').val(response.id);
                        $('#editKodeG').val(response.kodeguru);
                        $('#editKodeM').val(response.kodemapel);


                        // Tampilkan modal edit
                        $('#edit').modal('show');
                    }
                });
            });

            $('#saveEdit').click(function() {
                var id = $('#editId').val();
                var kodeGuru = $('#editKodeG').val();
                var mapel = $('#editKodeM').val();


                // Prepare the data to be sent
                var data = {
                    kodeguru: kodeGuru,
                    kodemapel: mapel
                };
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                // Send an AJAX request to update the Kelas
                $.ajax({
                    url: "{{ route('gm.edit', '') }}" + "/" + id,
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

            $('#table-gm').on('click', '.delete', function() {
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
                            url: "{{ route('gm.delete', '') }}" + "/" + id,
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
                        <label for="tambahKode">Kode Guru </label>
                        <select class="form-control" id="tambahKodeG" name="kdguru">
                            <option value="" disabled selected>Pilih Kode Guru</option>
                            @foreach ($guru as $row)
                                <option value="{{ $row->id }}">{{ $row->kodeguru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambahKode">Kode Mapel </label>
                        <select class="form-control" id="tambahKodeM" name="kdmapel">
                            <option value="" disabled selected>Pilih Kode Mapel</option>
                            @foreach ($mapel as $row)
                                <option value="{{ $row->id }}">{{ $row->kodemapel }}</option>
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
                        <label for="tambahKode">Kode Guru </label>
                        <select class="form-control" id="editKodeG" name="kdguru">
                            @foreach ($guru as $row)
                                <option value="{{ $row->id }}">{{ $row->kodeguru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambahKode">Kode Mapel </label>
                        <select class="form-control" id="editKodeM" name="kdmapel">
                            @foreach ($mapel as $row)
                                <option value="{{ $row->id }}">{{ $row->kodemapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="saveEdit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
