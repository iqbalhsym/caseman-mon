@section('title', 'Data Obat')

<x-staradmin>
    <style>
        .search-card-body {
            padding: 0.75rem 1.25rem !important; /* Membuat card lebih tipis */
        }
        .compact-margin {
            margin-bottom: 0.5rem !important; /* Merapatkan jarak ke tabel */
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
                                            <table class="table table-hover select-table table-condensed">
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
                                                    @forelse ($data as $item)
                                                        <tr class="obat-row" data-id="{{ $item->id }}">
                                                            <td>{{ $item->f_nf }}</td>
                                                            <td>{{ $item->nama_generik }}</td>
                                                            <td><span class="badge badge-info">{{ $item->kode_item }}</span></td>
                                                            <td><h6 class="mb-0">{{ $item->nama_item }}</h6></td>
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
                                                        <tr><td colspan="6" class="text-center py-4">Belum ada data obat</td></tr>
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

            $('#add').click(function () {
                $('#addObatModalLabel').text('Tambah Data Obat');
                $('#f_nf').val('');
                $('#nama_generik').val('');
                $('#kode_item').val('');
                $('#nama_item').val('');
                $('#warna').val('');
                $('#product_id').val('');
                obatModal.show();
                setTimeout(() => $('#f_nf').focus(), 500);
            });

            $('#save-btn').click(function (e) {
                e.preventDefault();

                $.ajax({
                    data: $('#formInput').serialize(),
                    url: "{{ route('admin.obat.store') }}",
                    type: "POST",
                    dataType: 'json',
                    beforeSend: function() {
                        $('#save-btn').prop('disabled', true)
                        $('#save-btn').html('Loading <i class="mdi mdi-loading mdi-spin"></i>')
                    },
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        showToast(data.message)
                        $('#formInput').trigger("reset");
                        obatModal.hide();

                        $('#obat-table-body').html('');
                        getDataObat();
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                    },
                    complete: function() {
                        $('#save-btn').prop('disabled', false)
                        $('#save-btn').html('Simpan')
                    }
                });
            });

            // Import feature
            const importModal = new bootstrap.Modal(document.getElementById('import-modal'));
            
            $('#btn-import').click(function () {
                $('#file').val('');
                importModal.show();
            });

            $('#formImport').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: "{{ route('admin.obat.import') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btn-import-save').prop('disabled', true);
                        $('#btn-import-save').html('Loading <i class="mdi mdi-loading mdi-spin"></i>');
                    },
                    success: function (data) {
                        if (data.status === 'error') {
                            showToast(data.message, 'error');
                            return false;
                        }
                        showToast(data.message);
                        importModal.hide();
                        getDataObat();
                    },
                    error: function (data) {
                        showToast("Terjadi kesalahan sistem", 'error');
                    },
                    complete: function() {
                        $('#btn-import-save').prop('disabled', false);
                        $('#btn-import-save').html('Import');
                    }
                });
            });

            function getDataObat (){
                $.ajax({
                    url: "{{ route('admin.obat.create') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#obat-table-body').html('');

                        data.data.forEach(element => {
                            let badgeWarna = '<span class="badge bg-secondary">-</span>';
                            if (element.warna === 'hijau') badgeWarna = '<span class="badge bg-success">Hijau</span>';
                            if (element.warna === 'kuning') badgeWarna = '<span class="badge bg-warning text-dark">Kuning</span>';
                            if (element.warna === 'merah') badgeWarna = '<span class="badge bg-danger">Merah</span>';

                            const rowHTML = `
                                <tr class="obat-row" data-id="${element.id}">
                                    <td>${element.f_nf || '-'}</td>
                                    <td>${element.nama_generik || '-'}</td>
                                    <td><span class="badge badge-info">${element.kode_item || '-'}</span></td>
                                    <td><h6 class="mb-0">${element.nama_item || '-'}</h6></td>
                                    <td>${badgeWarna}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${element.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${element.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                    </td>
                                </tr>
                            `;
                            $('#obat-table-body').append(rowHTML);
                        })
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                    },
                });
            }

            $('body').on('click', '.edit', function () {
                var product_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.obat.index') }}" + '/' + product_id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#addObatModalLabel').text('Edit Data Obat');
                        $('#f_nf').val(data.data.f_nf);
                        $('#nama_generik').val(data.data.nama_generik);
                        $('#kode_item').val(data.data.kode_item);
                        $('#nama_item').val(data.data.nama_item);
                        $('#warna').val(data.data.warna);
                        $('#product_id').val(data.data.id);
                        obatModal.show();
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                    }
                });
            });

            $('body').on('click', '.delete', function () {
                var id = $(this).data("id");

                Swal.fire({
                    title: 'Yakin Ingin Menghapus ?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f3bb3',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.obat.store') }}" + '/' + id,
                            success: function (data) {
                                (data.status == 'success' ? Swal.fire("Berhasil !!", data.message, "success") : Swal.fire("Gagal !!", data.message, "error"))
                                getDataObat();
                            },
                            error: function (data) {
                                Swal.fire("Gagal !!", data.responseText, "error")
                            }
                        });
                    }
                })
            });

            // Search filters
            function applyFilters(){
                const q = $('#obat-search').val().toLowerCase();
                const color = $('#obat-color-filter').val().toLowerCase();

                $('.obat-row').each(function(){
                    const $tr = $(this);
                    const generik = $tr.find('td').eq(1).text().toLowerCase();
                    const kode = $tr.find('td').eq(2).text().toLowerCase();
                    const nama = $tr.find('td').eq(3).text().toLowerCase();
                    const warnaCell = $tr.find('td').eq(4).text().toLowerCase().trim();

                    let matches = true;
                    if (q && !(kode.includes(q) || nama.includes(q) || generik.includes(q))) matches = false;
                    
                    if (color && color !== "") {
                        if (warnaCell !== color) {
                            matches = false;
                        }
                    }

                    $tr.toggle(matches);
                });
            }

            $('#obat-search').on('input', applyFilters);
            $('#obat-color-filter').on('change', applyFilters);

        </script>
    @endpush

</x-staradmin>
