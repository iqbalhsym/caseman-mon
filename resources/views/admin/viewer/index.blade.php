@section('title', 'Viewer Status')

<x-staradmin>
    @push('style')
        <style>
            .filter-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 12px;
            }
            .filter-btn {
                background: #fff;
                border: 1px solid #dee2e6;
                padding: 5px 14px;
                border-radius: 20px;
                color: #6c757d;
                font-weight: 500;
                font-size: 0.82rem;
                cursor: pointer;
                transition: all 0.2s ease;
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
                box-shadow: 0 3px 8px rgba(31,59,179,0.2);
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
            @media (max-width: 768px) {
                .filter-nav {
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    padding-bottom: 6px;
                    scrollbar-width: thin;
                }
                .filter-btn { flex: 0 0 auto; }
                .info-grid { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Viewer Status Permintaan</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">

                        {{-- Card Pencarian --}}
                        <div class="row compact-margin">
                            <div class="col-12 stretch-card">
                                <div class="card shadow-sm">
                                    <div class="card-body search-card-body">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <div class="input-group" style="max-width: 380px; flex:1;">
                                                <span class="input-group-text input-group-text-custom">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                                <input type="search" class="form-control search-input-custom" id="search-input" placeholder="Cari nama, No. RM, atau Ruangan...">
                                            </div>
                                            <span class="text-muted" style="font-size: 0.8rem;">
                                                <i class="mdi mdi-clock-outline"></i> Menampilkan data 7 hari terakhir yang disetujui
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Filter Kategori --}}
                        <nav class="filter-nav">
                            <button class="filter-btn active" data-filter="semua">Semua</button>
                            <button class="filter-btn" data-filter="Obat">Farmasi</button>
                            <button class="filter-btn" data-filter="Lab">Laboratorium</button>
                            <button class="filter-btn" data-filter="Rad">Radiologi</button>
                            <button class="filter-btn" data-filter="Bmhp">BMHP</button>
                            <button class="filter-btn" data-filter="Darah">Produk Darah</button>
                        </nav>

                        <div class="row" id="submission-list">
                            <!-- Diisi oleh Javascript -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const initialSubmissions = @json($datas);
            let submissions = Array.isArray(initialSubmissions) ? initialSubmissions.slice() : [];

            const submissionListContainer = document.getElementById('submission-list');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const searchInput = document.getElementById('search-input');

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
                    menunggu:  { text: 'Menunggu',  badge: 'warning' },
                    disetujui: { text: 'Disetujui', badge: 'success' },
                    konfirmasi:{ text: 'Konfirmasi', badge: 'orange' },
                    ditolak:   { text: 'Ditolak',   badge: 'danger' },
                    batal:     { text: 'Dibatalkan', badge: 'dark' },
                };

                const lowerQ = searchQuery.toLowerCase().trim();

                const filtered = submissions.filter(s => {
                    const categoryMatch = filter === 'semua' || s.kategori === filter;
                    const searchMatch = (s.nama && String(s.nama).toLowerCase().includes(lowerQ)) || 
                                        (s.no_rm && String(s.no_rm).toLowerCase().includes(lowerQ)) ||
                                        (s.lokasi && String(s.lokasi).toLowerCase().includes(lowerQ));
                    return categoryMatch && searchMatch;
                });

                if (filtered.length === 0) {
                    submissionListContainer.innerHTML = `
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="mdi mdi-file-hidden" style="font-size: 3rem; color: #ccc;"></i>
                                <h6 class="mt-3 text-muted">Data tidak ditemukan</h6>
                            </div>
                        </div>`;
                    return;
                }

                filtered.forEach(item => {
                    const statusInfo = statusMap[item.status] || { text: item.status, badge: 'light' };

                    const fileLinks = [item.file, item.file2, item.file3]
                        .filter(Boolean)
                        .map((f, i) => `<a href="${f}" target="_blank" class="badge badge-opacity-primary me-1">File ${i+1}</a>`)
                        .join('');

                    const noteHTML = (item.catatan_diterima !== null && item.jumlah_hari !== null) ? `
                        <div class="info-row">
                            <span class="label">Persetujuan:</span>
                            <span class="value fw-bold text-success">${item.jumlah_hari} Hari (${item.tanggal_mulai_expired} – ${item.tanggal_berakhir_expired})</span>
                        </div>` : '';

                    submissionListContainer.insertAdjacentHTML('beforeend', `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card submission-card status-${item.status} h-100">
                                <div class="card-header">
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
                                    ${(item.detail_paket && item.detail_paket.length > 0) ? item.detail_paket.map((paket, idx) => `
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
                                            <div class="info-row">
                                                <span class="label" style="min-width:80px">Indikasi:</span>
                                                <span class="value">${displayHTML(paket.indikasi)}</span>
                                            </div>
                                        </div>
                                    `).join('') : `
                                        <div class="info-row">
                                            <span class="label">Kategori:</span>
                                            <span class="value text-primary font-weight-bold">${item.kategori ? item.kategori.replace(/_/g, ' ') : '-'}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Keterangan:</span>
                                            <span class="value">${displayHTML(item.keterangan)}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="label">Indikasi:</span>
                                            <span class="value">${displayHTML(item.indikasi)}</span>
                                        </div>
                                    `}
                                    <hr>
                                    <div class="info-row mt-3">
                                        <span class="label">Catatan:</span>
                                        <span class="value">${displayHTML(item.catatan_diterima)}</span>
                                    </div>
                                    ${noteHTML}
                                    ${fileLinks ? `<div class="info-row mt-2"><span class="label">Lampiran:</span><span class="value">${fileLinks}</span></div>` : ''}
                                </div>
                                <div class="card-footer justify-content-between align-items-center">
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        <i class="mdi mdi-account-check text-success me-1"></i>${item.status2} oleh: <strong>${item.manager || '-'}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }

            function updateDisplay() {
                const activeFilter = document.querySelector('.filter-btn.active')?.getAttribute('data-filter') || 'semua';
                renderSubmissions(activeFilter, searchInput.value);
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
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
                });
            }, 300);

            searchInput.addEventListener('input', handleSearch);
            renderSubmissions();
        </script>
    @endpush

</x-staradmin>
