@extends('guru.master')
@section('judul_halaman', 'Absensi')
@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endsection
@section('konten')
    <div class="section-body">
        <h2 class="section-title">Hi,{{ Auth::user()->name }}</h2>
        <p class="section-lead">
            Ubah Informasi akun Didalam Halaman ini
        </p>
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-4">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <div class="profile-widget-items">
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Status</div>
                                <div class="profile-widget-item-value badge badge-success">{{ Auth::user()->role->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <div class="font-weight-bold mb-2">Pengaturan</div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#changeUsernameModal">Ganti
                            Username</button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal">Ganti
                            Password</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <script>
            $(document).ready(function() {
                function handleFormSubmission(formId, routeName, successMessage) {
                    var formData = $(formId).serialize();

                    $.ajax({
                        type: "POST",
                        url: "{{ url('') }}" + "/" + routeName,
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                title: 'Sukses',
                                text: successMessage,
                                icon: 'success',
                                timer: 2000, // Menutup setelah 2 detik (2000 ms)
                                showConfirmButton: false // Menyembunyikan tombol OK
                            });

                            $(formId).trigger('reset'); // Reset form
                            $(formId).closest('.modal').modal('hide'); // Close modal
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON.message;

                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                timer: 2000, // Menutup setelah 2 detik (2000 ms)
                                showConfirmButton: false // Menyembunyikan tombol OK
                            });
                        }
                    });
                }

                $("#submitFormButtonPw").on("click", function() {
                    handleFormSubmission("#changePasswordForm", "gurugantipw", "Password berhasil diubah.");
                });

                $("#submitFormButtonUser").on("click", function() {
                    handleFormSubmission("#changeUsernameForm", "gurugantiuname", "Username berhasil diubah.");
                });
            });
        </script>
    @endsection
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="changePasswordModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" class="needs-validation" novalidate="">
                        @csrf
                        <div class="form-group">
                            <label for="currentPassword">Password Saat Ini</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                                required>
                            <div class="invalid-feedback">
                                Masukkan Password Saat ini
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Password Baru</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                required hidden>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" id="submitFormButtonPw" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeUsernameModal" tabindex="-1" role="dialog"
        aria-labelledby="changeUsernameModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeUsernameModalLabel">Ganti Username</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="changeUsernameForm" class="needs-validation" novalidate="">
                        @csrf
                        <div class="form-group">
                            <label for="currentUsername">Username Saat Ini</label>
                            <input type="text" class="form-control" id="currentUsername" name="currentUsername"
                                value="{{ Auth::user()->username }}" required>
                        </div>
                        <div class="form-group">
                            <label for="newUsername">Username Baru</label>
                            <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                            <div class="invalid-feedback">
                                Masukkan Username dgn baik dan benar
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                required hidden>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" id="submitFormButtonUser" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
