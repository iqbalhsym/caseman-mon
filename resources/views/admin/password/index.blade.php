@section('title', 'Ganti Password')

<x-staradmin>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-primary border-bottom pb-3 mb-4"><i class="mdi mdi-lock-reset me-2"></i>Ganti Password</h4>
                    <p class="card-description text-muted">
                        Silakan masukkan password baru Anda di bawah ini.
                    </p>
                    <form class="forms-sample" id="formInput">
                        <div class="form-group">
                            <label for="nama" class="fw-bold">Nama Akun</label>
                            <input type="text" class="form-control" id="nama" name="nama" readonly value="{{ $user->name }}" style="background-color: #f8f9fa;">
                        </div>
                        <div class="form-group">
                            <label for="password" class="fw-bold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="mdi mdi-lock-outline"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Masukkan password baru">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" id="btnSubmit" class="btn btn-primary text-white me-2"><i class="mdi mdi-content-save me-1"></i> Simpan Password Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $('#btnSubmit').click(function (e) {
                e.preventDefault();

                $('#btnSubmit').prop('disabled', true);
                $('#btnSubmit').html('Loading <i class="mdi mdi-loading mdi-spin"></i>');

                var password = $('#password').val();

                if (password.length == 0){
                    Swal.fire('Peringatan', 'Password tidak boleh kosong', 'warning');
                    $('#btnSubmit').prop('disabled', false);
                    $('#btnSubmit').html('<i class="mdi mdi-content-save me-1"></i> Simpan Password Baru');
                    return false;
                }

                $.ajax({
                    data: {
                        password: password,
                        user: '{{ $user->id }}',
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('admin.ganti-password.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html('<i class="mdi mdi-content-save me-1"></i> Simpan Password Baru');

                        if (data.status === 'error'){
                            Swal.fire('Gagal', data.message, 'error')
                            return false;
                        }

                        Swal.fire('Berhasil', data.message, 'success').then(() => {
                            $('#password').val('');
                            window.location.href = "{{ route('login') }}";
                        });
                    },
                    error: function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html('<i class="mdi mdi-content-save me-1"></i> Simpan Password Baru');
                        Swal.fire('Gagal', data.responseText, 'error');
                    }
                });
            });
        </script>
    @endpush
</x-staradmin>
