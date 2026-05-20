@section('title', 'Hak Akses (Role)')

<x-staradmin>

    <style>
        .search-card-body {
            padding: 0.75rem 1.25rem !important;
        }
        .compact-margin {
            margin-bottom: 0.5rem !important;
        }
        .input-group-text-custom {
            background-color: transparent !important;
            border-right: none !important;
            padding-right: 0.5rem !important;
        }
        .search-input-custom {
            border-left: none !important;
            padding-left: 0 !important;
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen Hak Akses</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">

                        {{-- Card Pencarian (Toolbar) --}}
                        <div class="row compact-margin">
                            <div class="col-12 stretch-card">
                                <div class="card shadow-sm">
                                    <div class="card-body search-card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="input-group" style="width: 280px;">
                                                <span class="input-group-text input-group-text-custom">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                                <input type="text" id="role-search" class="form-control search-input-custom" placeholder="Cari nama role...">
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add">
                                                <i class="mdi mdi-shield-plus"></i> Tambah Role
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Tabel (Data) --}}
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover select-table table-condensed" id="role-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama Role (Sistem)</th>
                                                        <th>Alias / Label</th>
                                                        <th>Jumlah Pengguna</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="role-table-body">
                                                    @forelse ($data as $item)
                                                        <tr class="role-row" data-name="{{ strtolower($item->alias) }}">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td><code>{{ $item->name }}</code></td>
                                                            <td>
                                                                <h6 class="mb-0">{{ $item->alias }}</h6>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-opacity-info">{{ $item->users_count }} pengguna</span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="{{ $item->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                                                @if ($item->id !== 1)
                                                                    <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="{{ $item->id }}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="5" class="text-center py-4">Belum ada data role</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah / Edit Role -->
    <div class="modal fade" id="role-modal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">

                        <div class="form-group">
                            <label for="alias">Alias / Label <small class="text-muted">(tampil di aplikasi)</small></label>
                            <input type="text" class="form-control" id="alias" name="alias" placeholder="Contoh: Case Manager" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Role <small class="text-muted">(sistem)</small></label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: casemanager" autocomplete="off">
                            <small class="text-muted">Diisi otomatis dari alias, bisa diubah manual.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="save-btn" class="btn btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const roleModal = new bootstrap.Modal(document.getElementById('role-modal'));

            // Auto-generate name from alias
            $('#alias').on('input', function () {
                const aliasVal = $(this).val().toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
                if (!$('#product_id').val()) {
                    $('#nama').val(aliasVal);
                }
            });

            $('#add').click(function () {
                $('#roleModalLabel').text('Tambah Role');
                $('#formInput')[0].reset();
                $('#product_id').val('');
                roleModal.show();
            });

            $('#save-btn').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: $('#formInput').serialize(),
                    url: "{{ route('admin.role.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function () {
                        $('#save-btn').prop('disabled', true).html('Loading <i class="mdi mdi-loading mdi-spin"></i>');
                    },
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        showToast(data.message);
                        $('#formInput')[0].reset();
                        roleModal.hide();
                        loadData();
                    },
                    error: function (data) { showToast(data.responseText, 'error'); },
                    complete: function () { $('#save-btn').prop('disabled', false).html('Simpan'); }
                });
            });

            function loadData() {
                $.ajax({
                    url: "{{ route('admin.role.create') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        let html = '';
                        data.data.forEach(function (item, idx) {
                            const deleteBtn = item.id !== 1
                                ? `<button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${item.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>`
                                : '';
                            html += `
                                <tr class="role-row" data-name="${(item.alias || '').toLowerCase()}">
                                    <td>${idx + 1}</td>
                                    <td><code>${item.name}</code></td>
                                    <td><h6 class="mb-0">${item.alias || '-'}</h6></td>
                                    <td><span class="badge badge-opacity-info">${item.users_count || 0} pengguna</span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${item.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                        ${deleteBtn}
                                    </td>
                                </tr>`;
                        });
                        $('#role-table-body').html(html || '<tr><td colspan="5" class="text-center py-4">Belum ada data role</td></tr>');
                    },
                    error: function (data) { showToast(data.responseText, 'error'); }
                });
            }

            $('body').on('click', '.edit', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.role.index') }}/" + id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        $('#roleModalLabel').text('Edit Role');
                        $('#product_id').val(data.data.id);
                        $('#alias').val(data.data.alias);
                        $('#nama').val(data.data.name);
                        roleModal.show();
                    },
                    error: function (data) { showToast(data.responseText, 'error'); }
                });
            });

            $('body').on('click', '.delete', function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin Ingin Menghapus Role?',
                    text: 'Role yang masih digunakan oleh pengguna tidak dapat dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f3bb3',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.role.store') }}/" + id,
                            success: function (data) {
                                data.status === 'success'
                                    ? Swal.fire('Berhasil!', data.message, 'success')
                                    : Swal.fire('Gagal!', data.message, 'error');
                                loadData();
                            },
                            error: function (data) { Swal.fire('Gagal!', data.responseText, 'error'); }
                        });
                    }
                });
            });

            // Client-side search
            $('#role-search').on('input', function () {
                const q = $(this).val().toLowerCase();
                $('.role-row').each(function () {
                    $(this).toggle($(this).data('name').includes(q));
                });
            });
        </script>
    @endpush

</x-staradmin>
