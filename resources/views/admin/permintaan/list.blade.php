@section('title', 'Daftar Permintaan')

<x-staradmin>

    @push('style')
        <style>
            .filter-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 20px;
            }
            .filter-btn {
                background: #fff;
                border: 1px solid #dee2e6;
                padding: 8px 16px;
                border-radius: 20px;
                color: #6c757d;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            .filter-btn:hover {
                background: #f8f9fa;
                color: #1f3bb3;
                border-color: #1f3bb3;
            }
            .filter-btn.active {
                background: #1f3bb3;
                color: white;
                border-color: #1f3bb3;
                box-shadow: 0 4px 6px rgba(31, 59, 179, 0.2);
            }
            .submission-card {
                margin-bottom: 20px;
                border-radius: 8px;
                border: 1px solid #eee;
                border-left: 5px solid #ccc; /* Default border */
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                transition: transform 0.2s ease-in-out;
                background: #fff;
            }
            .submission-card.status-menunggu { border-left-color: #ffc107; }
            .submission-card.status-disetujui { border-left-color: #198754; }
            .submission-card.status-konfirmasi { border-left-color: #fd7e14; }
            .submission-card.status-ditolak { border-left-color: #dc3545; }
            .submission-card.status-batal { border-left-color: #000000; }

            .submission-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            }
            .submission-card .card-header {
                background: transparent;
                border-bottom: 1px solid #f3f3f3;
                padding: 12px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .submission-card .card-body {
                padding: 12px 15px;
            }
            .patient-name {
                font-size: 0.85rem;
                font-weight: 800;
                color: #333;
                text-transform: uppercase;
                margin-bottom: 0;
            }
            .submission-date {
                font-size: 0.75rem;
                color: #888;
                display: block;
            }
            .info-row {
                display: flex;
                margin-bottom: 4px;
                font-size: 0.78rem;
                line-height: 1.4;
            }
            .info-row .label {
                width: 100px;
                color: #777;
                flex-shrink: 0;
            }
            .info-row .value {
                color: #333;
                font-weight: 500;
                flex-grow: 1;
                word-wrap: break-word;
                overflow-wrap: break-word;
                min-width: 0;
            }
            .info-row .value .badge {
                white-space: normal;
                text-align: left;
                line-height: 1.4;
            }
            .card-footer {
                background: #fcfcfc;
                border-top: 1px solid #eee;
                padding: 10px;
                display: flex;
                justify-content: center;
                gap: 5px;
                flex-wrap: wrap;
            }
            .badge-status {
                font-size: 0.65rem;
                padding: 4px 10px;
                border-radius: 10px;
            }
            .bg-warning {
                background-color: #ffc107 !important;
                color: #000 !important;
            }
            .bg-orange {
                background-color: #fd7e14 !important;
                color: #fff !important;
            }
            .bg-success {
                background-color: #198754 !important;
                color: #fff !important;
            }
            .bg-danger {
                background-color: #dc3545 !important;
                color: #fff !important;
            }
            .bg-dark {
                background-color: #000000 !important;
                color: #fff !important;
            }
            
            @media (max-width: 768px) {
                .filter-nav {
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    padding-bottom: 10px;
                }
                .filter-btn {
                    white-space: nowrap;
                }
                .info-row {
                    flex-direction: column;
                }
                .info-row .label {
                    margin-bottom: 2px;
                }
            }
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

        .submission-card {
            position: relative;
        }

        .btn-delete-floating {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 22px;
            height: 22px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
            font-size: 12px;
            line-height: 1;
        }
        .btn-delete-floating:hover {
            background: #b02a37;
            transform: scale(1.1);
        }
        </style>
    @endpush

    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Review Permintaan</a>
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
                                        <div class="d-flex align-items-center">
                                            <div class="input-group" style="max-width: 380px; flex: 1;">
                                                <span class="input-group-text input-group-text-custom">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                                <input type="search" class="form-control search-input-custom" id="search-input" placeholder="Cari nama, No. RM, atau Ruangan...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Filters --}}
                        <nav class="filter-nav">
                            <button class="filter-btn active" data-filter="semua">Semua</button>
                            <button class="filter-btn" data-filter="menunggu">Menunggu</button>
                            <button class="filter-btn" data-filter="konfirmasi">Konfirmasi</button>
                            <button class="filter-btn" data-filter="disetujui">Disetujui</button>
                            <button class="filter-btn" data-filter="ditolak">Ditolak</button>
                            <button class="filter-btn" data-filter="batal">Batal</button>
                        </nav>

                        {{-- Submissions List --}}
                        <div class="row" id="submission-list">
                            <!-- Diisi oleh Javascript -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Persetujuan -->
    <div class="modal fade" id="add-user-modal" tabindex="-1" aria-labelledby="add-user-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-user-modal-title">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInput">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <input type="hidden" id="paket_index" name="paket_index">
                        
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Masukkan catatan..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="hari">Jumlah Hari (Expired)</label>
                            <input type="text" class="form-control" id="hari" name="hari" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal">Tanggal Mulai (Expired)</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="save-btn" class="btn btn-primary text-white">Simpan Persetujuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Batal/Tolak -->
    <div class="modal fade" id="modalBatal" tabindex="-1" aria-labelledby="headerBatal" aria-hidden="true">
        <div class="modal-dialog modal-sm-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="headerBatal">Alasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formInputBatal">
                    <div class="modal-body">
                        <input type="hidden" id="id_batal" name="product_id">
                        <input type="hidden" id="status_angka" name="status_angka">
                        <input type="hidden" id="batal_paket_index" name="paket_index">

                        <div class="form-group">
                            <label for="catatan_batal">Catatan / Alasan</label>
                            <textarea class="form-control" id="catatan_batal" name="catatan_batal" rows="3" placeholder="Masukkan catatan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="save-btn-batal" class="btn btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const currentUserRole = {{ Auth::user()->role_id }};
            const initialSubmissions = @json($datas);
            let submissions = Array.isArray(initialSubmissions) ? initialSubmissions.slice() : [];

            const submissionListContainer = document.getElementById('submission-list');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('search-input');

            // Modals
            const confirmModal = new bootstrap.Modal(document.getElementById('add-user-modal'));
            const cancelModal = new bootstrap.Modal(document.getElementById('modalBatal'));

            function debounce(fn, delay) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            function fetchSearch(q) {
                return $.getJSON("{{ route('admin.permintaan.search') }}", { q: q });
            }

            function displayHTML(text) {
                if (!text) return '-';
                return text.replace(/\n/g, '<br>');
            }

            function renderSubmissions(filter = 'semua', searchQuery = '') {
                submissionListContainer.innerHTML = '';

                const statusMap = {
                    disetujui: { text: 'Disetujui', badge: 'success' },
                    ditolak: { text: 'Ditolak', badge: 'danger' },
                    menunggu: { text: 'Menunggu', badge: 'warning' },
                    konfirmasi: { text: 'Dikonfirmasi', badge: 'orange' },
                    batal: { text: 'Dibatalkan', badge: 'dark' },
                };

                const lowerCaseQuery = searchQuery.toLowerCase().trim();

                const filteredSubmissions = submissions.filter(s => {
                    const statusMatch = (filter === 'semua') || (s.status === filter);
                    const searchMatch = (s.nama && String(s.nama).toLowerCase().includes(lowerCaseQuery)) ||
                                        (s.no_rm && String(s.no_rm).toLowerCase().includes(lowerCaseQuery)) ||
                                        (s.lokasi && String(s.lokasi).toLowerCase().includes(lowerCaseQuery));
                    return statusMatch && searchMatch;
                });

                if (filteredSubmissions.length === 0) {
                    submissionListContainer.innerHTML = `
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="mdi mdi-file-hidden" style="font-size: 3rem; color: #ccc;"></i>
                                <h5 class="mt-3 text-muted">Data tidak ditemukan</h5>
                            </div>
                        </div>
                    `;
                    return;
                }

                filteredSubmissions.forEach(item => {
                    const statusInfo = statusMap[item.status] || { text: item.status, badge: 'light' };

                    let tgLink = item.phone ? `https://t.me/+${item.phone}` : '#';
                    let tgTarget = item.phone ? '_blank' : '_self';
                    let tgOnclick = item.phone ? '' : 'onclick="alert(\'Nomor Telegram belum disetel di profil pengguna ini!\')"';

                    let historyHTML = `
                        <div class="text-muted" style="font-size: 0.7rem;">
                            <i class="mdi mdi-account-check text-success me-1"></i> ${item.status2} oleh: <strong>${item.manager || '-'}</strong>
                            <br>
                            <i class="mdi mdi-account text-primary me-1"></i> Pengaju: <strong>${item.pengaju || '-'}</strong>
                        </div>
                    `;

                    let editButtonHTML = item.can_edit ? `
                        <a href="/admin/permintaan/${item.id}/edit" class="btn btn-xs btn-outline-warning edit">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                    ` : '';

                    let cardFooter = '';
                    if (item.status === 'menunggu') {
                        cardFooter = `
                            <div class="card-footer justify-content-between align-items-center flex-wrap gap-2">
                                ${historyHTML}
                                <div class="d-flex gap-1 flex-wrap align-items-center">
                                    <a href="${tgLink}" target="${tgTarget}" ${tgOnclick} class="btn btn-xs btn-info text-white"><i class="mdi mdi-telegram"></i> Chat</a>
                                    ${editButtonHTML}
                                    ${!(item.detail_paket && item.detail_paket.length > 0) ? `
                                        <button class="btn btn-xs btn-danger text-white btn-reject" data-id="${item.id}" data-paket="">Tolak</button>
                                        <button class="btn btn-xs btn-dark text-white btn-batal" data-id="${item.id}" data-paket="">Batal</button>
                                        <button class="btn btn-xs bg-orange text-white btn-confirm" data-id="${item.id}" data-paket="">Konfirmasi</button>
                                        <button class="btn btn-xs btn-success text-white btn-approve" data-id="${item.id}" data-paket="">Terima</button>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    } else if (item.status == 'konfirmasi') {
                        cardFooter = `
                            <div class="card-footer justify-content-between align-items-center flex-wrap gap-2">
                                ${historyHTML}
                                <div class="d-flex gap-1 flex-wrap align-items-center">
                                    <a href="${tgLink}" target="${tgTarget}" ${tgOnclick} class="btn btn-xs btn-info text-white"><i class="mdi mdi-telegram"></i> Chat</a>
                                    ${editButtonHTML}
                                    ${!(item.detail_paket && item.detail_paket.length > 0) ? `
                                        <button class="btn btn-xs btn-danger text-white btn-reject" data-id="${item.id}" data-paket="">Tolak</button>
                                        <button class="btn btn-xs btn-dark text-white btn-batal" data-id="${item.id}" data-paket="">Batal</button>
                                        <button class="btn btn-xs btn-success text-white btn-approve" data-id="${item.id}" data-paket="">Terima</button>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    } else if (item.status == 'disetujui') {
                        cardFooter = `
                            <div class="card-footer justify-content-between align-items-center flex-wrap gap-2">
                                ${historyHTML}
                                ${editButtonHTML}
                            </div>
                        `;
                    } else if (item.status == 'ditolak' || item.status == 'batal') {
                        cardFooter = `
                            <div class="card-footer justify-content-between align-items-center flex-wrap gap-2">
                                ${historyHTML}
                                <div class="d-flex gap-1 flex-wrap align-items-center">
                                    ${editButtonHTML}
                                    <button class="btn btn-xs btn-outline-warning" id="btn-edit-permintaan" data-id="${item.id}">
                                        <i class="mdi mdi-pencil"></i> Ubah Status
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        cardFooter = `
                            <div class="card-footer justify-content-between align-items-center flex-wrap gap-2">
                                ${historyHTML}
                                ${editButtonHTML}
                            </div>
                        `;
                    }

                    let cardNote = '';
                    if (item.catatan_diterima !== null && item.jumlah_hari !== null && !(item.detail_paket && item.detail_paket.length > 0)) {
                        cardNote = `
                            <div class="info-row mt-3 pt-3 border-top">
                                <span class="label text-info">Persetujuan:</span>
                                <span class="value fw-bold text-dark">${item.jumlah_hari} Hari (${item.tanggal_mulai_expired} s/d ${item.tanggal_berakhir_expired})</span>
                            </div>
                        `;
                    }

                    let fileLinks = '';
                    if (item.file) fileLinks += `<a href="${item.file}" target="_blank" class="badge badge-opacity-primary me-2"><i class="mdi mdi-download"></i> File 1</a>`;
                    if (item.file2) fileLinks += `<a href="${item.file2}" target="_blank" class="badge badge-opacity-primary me-2"><i class="mdi mdi-download"></i> File 2</a>`;
                    if (item.file3) fileLinks += `<a href="${item.file3}" target="_blank" class="badge badge-opacity-primary me-2"><i class="mdi mdi-download"></i> File 3</a>`;

                    const cardHTML = `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card submission-card status-${item.status} h-100">
                                <div class="card-header">
                                    ${currentUserRole == 1 ? `<div class="btn-delete-floating btn-delete-permintaan" data-id="${item.id}" title="Hapus Permintaan"><i class="mdi mdi-close"></i></div>` : ''}
                                    <div class="header-info">
                                        <p class="patient-name">${item.nama}</p>
                                        <span class="submission-date">Diajukan: ${new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })} ${item.jam || ''}</span>
                                    </div>
                                    <div class="badge bg-${statusInfo.badge} badge-status">${statusInfo.text}</div>
                                </div>
                                <div class="card-body">
                                    <div class="info-row">
                                        <span class="label">Registered:</span>
                                        <span class="value">${item.tanggal_masuk || '-'}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">No. RM:</span>
                                        <span class="value">${item.no_rm}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Ruangan:</span>
                                        <span class="value">${item.lokasi || '-'}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Jaminan Pasien:</span>
                                        <span class="value">${item.jaminan || '-'}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Jam Respon:</span>
                                        <span class="value">${item.jam_respon || '-'}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="label">Diagnosis:</span>
                                        <span class="value">${displayHTML(item.diagnosis)}</span>
                                    </div>
                                    <hr>
                                    <h6 class="text-primary mb-2 mt-3">Detail Paket</h6>
                                    ${(item.detail_paket && item.detail_paket.length > 0) ? item.detail_paket.map((paket, idx) => {
                                        let paketStatus = paket.status || item.status;
                                        let paketStatusInfo = statusMap[paketStatus] || { text: paketStatus, badge: 'light' };
                                        
                                        let pCatatan = '';
                                        if (paket.catatan) {
                                            pCatatan = `
                                            <div class="info-row mt-1 text-muted" style="font-size: 0.8rem;">
                                                <span class="label" style="min-width:80px">Catatan:</span>
                                                <span class="value">${displayHTML(paket.catatan)}</span>
                                            </div>`;
                                        }
                                        
                                        let paketActions = '';
                                        if (paketStatus === 'menunggu') {
                                            paketActions = `
                                                <div class="mt-2 pt-2 border-top">
                                                    <button class="btn btn-xs btn-danger text-white btn-reject" data-id="${item.id}" data-paket="${idx}">Tolak</button>
                                                    <button class="btn btn-xs btn-dark text-white btn-batal" data-id="${item.id}" data-paket="${idx}">Batal</button>
                                                    <button class="btn btn-xs bg-orange text-white btn-confirm" data-id="${item.id}" data-paket="${idx}">Konfirmasi</button>
                                                    <button class="btn btn-xs btn-success text-white btn-approve" data-id="${item.id}" data-paket="${idx}">Terima</button>
                                                </div>
                                            `;
                                        } else if (paketStatus === 'konfirmasi') {
                                            paketActions = `
                                                <div class="mt-2 pt-2 border-top">
                                                    <span class="badge bg-${paketStatusInfo.badge} me-2">${paketStatusInfo.text}</span>
                                                    ${pCatatan}
                                                    <div class="mt-2">
                                                        <button class="btn btn-xs btn-danger text-white btn-reject" data-id="${item.id}" data-paket="${idx}">Tolak</button>
                                                        <button class="btn btn-xs btn-dark text-white btn-batal" data-id="${item.id}" data-paket="${idx}">Batal</button>
                                                        <button class="btn btn-xs btn-success text-white btn-approve" data-id="${item.id}" data-paket="${idx}">Terima</button>
                                                    </div>
                                                </div>
                                            `;
                                        } else {
                                            let pExpired = '';
                                            if (paketStatus === 'disetujui' && paket.jumlah_hari) {
                                                pExpired = `
                                                <div class="info-row mt-1 text-info" style="font-size: 0.8rem;">
                                                    <span class="label" style="min-width:80px">Persetujuan:</span>
                                                    <span class="value">${paket.jumlah_hari} Hari (${paket.tanggal_mulai_expired} s/d ${paket.tanggal_berakhir_expired})</span>
                                                </div>`;
                                            }
                                            paketActions = `
                                                <div class="mt-2 pt-2 border-top">
                                                    <span class="badge bg-${paketStatusInfo.badge} me-2">${paketStatusInfo.text}</span>
                                                    ${pCatatan}
                                                    ${pExpired}
                                                </div>
                                            `;
                                        }

                                        return `
                                        <div class="p-2 mb-2 bg-light border rounded">
                                            <strong>Paket ${idx + 1}</strong>
                                            <div class="info-row mt-1">
                                                <span class="label" style="min-width:80px">Kategori:</span>
                                                <span class="value text-primary font-weight-bold">${paket.kategori ? paket.kategori.replace(/_/g, ' ') : '-'}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="label" style="min-width:80px">Keterangan:</span>
                                                <span class="value">${displayHTML(paket.keterangan)}</span>
                                            </div>
                                            ${paket.detail_obat ? `
                                            <div class="info-row">
                                                <span class="label" style="min-width:80px">Detail Obat:</span>
                                                <span class="value">${paket.detail_obat}</span>
                                            </div>
                                            ` : ''}
                                            <div class="info-row">
                                                <span class="label" style="min-width:80px">Indikasi:</span>
                                                <span class="value">${displayHTML(paket.indikasi)}</span>
                                            </div>
                                            ${paketActions}
                                        </div>
                                        `;
                                    }).join('') : `
                                        <div class="info-row">
                                            <span class="label">Kategori:</span>
                                            <span class="value text-primary font-weight-bold">${item.kategori ? item.kategori.replace(/_/g, ' ') : '-'}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Keterangan:</span>
                                            <span class="value">${displayHTML(item.keterangan)}</span>
                                        </div>
                                        ${item.detail_obat ? `
                                        <div class="info-row">
                                            <span class="label">Detail Obat:</span>
                                            <span class="value">${item.detail_obat}</span>
                                        </div>
                                        ` : ''}
                                        <div class="info-row">
                                            <span class="label">Indikasi:</span>
                                            <span class="value">${displayHTML(item.indikasi)}</span>
                                        </div>
                                    `}
                                    <hr>
                                    ${!(item.detail_paket && item.detail_paket.length > 0) ? `
                                    <div class="info-row mt-3">
                                        <span class="label">Catatan:</span>
                                        <span class="value">${displayHTML(item.catatan_diterima)}</span>
                                    </div>
                                    ` : ''}
                                    
                                    ${fileLinks ? `
                                    <div class="info-row mt-2">
                                        <span class="label">File:</span>
                                        <span class="value">${fileLinks}</span>
                                    </div>
                                    ` : ''}

                                    ${cardNote}
                                </div>
                                ${cardFooter}
                            </div>
                        </div>
                    `;
                    submissionListContainer.insertAdjacentHTML('beforeend', cardHTML);
                });
            }

            function updateDisplay() {
                const activeFilterBtn = document.querySelector('.filter-btn.active');
                const activeFilter = activeFilterBtn ? activeFilterBtn.getAttribute('data-filter') : 'semua';
                renderSubmissions(activeFilter, searchInput.value);
            }

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    sessionStorage.setItem('activeFilter', button.getAttribute('data-filter'));
                    updateDisplay();
                });
            });

            const handleSearch = debounce(function () {
                const q = searchInput.value.trim();
                if (q.length === 0) {
                    submissions = Array.isArray(initialSubmissions) ? initialSubmissions.slice() : [];
                    updateDisplay();
                    return;
                }

                fetchSearch(q).done(function (resp) {
                    if (resp.status === 'success') {
                        submissions = resp.data;
                        updateDisplay();
                    }
                }).fail(function () {
                    // Fallback
                });
            }, 300);

            searchInput.addEventListener('input', handleSearch);

            // Inisialisasi
            const savedFilter = sessionStorage.getItem('activeFilter');
            if (savedFilter) {
                const targetBtn = document.querySelector(`.filter-btn[data-filter="${savedFilter}"]`);
                if (targetBtn) {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    targetBtn.classList.add('active');
                }
            }
            updateDisplay();

        </script>

        <script>
            function sendData(status, id, angka, paket_index = ''){
                $.ajax({
                    data: {
                        status: status,
                        id: id,
                        angka: angka,
                        paket_index: paket_index,
                        _token: "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('admin.list-permintaan.store') }}",
                    success: function (data) {
                        if (data.status == 'success') {
                            window.location.reload();
                        } else {
                            Swal.fire("Gagal !!", data.message, "error");
                        }
                    },
                    error: function (data) {
                        Swal.fire("Gagal !!", data.responseText, "error")
                    }
                });
            }

            $('body').on('click', '.btn-approve', function () {
                $('#product_id').val($(this).data('id'));
                $('#paket_index').val($(this).data('paket') !== undefined ? $(this).data('paket') : '');
                $('#formInput')[0].reset();
                confirmModal.show();
            });

            $('body').on('click', '#save-btn', function (e) {
                e.preventDefault();

                var id = $('#product_id').val();
                $.ajax({
                    data: {
                        status: 'disetujui',
                        id: id,
                        angka: 2,
                        catatan: $('#catatan').val(),
                        hari: $('#hari').val(),
                        tanggal: $('#tanggal').val(),
                        paket_index: $('#paket_index').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('admin.list-permintaan.store') }}",
                    beforeSend: function () {
                        $('#save-btn').prop('disabled', true);
                        $('#save-btn').html('Loading <i class="mdi mdi-loading mdi-spin"></i>');
                    },
                    success: function (data) {
                        if(data.status == 'success') {
                            window.location.reload();
                        } else {
                            Swal.fire("Gagal !!", data.message, "error");
                        }
                    },
                    error: function (data) {
                        Swal.fire("Gagal !!", data.responseText, "error")
                    },
                    complete: function () {
                        $('#save-btn').prop('disabled', false);
                        $('#save-btn').html('Simpan Persetujuan');
                        confirmModal.hide();
                    }
                });
            });

            $('body').on('click', '.btn-reject', function () {
                var id = $(this).data("id");
                $('#formInputBatal')[0].reset();
                $('#id_batal').val(id);
                $('#status_angka').val(4);
                $('#batal_paket_index').val($(this).data('paket') !== undefined ? $(this).data('paket') : '');
                $('#headerBatal').html('Alasan Penolakan');
                cancelModal.show();
            });

            $('body').on('click', '.btn-batal', function () {
                var id = $(this).data("id");
                $('#formInputBatal')[0].reset();
                $('#id_batal').val(id);
                $('#status_angka').val(5);
                $('#batal_paket_index').val($(this).data('paket') !== undefined ? $(this).data('paket') : '');
                $('#headerBatal').html('Alasan Pembatalan');
                cancelModal.show();
            });
            
            $('body').on('click', '.btn-confirm', function () {
                var id = $(this).data("id");
                $('#formInputBatal')[0].reset();
                $('#id_batal').val(id);
                $('#status_angka').val(3);
                $('#batal_paket_index').val($(this).data('paket') !== undefined ? $(this).data('paket') : '');
                $('#headerBatal').html('Alasan Konfirmasi');
                cancelModal.show();
            });

            $('body').on('click', '#save-btn-batal', function (e) {
                e.preventDefault();

                var id = $('#id_batal').val();
                $.ajax({
                    data: {
                        status: $('#status_angka').val() == 4 ? 'ditolak' : ($('#status_angka').val() == 5 ? 'batal' : 'konfirmasi'),
                        id: id,
                        angka: $('#status_angka').val(),
                        catatan: $('#catatan_batal').val(),
                        paket_index: $('#batal_paket_index').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    type: "POST",
                    url: "{{ route('admin.list-permintaan.store') }}",
                    beforeSend: function () {
                        $('#save-btn-batal').prop('disabled', true);
                        $('#save-btn-batal').html('Loading <i class="mdi mdi-loading mdi-spin"></i>');
                    },
                    success: function (data) {
                        if(data.status == 'success') {
                            window.location.reload();
                        } else {
                            Swal.fire("Gagal !!", data.message, "error");
                        }
                    },
                    error: function (data) {
                        Swal.fire("Gagal !!", data.responseText, "error")
                    },
                    complete: function () {
                        $('#save-btn-batal').prop('disabled', false);
                        $('#save-btn-batal').html('Simpan');
                        cancelModal.hide();
                    }
                });
            });

            $('body').on('click', '#btn-edit-permintaan', function () {
                var id = $(this).data("id");

                Swal.fire({
                    title: 'Yakin Ingin Mengedit Status Permintaan ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f3bb3',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Edit!',
                    cancelButtonText: 'Batal',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('admin.list-permintaan.index') }}" + '/' + id + '/edit',
                            success: function (data) {
                                if(data.status == 'success') {
                                    window.location.reload();
                                } else {
                                    Swal.fire("Gagal !!", data.message, "error");
                                }
                            },
                            error: function (data) {
                                Swal.fire("Gagal !!", data.responseText, "error")
                            },
                        });
                    }
                })
            });
            $('body').on('click', '.btn-delete-permintaan', function () {
                var id = $(this).data("id");

                Swal.fire({
                    title: 'Yakin Ingin Menghapus Permintaan?',
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
                            url: "{{ route('admin.permintaan.index') }}" + "/" + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.status == 'success') {
                                    window.location.reload();
                                } else {
                                    Swal.fire("Gagal !!", data.message, "error");
                                }
                            },
                            error: function (data) {
                                Swal.fire("Gagal !!", data.responseText, "error")
                            }
                        });
                    }
                })
            });
        </script>
    @endpush

</x-staradmin>
