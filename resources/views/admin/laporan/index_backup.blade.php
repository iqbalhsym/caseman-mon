@section('title', 'Lpoaran')

<x-layouts>
@push('style')
    <link href="{{ asset('css/laporan.css') }}" rel="stylesheet">
    <style>
        .filter-container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 24px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .filter-container form { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label { font-size: 12px; margin-bottom: 4px; color: #555; }
        .filter-group input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .filter-container button { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .filter-btn { background-color: #104837; color: white; }
        .reset-btn { background-color: #6c757d; color: white; }
    </style>
@endpush

<main class="main-content">

    {{-- Form Filter --}}
    <div class="filter-container">
        <form id="filter-form">
            <div class="filter-group">
                <label for="start_date">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date">
            </div>
            <div class="filter-group">
                <label for="end_date">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date">
            </div>
            <div class="filter-group">
                <label for="month">Atau per Bulan</label>
                <input type="month" id="month" name="month">
            </div>
            <button type="submit" class="filter-btn">Terapkan Filter</button>
            <button type="button" id="reset-filter" class="reset-btn">Reset</button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card total">
            <p class="label">Total Pengajuan</p>
            <p class="value" id="total-requests">0</p>
        </div>
        <div class="stat-card approved">
            <p class="label">Disetujui</p>
            <p class="value" id="approved-requests">0</p>
        </div>
        <div class="stat-card rejected">
            <p class="label">Ditolak</p>
            <p class="value" id="rejected-requests">0</p>
        </div>
        <div class="stat-card pending">
            <p class="label">Menunggu</p>
            <p class="value" id="pending-requests">0</p>
        </div>
        <div class="stat-card rejected">
            <p class="label">Batal</p>
            <p class="value" id="cancelled-requests">0</p>
        </div>
    </div>

    <div class="chart-container">
        <h2>Pengajuan per Kategori</h2>
        <canvas id="categoryChart"></canvas>
    </div>

    <div class="chart-container">
        <h2>Komposisi Status</h2>
        <canvas id="statusChart"></canvas>
    </div>

</main>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // --- SIMULASI DATA YANG SAMA DARI HALAMAN DAFTAR ---
        // const submissions = [
        //     { id: 1, kategori: 'Konsultasi Dokter', status: 'disetujui' },
        //     { id: 2, kategori: 'Tindakan Medis', status: 'menunggu' },
        //     { id: 3, kategori: 'Permintaan Obat', status: 'ditolak' },
        //     { id: 4, kategori: 'Konsultasi Dokter', status: 'disetujui' },
        //     { id: 5, kategori: 'Tindakan Medis', status: 'menunggu' },
        //     { id: 6, kategori: 'Lainnya', status: 'disetujui' },
        //     { id: 7, kategori: 'Permintaan Obat', status: 'disetujui' },
        // ];

        const submissions = @json($datas);

        // --- MENGHITUNG STATISTIK ---
        function calculateStats() {
            const total = submissions.length;
            const approved = submissions.filter(s => s.status === 'disetujui').length;
            const rejected = submissions.filter(s => s.status === 'ditolak').length;
            const pending = submissions.filter(s => s.status === 'menunggu').length;
            const cancelled = submissions.filter(s => s.status === 'batal').length;

            document.getElementById('total-requests').textContent = total;
            document.getElementById('approved-requests').textContent = approved;
            document.getElementById('rejected-requests').textContent = rejected;
            document.getElementById('pending-requests').textContent = pending;
            document.getElementById('cancelled-requests').textContent = cancelled;

            return { total, approved, rejected, pending, cancelled };
        }

        // --- MEMPROSES DATA UNTUK GRAFIK ---
        function processChartData() {
            // Data untuk Grafik Kategori (Bar Chart)
            const categoryCounts = submissions.reduce((acc, submission) => {
                const category = submission.kategori.replace(/_/g, ' ');
                acc[category] = (acc[category] || 0) + 1;
                return acc;
            }, {});

            const categoryLabels = Object.keys(categoryCounts);
            const categoryData = Object.values(categoryCounts);

            // Data untuk Grafik Status (Pie Chart)
            const statusCounts = calculateStats();
            const statusLabels = ['Disetujui', 'Ditolak', 'Menunggu', 'Batal'];
            const statusData = [statusCounts.approved, statusCounts.rejected, statusCounts.pending, statusCounts.cancelled];

            return { categoryLabels, categoryData, statusLabels, statusData };
        }

        // --- FUNGSI UNTUK MEMBUAT GRAFIK ---
        function createCharts() {
            const { categoryLabels, categoryData, statusLabels, statusData } = processChartData();

            // Grafik Batang (Bar Chart) untuk Kategori
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: categoryData,
                        backgroundColor: 'rgba(16, 72, 55, 0.8)',
                        borderColor: 'rgba(16, 72, 55, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat bar menjadi horizontal
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Grafik Pai (Pie Chart) untuk Status
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            'rgba(46, 125, 50, 0.8)', // Hijau
                            'rgba(198, 40, 40, 0.8)',  // Merah
                            'rgba(237, 108, 2, 0.8)',   // Oranye
                            'rgba(198, 40, 40, 0.8)'   // Oranye'
                        ],
                        borderColor: [
                            'rgba(46, 125, 50, 1)',
                            'rgba(198, 40, 40, 1)',
                            'rgba(237, 108, 2, 1)',
                            'rgba(237, 108, 2, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
        }

        // --- INISIALISASI HALAMAN ---
        calculateStats();
        createCharts();

    </script>
@endpush
</x-layouts>
