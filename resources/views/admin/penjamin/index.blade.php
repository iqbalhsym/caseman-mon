@section('title', 'Penjamin')

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
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen Penjamin</a>
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
                                                    <input type="text" id="user-search" class="form-control search-input-custom" placeholder="Cari nama penjamin">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add">
                                                <i class="mdi mdi-shield-plus"></i> Tambah Penjamin
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
                                            <table class="table table-hover select-table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Penjamin</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="user-table-body">
                                                    @forelse ($data as $item)
                                                        <tr class="user-row" data-id="{{ $item->id }}">
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div>
                                                                        <h6>{{ $item->nama }}</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $item->keterangan ?? '-' }}</td>
                                                            <td>
                                                                @if ($item->status == 'ya')
                                                                    <div class="badge badge-opacity-success">Aktif</div>
                                                                @else
                                                                    <div class="badge badge-opacity-danger">Tidak Aktif</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="{{ $item->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="{{ $item->id }}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="4" class="text-center py-4">Belum ada data penjamin</td></tr>
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
    <div class="modal fade" id="add-penjamin-modal" tabindex="-1" aria-labelledby="addPenjaminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPenjaminModalLabel">Tambah Penjamin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        
                        <div class="form-group">
                            <label for="nama">Nama Penjamin</label>
                            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="ya">Aktif</option>
                                <option value="tidak">Tidak Aktif</option>
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

    @push('script')
        <script>
            const penjaminModal = new bootstrap.Modal(document.getElementById('add-penjamin-modal'));

            $('#add').click(function () {
                $('#addPenjaminModalLabel').text('Tambah Penjamin');
                $('#nama').val('');
                $('#keterangan').val('');
                $('#status').val('ya');
                $('#product_id').val('');
                penjaminModal.show();
                setTimeout(() => $('#nama').focus(), 500);
            });

            $('#save-btn').click(function (e) {
                e.preventDefault();

                $.ajax({
                    data: $('#formInput').serialize(),
                    url: "{{ route('admin.penjamin.store') }}",
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
                        penjaminModal.hide();

                        $('#user-table-body').html('');
                        getDataUser();
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

            function getDataUser (){
                $.ajax({
                    url: "{{ route('admin.penjamin.create') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#user-table-body').html('');

                        data.data.forEach(element => {
                            let statusBadge = element.status == 'ya' ? '<div class="badge badge-opacity-success">Aktif</div>' : '<div class="badge badge-opacity-danger">Tidak Aktif</div>';
                            const rowHTML = `
                                <tr class="user-row" data-id="${element.id}">
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <h6>${element.nama}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${element.keterangan || '-'}</td>
                                    <td>${statusBadge}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${element.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${element.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                    </td>
                                </tr>
                            `;
                            $('#user-table-body').append(rowHTML);
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
                    url: "{{ route('admin.penjamin.index') }}" + '/' + product_id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#addPenjaminModalLabel').text('Edit Penjamin');
                        $('#nama').val(data.data.nama);
                        $('#keterangan').val(data.data.keterangan);
                        $('#status').val(data.data.status);
                        $('#product_id').val(data.data.id);
                        penjaminModal.show();
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
                            url: "{{ route('admin.penjamin.store') }}" + '/' + id,
                            success: function (data) {
                                (data.status == 'success' ? Swal.fire("Berhasil !!", data.message, "success") : Swal.fire("Gagal !!", data.message, "error"))
                                getDataUser();
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
                const q = $('#user-search').val().toLowerCase();

                $('.user-row').each(function(){
                    const $tr = $(this);
                    const name = $tr.find('td').eq(0).text().toLowerCase();

                    let matches = true;
                    if (q && !name.includes(q)) matches = false;

                    $tr.toggle(matches);
                });
            }

            $('#user-search').on('input', applyFilters);

        </script>
    @endpush

</x-staradmin>
