@section('title', 'Pengguna')

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
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Manajemen Pengguna</a>
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
                                                    <input type="text" id="user-search" class="form-control search-input-custom" placeholder="Cari nama, username atau email">
                                                </div>
                                                <select id="filter-role" class="form-control form-control-sm" style="width: 130px; font-size: 0.85rem;">
                                                    <option value="">Semua Role</option>
                                                    @foreach ($role as $r)
                                                        <option value="{{ $r->id }}">{{ $r->alias }}</option>
                                                    @endforeach
                                                </select>
                                                <select id="filter-status" class="form-control form-control-sm" style="width: 130px; font-size: 0.85rem;">
                                                    <option value="">Semua Status</option>
                                                    <option value="active">Aktif</option>
                                                    <option value="inactive">Nonaktif</option>
                                                </select>
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
                                                        <th>Pengguna</th>
                                                        <th>Username</th>
                                                        <th>No. Telegram</th>
                                                        <th>Role</th>
                                                        <th>Lokasi</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="user-table-body">
                                                    @forelse ($data as $item)
                                                        <tr class="user-row" data-id="{{ $item->id }}" data-role="{{ $item->role_id }}" data-status="{{ $item->status }}">
                                                            <td>
                                                                <div class="d-flex">
                                                                    <div>
                                                                        <h6>{{ $item->name }}</h6>
                                                                        <p>{{ $item->email }}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $item->username }}</td>
                                                            <td>{{ $item->phone }}</td>
                                                            <td>
                                                                <div class="badge badge-opacity-info">{{ $item->role->alias ?? '-' }}</div>
                                                            </td>
                                                            <td>
                                                                {{ $item->lokasi ? ($item->lokasi->nama . ' Lt. ' . $item->lokasi->lantai) : '-' }}
                                                            </td>
                                                            <td>
                                                                <div class="badge badge-opacity-{{ $item->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($item->status) }}</div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="{{ $item->id }}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                                                @if(Auth::user()->role_id == 1)
                                                                <button type="button" class="btn btn-sm btn-danger text-white delete" data-id="{{ $item->id }}" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="7" class="text-center py-4">Belum ada data pengguna</td></tr>
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
    <div class="modal fade" id="add-user-modal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <input type="hidden" id="create" name="create">
                        
                        <div class="form-group">
                            <label for="role">Hak Akses</label>
                            <select class="form-control" id="role" name="role" @if(Auth::user()->role_id != 1) disabled @endif>
                                @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->alias }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lokasi_id">Lokasi Ruangan (Khusus Akun Ruangan)</label>
                            <select class="form-control" id="lokasi_id" name="lokasi_id" @if(Auth::user()->role_id != 1) disabled @endif>
                                <option value="">Tidak ada/Semua Lokasi</option>
                                @foreach ($lokasi as $l)
                                    <option value="{{ $l->id }}">{{ $l->nama }} Lt. {{ $l->lantai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" @if(Auth::user()->role_id != 1) readonly @endif>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" @if(Auth::user()->role_id != 1) readonly @endif>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="off" @if(Auth::user()->role_id != 1) readonly @endif>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telegram</label>
                            <input type="text" class="form-control" id="phone" name="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" @if(Auth::user()->role_id != 1) disabled @endif>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
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
            // Role checking for JS
            const currentUserRole = {{ Auth::user()->role_id }};
            
            // Bootstrap modal instance
            const userModal = new bootstrap.Modal(document.getElementById('add-user-modal'));

            $('#add').click(function () {
                $('#addUserModalLabel').text('Tambah Pengguna Baru');
                $('#username').val('');
                $('#nama').val('');
                $('#email').val('');
                $('#phone').val('');
                $('#status').val('active');
                $('#product_id').val('');
                $('#create').val('create');
                $('#lokasi_id').val('');
                userModal.show();
            });

            $('#save-btn').click(function (e) {
                e.preventDefault();

                $.ajax({
                    data: $('#formInput').serialize(),
                    url: "{{ route('admin.user.store') }}",
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
                        userModal.hide();

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
                    url: "{{ route('admin.user.create') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#user-table-body').html('');

                        data.data.forEach(element => {
                            let statusBadge = element.status == 'active' ? 'success' : 'danger';
                            
                            const rowHTML = `
                                <tr class="user-row" data-id="${element.id}" data-role="${element.role_id}" data-status="${element.status}">
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <h6>${element.name}</h6>
                                                <p>${element.email}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${element.username}</td>
                                    <td>${element.phone}</td>
                                    <td><div class="badge badge-opacity-info">${element.role_alias || '-'}</div></td>
                                    <td>${element.lokasi_name || '-'}</td>
                                    <td><div class="badge badge-opacity-${statusBadge}">${element.status.charAt(0).toUpperCase() + element.status.slice(1)}</div></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary text-white edit" data-id="${element.id}" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                        ${currentUserRole == 1 ? `<button type="button" class="btn btn-sm btn-danger text-white delete" data-id="${element.id}" title="Hapus"><i class="mdi mdi-delete"></i></button>` : ''}
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
                    url: "{{ route('admin.user.index') }}" + '/' + product_id + '/edit',
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        $('#addUserModalLabel').text('Edit Pengguna');
                        $('#nama').val(data.data.name);
                        $('#username').val(data.data.username);
                        $('#email').val(data.data.email);
                        $('#phone').val(data.data.phone);
                        $('#status').val(data.data.status);
                        $('#product_id').val(data.data.id);
                        $('#create').val('update');
                        $('#role').val(data.data.role_id);
                        $('#lokasi_id').val(data.data.lokasi_id || '');
                        userModal.show();
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
                            url: "{{ route('admin.user.store') }}" + '/' + id,
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



            // Search & filters (client-side filtering)
            function applyFilters(){
                const q = $('#user-search').val().toLowerCase();
                const role = $('#filter-role').val();
                const status = $('#filter-status').val();

                // table rows
                $('.user-row').each(function(){
                    const $tr = $(this);
                    const name = $tr.find('td').eq(0).text().toLowerCase();
                    const username = $tr.find('td').eq(1).text().toLowerCase();
                    const email = $tr.find('td').eq(0).text().toLowerCase(); // email is inside td 0 now
                    const r = $tr.data('role') + '';
                    const s = $tr.data('status') + '';

                    let matches = true;
                    if (q && !(name.includes(q) || username.includes(q) || email.includes(q))) matches = false;
                    if (role && role !== r) matches = false;
                    if (status && status !== s) matches = false;

                    $tr.toggle(matches);
                });
            }

            $('#user-search').on('input', applyFilters);
            $('#filter-role').on('change', applyFilters);
            $('#filter-status').on('change', applyFilters);

        </script>
    @endpush

</x-staradmin>
