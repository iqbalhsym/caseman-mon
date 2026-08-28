@section('title', 'Data LAB')

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
        .col-nama-item {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pagination .page-link { font-size: 0.82rem; }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen LAB</a>
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
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="input-group" style="width: 280px;">
                                                    <span class="input-group-text input-group-text-custom">
                                                        <i class="mdi mdi-magnify text-muted"></i>
                                                    </span>
                                                    <input type="text" id="lab-search" class="form-control search-input-custom" placeholder="Cari kode atau item lab...">
                                                </div>
                                                <select class="form-select border-0 shadow-sm" id="lab-color-filter" style="width: 150px; background-color: #f8f9fa;">
                                                    <option value="">Semua Warna</option>
                                                    <option value="hijau">Hijau</option>
                                                    <option value="kuning">Kuning</option>
                                                    <option value="merah">Merah</option>
                                                    <option value="-">Tanpa Warna</option>
                                                </select>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-success btn-sm text-white mb-0" id="btn-import">
                                                    <i class="mdi mdi-file-excel-box"></i> Import Excel
                                                </button>
                                                <a href="{{ route('admin.lab.export') }}" class="btn btn-info btn-sm text-white mb-0">
                                                    <i class="mdi mdi-download"></i> Export
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add">
                                                    <i class="mdi mdi-plus-box"></i> Tambah LAB
                                                </button>
                                            </div>
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
                                            <table class="table table-hover select-table table-condensed" style="table-layout: fixed; width: 100%;">
                                                <colgroup>
                                                    <col style="width: 20%">
                                                    <col style="width: 50%">
                                                    <col style="width: 15%">
                                                    <col style="width: 15%">
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th>Kode Item</th>
                                                        <th>Nama Item</th>
                                                        <th>Kategori</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="lab-table-body">
                                                    <tr><td colspan="4" class="text-center py-4"><i class="mdi mdi-loading mdi-spin"></i> Memuat data...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Pagination & Info --}}
                                        <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                                            <small class="text-muted" id="lab-info"></small>
                                            <nav aria-label="Pagination LAB">
                                                <ul class="pagination pagination-sm mb-0" id="lab-pagination"></ul>
                                            </nav>
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

    <!-- Bootstrap 5 Modal -->
    <div class="modal fade" id="add-lab-modal" tabindex="-1" aria-labelledby="addLabModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLabModalLabel">Tambah Data LAB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">

                        <div class="form-group mb-3">
                            <label for="kode_item" class="form-label">Kode Item</label>
                            <input type="text" class="form-control" id="kode_item" name="kode_item" placeholder="Masukkan kode item (misal: LAB-0001)...">
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_item" class="form-label">Nama Item <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_item" name="nama_item" placeholder="Masukkan nama pemeriksaan lab..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="warna" class="form-label">Kategori Warna <span class="text-danger">*</span></label>
                            <select class="form-select" id="warna" name="warna" required>
                                <option value="">Pilih Warna...</option>
                                <option value="hijau">Hijau</option>
                                <option value="kuning">Kuning</option>
                                <option value="merah">Merah</option>
                                <option value="none">Tanpa Warna</option>
                            </select>
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

    <!-- Modal Import -->
    <div class="modal fade" id="import-modal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="mdi mdi-file-excel-box text-success"></i> Import Data LAB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImport" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-info py-2 small mb-3">
                            Format Excel harus memiliki kolom: <br>
                            <strong>A: NAMA ITEM</strong>, <strong>B: KATEGORI</strong> (warna)
                        </div>
                        <div class="form-group mb-3">
                            <label for="file" class="form-label">Pilih File Excel (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="import_warna" class="form-label">Atur Semua Kategori Warna Ke:</label>
                            <select class="form-select" id="import_warna" name="warna">
                                <option value="none">Gunakan Warna dari Kolom B Excel</option>
                                <option value="hijau">Hijau</option>
                                <option value="kuning">Kuning</option>
                                <option value="merah">Merah</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="import-save-btn" class="btn btn-success text-white">Mulai Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const inputModal  = new bootstrap.Modal(document.getElementById('add-lab-modal'));
            const importModal = new bootstrap.Modal(document.getElementById('import-modal'));

            let currentPage  = 1;
            let debounceTimer = null;

            // ─── Load Data (Server-side Pagination) ───────────────────────────────
            function loadLab(page) {
                currentPage = page || 1;
                const search = $('#lab-search').val();
                const warna  = $('#lab-color-filter').val();

                $('#lab-table-body').html('<tr><td colspan="4" class="text-center py-3"><i class="mdi mdi-loading mdi-spin"></i> Memuat...</td></tr>');

                $.ajax({
                    url: "{{ route('admin.lab.data') }}",
                    type: "GET",
                    data: { search, warna, page: currentPage },
                    dataType: 'json',
                    success: function (res) {
                        renderTable(res);
                        renderPagination(res);
                        if (res.from && res.to) {
                            $('#lab-info').text('Menampilkan ' + res.from + '–' + res.to + ' dari ' + res.total + ' data');
                        } else {
                            $('#lab-info').text('');
                        }
                    },
                    error: function () {
                        $('#lab-table-body').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    }
                });
            }

            function renderTable(res) {
                if (!res.data || res.data.length === 0) {
                    $('#lab-table-body').html('<tr><td colspan="4" class="text-center py-4">Tidak ada data LAB ditemukan.</td></tr>');
                    $('#lab-info').text('');
                    return;
                }

                let html = '';
                res.data.forEach(function (el) {
                    let badgeWarna = '<span class="badge bg-secondary">-</span>';
                    if (el.warna === 'hijau')  badgeWarna = '<span class="badge bg-success">Hijau</span>';
                    if (el.warna === 'kuning') badgeWarna = '<span class="badge bg-warning text-dark">Kuning</span>';
                    if (el.warna === 'merah')  badgeWarna = '<span class="badge bg-danger">Merah</span>';

                    html += `<tr class="lab-row" data-id="${el.id}" data-warna="${el.warna || '-'}">
                        <td><span class="badge badge-info">${el.kode_item || '-'}</span></td>
                        <td><h6 class="mb-0 col-nama-item" title="${el.nama_item || '-'}">${el.nama_item || '-'}</h6></td>
                        <td>${badgeWarna}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${el.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${el.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                        </td>
                    </tr>`;
                });
                $('#lab-table-body').html(html);
            }

            function renderPagination(res) {
                if (res.last_page <= 1) {
                    $('#lab-pagination').html('');
                    return;
                }

                let pages = '';
                pages += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;

                let start = Math.max(1, res.current_page - 2);
                let end   = Math.min(res.last_page, res.current_page + 2);
                if (start > 1) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                for (let i = start; i <= end; i++) {
                    pages += `<li class="page-item ${i === res.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                if (end < res.last_page) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;

                pages += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;

                $('#lab-pagination').html(pages);
            }

            // Pagination click
            $(document).on('click', '#lab-pagination .page-link', function (e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (page && page !== currentPage) loadLab(page);
            });

            // Search — debounce 400ms
            $('#lab-search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { loadLab(1); }, 400);
            });

            // Color filter
            $('#lab-color-filter').on('change', function () { loadLab(1); });

            // ─── Modal Tambah ──────────────────────────────────────────────────────
            $('#add').click(function () {
                $('#formInput')[0].reset();
                $('#product_id').val('');
                $('#addLabModalLabel').html('Tambah Data LAB');
                inputModal.show();
            });

            $('#btn-import').click(function () {
                $('#formImport')[0].reset();
                importModal.show();
            });

            // ─── Save ──────────────────────────────────────────────────────────────
            $('#formInput').submit(function (e) {
                e.preventDefault();
                const $btn = $('#save-btn');
                $btn.prop('disabled', true).html('Saving <i class="mdi mdi-loading mdi-spin"></i>');

                $.ajax({
                    data: $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    url: "{{ route('admin.lab.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            inputModal.hide();
                            loadLab(currentPage);
                        } else {
                            showToast(data.message, 'error');
                        }
                    },
                    error: function (data) { showToast(data.responseText, 'error'); },
                    complete: function () { $btn.prop('disabled', false).html('Simpan'); }
                });
            });

            // ─── Import ────────────────────────────────────────────────────────────
            $('#formImport').submit(function (e) {
                e.preventDefault();
                const $btn = $('#import-save-btn');
                $btn.prop('disabled', true).html('Importing <i class="mdi mdi-loading mdi-spin"></i>');

                var formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    data: formData,
                    url: "{{ route('admin.lab.import') }}",
                    type: "POST",
                    cache: false, contentType: false, processData: false,
                    success: function (data) {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            importModal.hide();
                            loadLab(1);
                        } else {
                            showToast(data.message, 'error');
                        }
                    },
                    error: function (data) { showToast(data.responseText, 'error'); },
                    complete: function () { $btn.prop('disabled', false).html('Mulai Import'); }
                });
            });

            // ─── Edit ──────────────────────────────────────────────────────────────
            $('body').on('click', '.edit', function () {
                const id = $(this).data("id");
                $.get("{{ route('admin.lab.index') }}/" + id + '/edit', function (data) {
                    if (data.status === 'success') {
                        $('#formInput')[0].reset();
                        $('#addLabModalLabel').html("Edit Data LAB");
                        $('#product_id').val(data.data.id);
                        $('#kode_item').val(data.data.kode_item);
                        $('#nama_item').val(data.data.nama_item);
                        $('#warna').val(data.data.warna ? data.data.warna : 'none');
                        inputModal.show();
                    } else {
                        showToast(data.message, 'error');
                    }
                });
            });

            // ─── Delete ───────────────────────────────────────────────────────────
            $('body').on('click', '.delete', function () {
                const id = $(this).data("id");
                Swal.fire({
                    title: 'Yakin Ingin Menghapus Data LAB?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f3bb3',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.lab.index') }}/" + id,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (data) {
                                if (data.status === 'success') {
                                    showToast(data.message, 'success');
                                    loadLab(currentPage);
                                } else {
                                    Swal.fire("Gagal!!", data.message, "error");
                                }
                            },
                            error: function (data) { Swal.fire("Gagal!!", data.responseText, "error"); }
                        });
                    }
                });
            });

            // ─── Init ─────────────────────────────────────────────────────────────
            loadLab(1);
        </script>
    @endpush
</x-staradmin>
