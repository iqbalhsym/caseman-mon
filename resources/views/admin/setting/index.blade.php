@section('title', 'Running Text')

<x-staradmin>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card mx-auto mt-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-primary border-bottom pb-3 mb-4"><i class="mdi mdi-bullhorn-outline me-2"></i>Pengaturan Running Text</h4>
                    <p class="card-description text-muted">
                        Ubah isi teks pengumuman yang berjalan di bagian atas halaman aplikasi monitoring.
                    </p>
                    <form class="forms-sample" id="formInput">
                        <div class="form-group mb-3">
                            <label for="announcement" class="fw-bold">Running Text Pengumuman</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="mdi mdi-text-shadow"></i></span>
                                <textarea class="form-control" id="announcement" name="announcement" rows="4" placeholder="Masukkan teks pengumuman..." required>{{ $announcement }}</textarea>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Teks ini akan langsung berjalan di semua halaman admin di bawah menu Selamat Datang.
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" id="btnSubmit" class="btn btn-primary text-white me-2"><i class="mdi mdi-content-save me-1"></i> Simpan Perubahan</button>
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
                $('#btnSubmit').html('Saving <i class="mdi mdi-loading mdi-spin"></i>');

                var announcement = $('#announcement').val();

                $.ajax({
                    data: {
                        announcement: announcement,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('admin.setting.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html('<i class="mdi mdi-content-save me-1"></i> Simpan Perubahan');

                        if (data.status === 'error'){
                            showToast(data.message, 'error');
                            return false;
                        }

                        showToast(data.message, 'success');
                        setTimeout(() => { window.location.reload(); }, 800);
                    },
                    error: function (data) {
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html('<i class="mdi mdi-content-save me-1"></i> Simpan Perubahan');
                        showToast(data.responseText, 'error');
                    }
                });
            });
        </script>
    @endpush
</x-staradmin>
