<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengajuan</title>
    <style>
        :root {
            --bg-cream: #FFFBEF;
            --primary-green: #104837;
            --status-green: #2E7D32;
            --status-red: #C62828;
            --status-orange: #ED6C02;
            --text-light: #FFFFFF;
            --text-dark: #333333;
            --border-color: #e0e0e0;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-cream);
        }

        /* === LAYOUT UTAMA === */
        .mobile-container {
            width: 100%; height: 100vh;
            display: flex; flex-direction: column;
            position: relative;
        }
        .top-bar {
            background-color: var(--primary-green); padding: 15px 20px;
            display: flex; justify-content: center; align-items: center;
            color: white; font-size: 18px; font-weight: bold;
            flex-shrink: 0;
        }
        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0;
        }

        /* === Filter/Tab Navigasi === */
        .filter-nav {
            display: flex;
            justify-content: space-around;
            background-color: #fff;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .filter-btn {
            background: none;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #777;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: color 0.2s, border-bottom-color 0.2s;
        }
        .filter-btn.active {
            color: var(--primary-green);
            border-bottom-color: var(--primary-green);
        }

        /* === Daftar Pengajuan === */
        .submission-list {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .submission-card {
            background-color: #fff;
            border-radius: 12px;
            border-left: 5px solid; /* Akan diisi oleh status */
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .card-header {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-info .patient-name {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        .header-info .submission-date {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        /* Status colors */
        .status-approved { background-color: var(--status-green); }
        .card-approved { border-left-color: var(--status-green); }
        .status-rejected { background-color: var(--status-red); }
        .card-rejected { border-left-color: var(--status-red); }
        .status-pending { background-color: var(--status-orange); }
        .card-pending { border-left-color: var(--status-orange); }

        .card-body {
            padding: 0 15px 15px;
            font-size: 14px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-row .label {
            color: #666;
            width: 80px;
            flex-shrink: 0;
        }
        .info-row .value {
            font-weight: 500;
        }

    </style>
</head>
<body>

    <div class="mobile-container">
        <header class="top-bar">Daftar Pengajuan</header>

        <nav class="filter-nav">
            <button class="filter-btn active" data-filter="semua">Semua</button>
            <button class="filter-btn" data-filter="menunggu">Menunggu</button>
            <button class="filter-btn" data-filter="disetujui">Disetujui</button>
            <button class="filter-btn" data-filter="ditolak">Ditolak</button>
        </nav>

        <main class="main-content">
            <div class="submission-list" id="submission-list">
                </div>
        </main>

    </div>

    <script>
        // --- SIMULASI DATA DARI DATABASE ---
        const submissions = [
            { id: 1, nama: 'Bambang Pamungkas', no_rm: '123456', ruangan: 'Melati', lantai: '3', diagnosis: 'Demam Berdarah', kategori: 'Konsultasi Dokter', status: 'disetujui', tanggal: '2025-07-25' },
            { id: 2, nama: 'Susi Susanti', no_rm: '789012', ruangan: 'Anggrek', lantai: '2', diagnosis: 'Patah Tulang', kategori: 'Tindakan Medis', status: 'menunggu', tanggal: '2025-07-26' },
            { id: 3, nama: 'Taufik Hidayat', no_rm: '345678', ruangan: 'Mawar', lantai: '1', diagnosis: 'Hipertensi', kategori: 'Permintaan Obat', status: 'ditolak', tanggal: '2025-07-24' },
            { id: 4, nama: 'Evan Dimas', no_rm: '901234', ruangan: 'Kenanga', lantai: '4', diagnosis: 'Vertigo', kategori: 'Konsultasi Dokter', status: 'disetujui', tanggal: '2025-07-23' },
            { id: 5, nama: 'Greysia Polii', no_rm: '567890', ruangan: 'Dahlia', lantai: '2', diagnosis: 'Asma', kategori: 'Tindakan Medis', status: 'menunggu', tanggal: '2025-07-27' },
        ];

        // --- ELEMEN DOM ---
        const submissionListContainer = document.getElementById('submission-list');
        const filterButtons = document.querySelectorAll('.filter-btn');

        // --- FUNGSI-FUNGSI ---

        // Fungsi untuk merender daftar pengajuan
        function renderSubmissions(filter = 'semua') {
            submissionListContainer.innerHTML = ''; // Kosongkan daftar

            const statusMap = {
                disetujui: { text: 'Disetujui', className: 'approved' },
                ditolak: { text: 'Ditolak', className: 'rejected' },
                menunggu: { text: 'Menunggu', className: 'pending' },
            };

            const filteredSubmissions = submissions.filter(s => {
                if (filter === 'semua') return true;
                return s.status === filter;
            });

            if (filteredSubmissions.length === 0) {
                submissionListContainer.innerHTML = '<p style="text-align:center; color:#888;">Tidak ada data untuk filter ini.</p>';
                return;
            }

            filteredSubmissions.forEach(item => {
                const statusInfo = statusMap[item.status];
                const cardHTML = `
                    <div class="submission-card card-${statusInfo.className}">
                        <div class="card-header">
                            <div class="header-info">
                                <p class="patient-name">${item.nama}</p>
                                <span class="submission-date">Diajukan pada: ${new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                            </div>
                            <div class="status-badge status-${statusInfo.className}">${statusInfo.text}</div>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <span class="label">No. RM:</span>
                                <span class="value">${item.no_rm}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Ruangan:</span>
                                <span class="value">${item.ruangan} / Lt. ${item.lantai}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Kategori:</span>
                                <span class="value">${item.kategori.replace(/_/g, ' ')}</span>
                            </div>
                        </div>
                    </div>
                `;
                submissionListContainer.insertAdjacentHTML('beforeend', cardHTML);
            });
        }

        // --- EVENT LISTENERS ---
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Hapus kelas 'active' dari semua tombol
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Tambahkan 'active' ke tombol yang diklik
                button.classList.add('active');

                // Panggil fungsi render dengan filter yang sesuai
                const filterValue = button.getAttribute('data-filter');
                renderSubmissions(filterValue);
            });
        });

        // --- INISIALISASI HALAMAN ---
        renderSubmissions(); // Tampilkan semua data saat pertama kali dimuat

    </script>
</body>
</html>
