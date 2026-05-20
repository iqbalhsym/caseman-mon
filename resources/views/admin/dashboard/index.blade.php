@section('title', 'Beranda')

<x-staradmin>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Total Pengajuan</p>
                                        <h3 class="rate-percentage">{{ $totalRequests ?? 0 }}</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Disetujui</p>
                                        <h3 class="rate-percentage">{{ $approved ?? 0 }}</h3>
                                        <p class="text-success d-flex"><i class="mdi mdi-check-circle"></i></p>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Menunggu</p>
                                        <h3 class="rate-percentage">{{ $pending ?? 0 }}</h3>
                                        <p class="text-warning d-flex"><i class="mdi mdi-clock-outline"></i></p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Ditolak</p>
                                        <h3 class="rate-percentage">{{ $rejected ?? 0 }}</h3>
                                        <p class="text-danger d-flex"><i class="mdi mdi-close-circle"></i></p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Dibatalkan</p>
                                        <h3 class="rate-percentage">{{ $cancelled ?? 0 }}</h3>
                                        <p class="text-muted d-flex"><i class="mdi mdi-cancel"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Pengajuan 7 Hari Terakhir</h4>
                                                    </div>
                                                </div>
                                                <div class="chartjs-wrapper mt-3">
                                                    <canvas id="trendChart" style="height: 250px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 col-lg-4 col-lg-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Pengajuan per Kategori</h4>
                                                    </div>
                                                </div>
                                                <div class="chartjs-wrapper mt-3">
                                                    <canvas id="catChart" style="height: 250px;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="card-title card-title-dash">Recent Permintaan</h4>
                                                        </div>
                                                        <div class="mt-3">
                                                            @foreach($recent ?? [] as $r)
                                                                <div class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                                    <div class="d-flex">
                                                                        <div class="wrapper ms-3">
                                                                            <p class="ms-1 mb-1 fw-bold">{{ $r['nama'] }} <span class="text-muted">({{ $r['no_rm'] }})</span></p>
                                                                            <small class="text-muted mb-0">{{ $r['lokasi'] }} • {{ $r['kategori'] }}</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-muted text-small">
                                                                        <span class="badge badge-opacity-{{ $r['status'] == 'disetujui' ? 'success' : ($r['status'] == 'menunggu' ? 'warning' : 'danger') }}">
                                                                            {{ ucfirst($r['status']) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
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
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const byCategory = @json($byCategory ?? []);
            const labels7 = @json($labels ?? []);
            const trend = @json($trend ?? []);

            // Category chart
            const catCtx = document.getElementById('catChart').getContext('2d');
            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(byCategory).length ? Object.keys(byCategory) : ['-'],
                    datasets: [{
                        label: 'Jumlah',
                        data: Object.keys(byCategory).length ? Object.values(byCategory) : [0],
                        backgroundColor: '#1f3bb3',
                        borderRadius: 5
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });

            // Trend chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: labels7,
                    datasets: [{
                        label: 'Pengajuan',
                        data: trend,
                        borderColor: '#1f3bb3',
                        backgroundColor: 'rgba(31, 59, 179, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });
        </script>
    @endpush
</x-staradmin>
