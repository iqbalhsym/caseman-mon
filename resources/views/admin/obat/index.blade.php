@section('title', 'Data Obat')

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
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen Obat</a>
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
                                                    <input type="text" id="obat-search" class="form-control search-input-custom" placeholder="Cari kode, item, atau generik...">
                                                </div>
                                                <select class="form-select border-0 shadow-sm" id="obat-color-filter" style="width: 150px; background-color: #f8f9fa;">
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
                                                <a href="{{ route('admin.obat.export') }}" class="btn btn-info btn-sm text-white mb-0">
                                                    <i class="mdi mdi-download"></i> Export
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add">
                                                    <i class="mdi mdi-plus-box"></i> Tambah Obat
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
                                                    <col style="width: 6%">
                                                    <col style="width: 20%">
                                                    <col style="width: 12%">
                                                    <col style="width: 32%">
                                                    <col style="width: 12%">
                                                    <col style="width: 10%">
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th>F/NF</th>
                                                        <th>Nama Generik</th>
                                                        <th>Kode Item</th>
                                                        <th>Nama Item</th>
                                                        <th>Kategori</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="obat-table-body">
                                                    <tr><td colspan="6" class="text-center py-4"><i class="mdi mdi-loading mdi-spin"></i> Memuat data...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Pagination & Info --}}
                                        <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                                            <small class="text-muted" id="obat-info"></small>
                                            <nav aria-label="Pagination Obat">
                                                <ul class="pagination pagination-sm mb-0" id="obat-pagination"></ul>
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
    <div class="modal fade" id="add-obat-modal" tabindex="-1" aria-labelledby="addObatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addObatModalLabel">Tambah Data Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">

                        <div class="form-group">
                            <label for="f_nf">F/NF</label>
                            <input type="text" class="form-control" id="f_nf" name="f_nf" autocomplete="off" placeholder="Contoh: F">
                        </div>
                        <div class="form-group">
                            <label for="nama_generik">Nama Generik</label>
                            <input type="text" class="form-control" id="nama_generik" name="nama_generik" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="kode_item">Kode Item</label>
                            <input type="text" class="form-control" id="kode_item" name="kode_item" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="nama_item">Nama Item</label>
                            <input type="text" class="form-control" id="nama_item" name="nama_item" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="warna">Kategori Warna</label>
                            <select class="form-select" id="warna" name="warna">
                                <option value="">Pilih Warna...</option>
                                <option value="hijau">Hijau</option>
                                <option value="kuning">Kuning</option>
                                <option value="merah">Merah</option>
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

    <!-- Import Modal -->
    <div class="modal fade" id="import-modal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImport" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information-outline"></i> Pastikan format Excel Anda memiliki urutan kolom:
                            <b>F/NF, Nama Generik, Kode Item, Nama Item</b> pada baris pertama sebagai Header.
                        </div>
                        <div class="form-group">
                            <label for="file">File Excel (.xlsx, .xls, .csv)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls, .csv" required>
                        </div>
                        <div class="form-group">
                            <label for="import_warna">Kategori Warna Obat</label>
                            <select class="form-select" id="import_warna" name="warna" required>
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
                        <button type="submit" id="btn-import-save" class="btn btn-success text-white">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const obatModal = new bootstrap.Modal(document.getElementById('add-obat-modal'));
            const importModal = new bootstrap.Modal(document.getElementById('import-modal'));

            let currentPage = 1;
            let debounceTimer = null;

            // ─── Load Data (Server-side Pagination) ───────────────────────────────
            function loadObat(page) {
                currentPage = page || 1;
                const search = $('#obat-search').val();
                const warna  = $('#obat-color-filter').val();

                $('#obat-table-body').html('<tr><td colspan="6" class="text-center py-3"><i class="mdi mdi-loading mdi-spin"></i> Memuat...</td></tr>');

                $.ajax({
                    url: "{{ route('admin.obat.data') }}",
                    type: "GET",
                    data: { search, warna, page: currentPage },
                    dataType: 'json',
                    success: function (res) {
                        renderTable(res);
                        renderPagination(res);
                        $('#obat-info').text(
                            'Menampilkan ' + res.from + '–' + res.to + ' dari ' + res.total + ' data'
                        );
                    },
                    error: function () {
                        $('#obat-table-body').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    }
                });
            }

            function renderTable(res) {
                if (!res.data || res.data.length === 0) {
                    $('#obat-table-body').html('<tr><td colspan="6" class="text-center py-4">Tidak ada data obat ditemukan.</td></tr>');
                    $('#obat-info').text('');
                    return;
                }

                let html = '';
                res.data.forEach(function (el) {
                    let badgeWarna = '<span class="badge bg-secondary">-</span>';
                    if (el.warna === 'hijau')  badgeWarna = '<span class="badge bg-success">Hijau</span>';
                    if (el.warna === 'kuning') badgeWarna = '<span class="badge bg-warning text-dark">Kuning</span>';
                    if (el.warna === 'merah')  badgeWarna = '<span class="badge bg-danger">Merah</span>';

                    html += `<tr class="obat-row" data-id="${el.id}" data-warna="${el.warna || '-'}">
                        <td class="col-nama-item">${el.f_nf || '-'}</td>
                        <td class="col-nama-item" title="${el.nama_generik || '-'}">${el.nama_generik || '-'}</td>
                        <td><span class="badge badge-info">${el.kode_item || '-'}</span></td>
                        <td><h6 class="mb-0 col-nama-item" title="${el.nama_item || '-'}">${el.nama_item || '-'}</h6></td>
                        <td>${badgeWarna}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${el.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${el.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                        </td>
                    </tr>`;
                });
                $('#obat-table-body').html(html);
            }

            function renderPagination(res) {
                if (res.last_page <= 1) {
                    $('#obat-pagination').html('');
                    return;
                }

                let pages = '';
                // Prev
                pages += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;

                // Page numbers (show window of 5 around current)
                let start = Math.max(1, res.current_page - 2);
                let end   = Math.min(res.last_page, res.current_page + 2);
                if (start > 1) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                for (let i = start; i <= end; i++) {
                    pages += `<li class="page-item ${i === res.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                if (end < res.last_page) pages += `<li class="page-item disabled"><span class="page-link">…</span></li>`;

                // Next
                pages += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;

                $('#obat-pagination').html(pages);
            }

            // Pagination click
            $(document).on('click', '#obat-pagination .page-link', function (e) {
                e.preventDefault();
                const page = parseInt($(this).data('page'));
                if (page && page !== currentPage) loadObat(page);
            });

            // Search — debounce 400ms
            $('#obat-search').on('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () { loadObat(1); }, 400);
            });

            // Color filter
            $('#obat-color-filter').on('change', function () { loadObat(1); });

            // ─── Modal Tambah ──────────────────────────────────────────────────────
            $('#add').click(function () {
                $('#addObatModalLabel').text('Tambah Data Obat');
                $('#formInput')[0].reset();
                $('#product_id').val('');
                obatModal.show();
                setTimeout(() => $('#f_nf').focus(), 500);
            });

            $('#formInput').submit(function (e) {
                e.preventDefault();
                const $btn = $('#save-btn');
                $btn.prop('disabled', true).html('Loading <i class="mdi mdi-loading mdi-spin"></i>');

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('admin.obat.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        showToast(data.message);
                        obatModal.hide();
                        loadObat(currentPage);
                    },
                    error: function (data) { showToast(data.responseText, 'error'); },
                    complete: function () { $btn.prop('disabled', false).html('Simpan'); }
                });
            });

            // ─── Import ────────────────────────────────────────────────────────────
            $('#btn-import').click(function () {
                $('#file').val('');
                importModal.show();
            });

            $('#formImport').submit(function (e) {
                e.preventDefault();
                const $btn = $('#btn-import-save');
                $btn.prop('disabled', true).html('Loading <i class="mdi mdi-loading mdi-spin"></i>');

                $.ajax({
                    url: "{{ route('admin.obat.import') }}",
                    type: "POST",
                    data: new FormData(this),
                    cache: false, contentType: false, processData: false,
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        showToast(data.message);
                        importModal.hide();
                        loadObat(1);
                    },
                    error: function () { showToast("Terjadi kesalahan sistem", 'error'); },
                    complete: function () { $btn.prop('disabled', false).html('Import'); }
                });
            });

            // ─── Edit ──────────────────────────────────────────────────────────────
            $('body').on('click', '.edit', function () {
                const product_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.obat.index') }}/" + product_id + '/edit',
                    type: "GET", dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error') { showToast(data.message, 'error'); return; }
                        $('#addObatModalLabel').text('Edit Data Obat');
                        $('#f_nf').val(data.data.f_nf);
                        $('#nama_generik').val(data.data.nama_generik);
                        $('#kode_item').val(data.data.kode_item);
                        $('#nama_item').val(data.data.nama_item);
                        $('#warna').val(data.data.warna);
                        $('#product_id').val(data.data.id);
                        obatModal.show();
                    },
                    error: function (data) { showToast(data.responseText, 'error'); }
                });
            });

            // ─── Delete ───────────────────────────────────────────────────────────
            $('body').on('click', '.delete', function () {
                const id = $(this).data("id");
                Swal.fire({
                    title: 'Yakin Ingin Menghapus ?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
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
                            url: "{{ route('admin.obat.store') }}/" + id,
                            success: function (data) {
                                data.status === 'success'
                                    ? Swal.fire("Berhasil!", data.message, "success")
                                    : Swal.fire("Gagal!", data.message, "error");
                                loadObat(currentPage);
                            },
                            error: function (data) { Swal.fire("Gagal!", data.responseText, "error"); }
                        });
                    }
                });
            });

            // ─── Init ─────────────────────────────────────────────────────────────
            loadObat(1);
        </script>
    @endpush

</x-staradmin>
