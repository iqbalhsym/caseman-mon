<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content=""> <title>Formulir Pengajuan</title>
    <style>
        :root {
            --bg-cream: #FFFBEF;
            --primary-green: #104837;
            --accent-orange: #EC9800;
            --text-light: #FFFFFF;
            --text-dark: #333333;
            --card-shadow: rgba(0, 0, 0, 0.08);
            --border-color: #e0e0e0;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-cream);
        }

        /* === LAYOUT UTAMA DARI DASHBOARD === */
        .mobile-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .top-bar {
            background-color: var(--primary-green); padding: 15px 20px;
            display: flex; justify-content: space-between; align-items: center;
            flex-shrink: 0; z-index: 10;
        }
        .top-bar .logo { width: 100px; height: auto; }
        .burger-menu {
            background: none; border: none; cursor: pointer; padding: 0;
            display: flex; flex-direction: column; gap: 5px;
        }
        .burger-menu .line { width: 24px; height: 3px; background-color: var(--text-light); border-radius: 3px; }

        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }

        /* === FORM PENGAJUAN === */
        .page-header h1 {
            margin: 0 0 20px 0;
            color: var(--primary-green);
        }

        .form-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px var(--card-shadow);
            margin-bottom: 20px;
        }

        .form-section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            color: var(--primary-green);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .input-group {
            margin-bottom: 15px;
        }
        .input-group.full-width {
            grid-column: 1 / -1;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .input-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            color: var(--text-light);
            background-color: var(--accent-orange);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #d48700;
        }

        /* Navigasi Bawah & Menu Samping */
        .bottom-navbar {
            background-color: var(--primary-green); display: flex;
            justify-content: space-around; padding: 10px 0; flex-shrink: 0;
        }
        .nav-item {
            display: flex; flex-direction: column; align-items: center;
            text-decoration: none; color: var(--text-light); opacity: 0.7;
        }
        .nav-item svg { width: 24px; height: 24px; fill: var(--text-light); margin-bottom: 4px; }
        .nav-item span { font-size: 11px; }
        .nav-item.active { color: var(--accent-orange); opacity: 1; }
        .nav-item.active svg { fill: var(--accent-orange); }

        .side-menu {
            position: fixed; top: 0; left: 0; width: 70%; height: 100%;
            background-color: var(--primary-green); z-index: 100;
            transform: translateX(-100%); transition: transform 0.3s ease-in-out;
            padding: 80px 20px 20px; box-sizing: border-box;
            display: flex; flex-direction: column;
        }
        .side-menu.active { transform: translateX(0); }
        .side-menu a { color: var(--text-light); text-decoration: none; font-size: 18px; padding: 15px 10px; border-radius: 8px; }

        .overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 50; opacity: 0;
            visibility: hidden; transition: opacity 0.3s ease-in-out, visibility 0.3s;
        }
        .overlay.active { opacity: 1; visibility: visible; }
    </style>
</head>
<body>

    <div class="mobile-container">

        <nav class="side-menu">
            <a href="#">Dashboard</a>
            <a href="#">Manajemen Pengguna</a>
            <a href="#">Manajemen Shift</a>
            <a href="#">Pengajuan</a>
        </nav>

        <div class="overlay"></div>

        <header class="top-bar">
            <button class="burger-menu">
                <div class="line"></div><div class="line"></div><div class="line"></div>
            </button>
            <svg class="logo" viewBox="0 0 200 40" xmlns="http://www.w3.org/2000/svg">
              <text x="50" y="30" font-family="Arial, sans-serif" font-size="30" font-weight="bold" fill="#FFFFFF">LOGO</text>
            </svg>
            <div style="width: 24px;"></div>
        </header>

        <main class="main-content">
            <div class="page-header">
                <h1>Formulir Pengajuan</h1>
            </div>

            <form id="pengajuan-form">
                <div class="form-section">
                    <h2>Detail Pasien & Lokasi</h2>
                    <div class="input-group full-width">
                        <label for="nama">Nama Pasien</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="no_rm">No. Rekam Medis</label>
                            <input type="text" id="no_rm" name="no_rm" required>
                        </div>
                        <div class="input-group">
                            <label for="ruangan">Ruangan/Lokasi</label>
                            <input type="text" id="ruangan" name="ruangan" required>
                        </div>
                        <div class="input-group">
                            <label for="lantai">Lantai Ruangan</label>
                            <input type="text" id="lantai" name="lantai" required>
                        </div>
                        <div class="input-group">
                            <label for="diagnosis">Diagnosis</label>
                            <input type="text" id="diagnosis" name="diagnosis" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Detail Permintaan</h2>
                    <div class="input-group full-width">
                        <label for="kategori">Kategori Permintaan</label>
                        <select id="kategori" name="kategori">
                            <option value="">Pilih Kategori...</option>
                            <option value="konsultasi_dokter">Konsultasi Dokter</option>
                            <option value="tindakan_medis">Tindakan Medis</option>
                            <option value="permintaan_obat">Permintaan Obat</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="input-group full-width">
                        <label for="keterangan">Keterangan Tambahan (Free Text)</label>
                        <textarea id="keterangan" name="keterangan" placeholder="Jelaskan detail permintaan Anda di sini..."></textarea>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Kirim Pengajuan</button>
            </form>
        </main>

    </div>

    <script>
        // --- EVENT LISTENERS UNTUK LAYOUT UTAMA ---
        const burgerMenu = document.querySelector('.burger-menu');
        const sideMenu = document.querySelector('.side-menu');
        const overlay = document.querySelector('.overlay');

        function toggleSideMenu() {
            sideMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        burgerMenu.addEventListener('click', toggleSideMenu);
        overlay.addEventListener('click', toggleSideMenu);

        // --- LOGIKA FORM SUBMIT ---
        const pengajuanForm = document.getElementById('pengajuan-form');
        pengajuanForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah reload halaman

            // Di sini Anda bisa menambahkan logika AJAX untuk mengirim data
            const formData = new FormData(pengajuanForm);

            // Contoh menampilkan data di console
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            alert('Pengajuan berhasil dikirim! (Cek console untuk melihat data)');
            // Di aplikasi nyata, ganti alert dengan notifikasi toast dan pengiriman data ke server
            // pengajuanForm.reset();
        });
    </script>
</body>
</html>
