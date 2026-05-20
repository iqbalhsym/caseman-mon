@section('title', 'Ruangan & Lokasi')

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
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen Lokasi</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                        
                        {{-- Card Pencarian (Toolbar) --}}
                        <div class="row compact-margin"> {{-- Menggunakan class custom agar lebih rapat --}}
                            <div class="col-12 stretch-card"> {{-- Menghilangkan grid-margin bawaan --}}
                                <div class="card shadow-sm">
                                    <div class="card-body search-card-body"> {{-- Menggunakan padding lebih tipis --}}
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="d-flex gap-2 align-items-center">
                                                {{-- Input Group dengan Logo Lensa --}}
                                                <div class="input-group" style="width: 280px;">
                                                    <span class="input-group-text input-group-text-custom">
                                                        <i class="mdi mdi-magnify text-muted"></i>
                                                    </span>
                                                    <input type="text" id="user-search" class="form-control search-input-custom" placeholder="Cari nama ruangan atau lantai">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add">
                                                <i class="mdi mdi-map-marker-plus"></i> Tambah Lokasi
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
                                                        <th>Nama Ruangan</th>
                                                        <th>Lantai</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="user-table-body">
                                                    @forelse ($data as $item)
                                                        <tr class="user-row" data-id="{{ $item->id }}">
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $item->nama }}</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $item->lantai }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="{{ $item->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="{{ $item->id }}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="3" class="text-center py-4">Belum ada data lokasi</td></tr>
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

    {{-- Sisanya (Modal dan Script) tetap sama seperti kode awal Anda --}}

    <!-- Bootstrap 5 Modal -->
    <div class="modal fade" id="add-lokasi-modal" tabindex="-1" aria-labelledby="addLokasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLokasiModalLabel">Tambah Ruangan & Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        
                        <div class="form-group">
                            <label for="nama">Nama Ruangan</label>
                            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="lantai">Lantai</label>
                            <input type="text" class="form-control" id="lantai" name="lantai" autocomplete="off">
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
            const lokasiModal = new bootstrap.Modal(document.getElementById('add-lokasi-modal'));

            $('#add').click(function () {
                $('#addLokasiModalLabel').text('Tambah Ruangan & Lokasi');
                $('#nama').val('');
                $('#lantai').val('');
                $('#product_id').val('');
                lokasiModal.show();
                setTimeout(() => $('#nama').focus(), 500);
            });

            $('#save-btn').click(function (e) {
                e.preventDefault();

                $.ajax({
                    data: $('#formInput').serialize(),
                    url: "{{ route('admin.lokasi.store') }}",
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
                        lokasiModal.hide();

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
                    url: "{{ route('admin.lokasi.create') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#user-table-body').html('');

                        data.data.forEach(element => {
                            const rowHTML = `
                                <tr class="user-row" data-id="${element.id}">
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <h6>${element.nama}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${element.lantai}</td>
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
                    url: "{{ route('admin.lokasi.index') }}" + '/' + product_id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#addLokasiModalLabel').text('Edit Ruangan & Lokasi');
                        $('#nama').val(data.data.nama);
                        $('#lantai').val(data.data.lantai);
                        $('#product_id').val(data.data.id);
                        lokasiModal.show();
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
                            url: "{{ route('admin.lokasi.store') }}" + '/' + id,
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
                    const lantai = $tr.find('td').eq(1).text().toLowerCase();

                    let matches = true;
                    if (q && !(name.includes(q) || lantai.includes(q))) matches = false;

                    $tr.toggle(matches);
                });
            }

            $('#user-search').on('input', applyFilters);

        </script>
    @endpush

</x-staradmin>
