@section('title', 'Laporan')

<x-staradmin>
    <style>
        .search-card-body {
            padding: 0.75rem 1.25rem !important;
        }
        .compact-margin {
            margin-bottom: 0.5rem !important;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Laporan</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                        
                        {{-- Form Filter --}}
                        <div class="row compact-margin">
                            <div class="col-12 stretch-card">
                                <div class="card shadow-sm">
                                    <div class="card-body search-card-body">
                                        <form id="filter-form" class="d-flex gap-2 align-items-end flex-wrap">
                                            <div class="form-group mb-0">
                                                <label for="start_date" style="font-size:0.8rem;">Dari Tanggal</label>
                                                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date">
                                            </div>
                                            <div class="form-group mb-0">
                                                <label for="end_date" style="font-size:0.8rem;">Sampai Tanggal</label>
                                                <input type="date" class="form-control form-control-sm" id="end_date" name="end_date">
                                            </div>
                                            <div class="form-group mb-0">
                                                <label for="month" style="font-size:0.8rem;">Atau per Bulan</label>
                                                <input type="month" class="form-control form-control-sm" id="month" name="month">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm text-white mb-0">Terapkan Filter</button>
                                            <button type="button" id="reset-filter" class="btn btn-light btn-sm mb-0">Reset</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kartu Statistik --}}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Total Pengajuan</p>
                                        <h3 class="rate-percentage" id="total-requests">0</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Disetujui</p>
                                        <h3 class="rate-percentage" id="approved-requests">0</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-check-circle"></i></p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Menunggu</p>
                                        <h3 class="rate-percentage" id="pending-requests">0</h3>
                                        <p class="text-warning d-flex"><i class="mdi mdi-clock-outline"></i></p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Ditolak</p>
                                        <h3 class="rate-percentage" id="rejected-requests">0</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-close-circle"></i></p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Dibatalkan</p>
                                        <h3 class="rate-percentage" id="cancelled-requests">0</h3>
                                        <p class="text-muted d-flex"><i class="mdi mdi-cancel"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kontainer Chart --}}
                        <div class="row mt-4">
                            <div class="col-lg-8 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Pengajuan per Kategori</h4>
                                        <div class="chartjs-wrapper mt-3">
                                            <canvas id="categoryChart" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Komposisi Status</h4>
                                        <div class="chartjs-wrapper mt-3">
                                            <canvas id="statusChart" style="height: 300px;"></canvas>
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

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // --- KODE JAVASCRIPT FINAL YANG SUDAH DIPERBAIKI DAN DIRAPIKAN ---

        // Variabel global untuk menyimpan instance chart
        let categoryChartInstance = null;
        let statusChartInstance = null;

        // Data awal dari server saat halaman dimuat
        const initialSubmissions = @json($datas);

        // **PERBAIKAN 1: Fungsi-fungsi dipisah berdasarkan tugasnya untuk alur data yang jelas**

        // Fungsi ini HANYA menghitung data dan mengembalikannya sebagai objek.
        function calculateAllStats(submissions) {
            return {
                total: submissions.length,
                approved: submissions.filter(s => s.status === 'disetujui').length,
                rejected: submissions.filter(s => s.status === 'ditolak').length,
                pending: submissions.filter(s => s.status === 'menunggu').length,
                cancelled: submissions.filter(s => s.status === 'batal').length
            };
        }

        // Fungsi ini HANYA bertugas memperbarui teks pada kartu statistik di halaman.
        function updateStatCards(stats) {
            document.getElementById('total-requests').textContent = stats.total;
            document.getElementById('approved-requests').textContent = stats.approved;
            document.getElementById('rejected-requests').textContent = stats.rejected;
            document.getElementById('pending-requests').textContent = stats.pending;
            document.getElementById('cancelled-requests').textContent = stats.cancelled;
        }

        // Fungsi ini HANYA bertugas menggambar atau memperbarui chart di canvas.
        function renderCharts(submissions, stats) {
            // Hancurkan chart lama jika ada
            if (categoryChartInstance) categoryChartInstance.destroy();
            if (statusChartInstance) statusChartInstance.destroy();

            // Data untuk chart kategori
            const categoryCounts = submissions.reduce((acc, sub) => {
                acc[sub.kategori] = (acc[sub.kategori] || 0) + 1;
                return acc;
            }, {});
            // Chart Kategori (Bar)
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const catLabels = Object.keys(categoryCounts).length ? Object.keys(categoryCounts) : ['-'];
            const catData = Object.keys(categoryCounts).length ? Object.values(categoryCounts) : [0];

            categoryChartInstance = new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: catLabels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: catData,
                        backgroundColor: '#1f3bb3',
                        borderColor: '#1f3bb3',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });

            // Chart Status (Pie)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = [stats.approved, stats.rejected, stats.pending, stats.cancelled];

            statusChartInstance = new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Disetujui', 'Ditolak', 'Menunggu', 'Batal'],
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            '#198ae3',
                            '#ff4747',
                            '#ffc100',
                            '#8a909d'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }

        // **PERBAIKAN 2: Fungsi utama yang mengatur alur data secara berurutan**
        function updateDashboard(submissionsData) {
            // Langkah 1: Hitung semua statistik SEKALI SAJA.
            const stats = calculateAllStats(submissionsData);

            // Langkah 2: Perbarui tampilan kartu statistik.
            updateStatCards(stats);

            // Langkah 3: Gambar chart menggunakan data mentah dan data statistik yang sudah dihitung.
            renderCharts(submissionsData, stats);
        }

        // Event listener untuk form filter (tidak berubah, sudah benar)
        document.getElementById('filter-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            const filterUrl = `{{ route('admin.laporan.index') }}?${params}`;

            fetch(filterUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                updateDashboard(data); // Panggil fungsi utama dengan data baru
            })
            .catch(error => console.error('Error fetching filtered data:', error));
        });

        // Event listener untuk tombol reset (tidak berubah, sudah benar)
        document.getElementById('reset-filter').addEventListener('click', function() {
            document.getElementById('filter-form').reset();
            updateDashboard(initialSubmissions); // Panggil fungsi utama dengan data awal
        });

        // --- INISIALISASI HALAMAN ---
        // Panggil fungsi utama saat halaman pertama kali dimuat
        updateDashboard(initialSubmissions);

        // **PERBAIKAN 3: Semua kode lama dan yang tidak terpakai sudah dihapus.**

    </script>
@endpush
</x-staradmin>
