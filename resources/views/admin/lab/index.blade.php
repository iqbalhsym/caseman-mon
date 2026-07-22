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
                                                    @forelse ($data as $item)
                                                        <tr class="lab-row" data-id="{{ $item->id }}" data-warna="{{ $item->warna ?? '-' }}">
                                                            <td><span class="badge badge-info">{{ $item->kode_item }}</span></td>
                                                            <td><h6 class="mb-0 col-nama-item" title="{{ $item->nama_item }}">{{ $item->nama_item }}</h6></td>
                                                            <td>
                                                                @if($item->warna == 'hijau')
                                                                    <span class="badge bg-success">Hijau</span>
                                                                @elseif($item->warna == 'kuning')
                                                                    <span class="badge bg-warning text-dark">Kuning</span>
                                                                @elseif($item->warna == 'merah')
                                                                    <span class="badge bg-danger">Merah</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="{{ $item->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="{{ $item->id }}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="4" class="text-center py-4">Belum ada data pemeriksaan LAB</td></tr>
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
            const inputModal = new bootstrap.Modal(document.getElementById('add-lab-modal'));
            const importModal = new bootstrap.Modal(document.getElementById('import-modal'));

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

            // Live Search Client-side
            function filterTable() {
                const searchVal = $('#lab-search').val().toLowerCase();
                const colorFilter = $('#lab-color-filter').val();

                $('.lab-row').each(function () {
                    const row = $(this);
                    const kode = row.find('td:nth-child(1)').text().toLowerCase();
                    const nama = row.find('h6').text().toLowerCase();
                    const warna = row.data('warna');

                    const matchesSearch = kode.includes(searchVal) || nama.includes(searchVal);
                    const matchesColor = !colorFilter || (colorFilter === '-' && warna === '-') || (warna === colorFilter);

                    if (matchesSearch && matchesColor) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            $('#lab-search').on('keyup', filterTable);
            $('#lab-color-filter').on('change', filterTable);

            // Edit
            $('body').on('click', '.edit', function () {
                var id = $(this).data("id");
                $.get("{{ route('admin.lab.index') }}" +'/' + id +'/edit', function (data) {
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

            // Save
            $('#formInput').submit(function (e) {
                e.preventDefault();
                $('#save-btn').prop('disabled', true);
                $('#save-btn').html('Saving <i class="mdi mdi-loading mdi-spin"></i>');

                $.ajax({
                    data: $('#formInput').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                    url: "{{ route('admin.lab.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            inputModal.hide();
                            setTimeout(() => { window.location.reload(); }, 800);
                        } else {
                            showToast(data.message, 'error');
                            $('#save-btn').prop('disabled', false);
                            $('#save-btn').html('Simpan');
                        }
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                        $('#save-btn').prop('disabled', false);
                        $('#save-btn').html('Simpan');
                    }
                });
            });

            // Import
            $('#formImport').submit(function (e) {
                e.preventDefault();
                $('#import-save-btn').prop('disabled', true);
                $('#import-save-btn').html('Importing <i class="mdi mdi-loading mdi-spin"></i>');

                var formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    data: formData,
                    url: "{{ route('admin.lab.import') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status === 'success') {
                            showToast(data.message, 'success');
                            importModal.hide();
                            setTimeout(() => { window.location.reload(); }, 800);
                        } else {
                            showToast(data.message, 'error');
                            $('#import-save-btn').prop('disabled', false);
                            $('#import-save-btn').html('Mulai Import');
                        }
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                        $('#import-save-btn').prop('disabled', false);
                        $('#import-save-btn').html('Mulai Import');
                    }
                });
            });

            // Delete
            $('body').on('click', '.delete', function () {
                var id = $(this).data("id");
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
                            url: "{{ route('admin.lab.index') }}" + '/' + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    showToast(data.message, 'success');
                                    setTimeout(() => { window.location.reload(); }, 800);
                                } else {
                                    Swal.fire("Gagal !!", data.message, "error");
                                }
                            },
                            error: function (data) {
                                Swal.fire("Gagal !!", data.responseText, "error");
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-staradmin>
