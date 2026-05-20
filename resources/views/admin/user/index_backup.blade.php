<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
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
            background-color: var(--bg-cream);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .top-bar {
            background-color: var(--primary-green);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
        }

        .top-bar .logo {
            width: 100px;
            height: auto;
        }

        .burger-menu {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .burger-menu .line {
            width: 24px;
            height: 3px;
            background-color: var(--text-light);
            border-radius: 3px;
        }

        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }

        /* Daftar Kartu Pengguna */
        .page-header h1 {
            margin: 0 0 20px 0;
            color: var(--primary-green);
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .user-list {
            display: grid;
            gap: 15px;
        }

        .user-card {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 15px var(--card-shadow);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-green);
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            margin-right: 15px;
        }

        .user-info .name {
            font-weight: bold;
            font-size: 18px;
            margin: 0;
        }
        .user-info .email {
            color: #666;
            margin: 4px 0 0 0;
        }

        /* Navigasi Bawah */
        .bottom-navbar {
            background-color: var(--primary-green);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            flex-shrink: 0;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-light);
            opacity: 0.7;
        }

        .nav-item svg {
            width: 24px;
            height: 24px;
            fill: var(--text-light);
            margin-bottom: 4px;
        }
        .nav-item span { font-size: 11px; }
        .nav-item.active {
            color: var(--accent-orange);
            opacity: 1;
        }
        .nav-item.active svg { fill: var(--accent-orange); }

        /* Menu Samping (Sidebar) */
        .side-menu {
            position: fixed;
            top: 0; left: 0;
            width: 70%;
            height: 100%;
            background-color: var(--primary-green);
            z-index: 100;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            padding: 80px 20px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .side-menu.active { transform: translateX(0); }
        .side-menu a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 18px;
            padding: 15px 10px;
            border-radius: 8px;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s;
        }
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* === Floating Action Button (FAB) - BARU === */
        .fab {
            position: fixed;
            bottom: 80px; /* Jarak dari bawah */
            right: 20px; /* Jarak dari kiri */
            width: 60px;
            height: 60px;
            background-color: var(--accent-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: none;
            cursor: pointer;
            z-index: 900;
            transition: transform 0.2s;
        }
        .fab:hover {
            transform: scale(1.1);
        }
        .fab svg {
            width: 30px;
            height: 30px;
            fill: var(--text-light);
        }

        /* === Modal Tambah Pengguna === */
        .modal-container {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: flex-end;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 1000;
        }
        .modal-container.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background-color: #fff;
            width: 100%;
            padding: 20px;
            border-radius: 20px 20px 0 0;
            box-sizing: border-box;
            transform: translateY(100%);
            transition: transform 0.4s ease-in-out;
        }
        .modal-container.active .modal-content {
            transform: translateY(0);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h2 { margin: 0; }

        /* Form Styling */
        .input-group { margin-bottom: 15px; }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .form-actions button {
            flex-grow: 1;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid var(--primary-green);
        }
        #save-btn {
            background-color: var(--primary-green);
            color: var(--text-light);
        }
        #cancel-btn {
            background-color: #fff;
            color: var(--primary-green);
        }
    </style>
</head>
<body>

    <div class="mobile-container">

        <nav class="side-menu">
            <a href="#">Analisis Penjualan</a>
            <a href="#">Manajemen Produk</a>
            <a href="#">Data Pelanggan</a>
            <a href="#">Pengaturan</a>
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
                <h1>Manajemen Pengguna</h1>
            </div>

            <div class="user-list" id="user-list-container">
                </div>
        </main>

        <button class="fab" id="add-user-btn-fab">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        </button>

        <footer class="bottom-navbar">
            <a href="#" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8h5z"/></svg>
                <span>Beranda</span>
            </a>
            <a href="#" class="nav-item active"> <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                <span>Pengguna</span>
            </a>
            <a href="#" class="nav-item">
                <svg viewBox="0 0 24 24"><path d="M4 9h4v11H4zm6-5h4v16h-4zm6 8h4v8h-4z"/></svg>
                <span>Laporan</span>
            </a>
        </footer>

    </div>

    <div class="modal-container" id="add-user-modal">
        <div class="modal-content">
            <header class="modal-header">
                <h2>Tambah Pengguna Baru</h2>
            </header>
            <form id="add-user-form">
                <div class="input-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" required>
                </div>
                <div class="input-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" required>
                </div>
                <div class="form-actions">
                    <button type="button" id="cancel-btn">Batal</button>
                    <button type="submit" id="save-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- DATA PENGGUNA (Simulasi Database) ---
        let users = [
            { id: 1, name: 'Budi Santoso', email: 'budi.s@example.com' },
            { id: 2, name: 'Citra Lestari', email: 'citra.l@example.com' },
        ];

        // --- SEMUA ELEMEN DOM ---
        const userListContainer = document.getElementById('user-list-container');
        const addUserModal = document.getElementById('add-user-modal');
        const addUserBtnFab = document.getElementById('add-user-btn-fab'); // Tombol FAB baru
        const cancelBtn = document.getElementById('cancel-btn');
        const addUserForm = document.getElementById('add-user-form');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');

        // Elemen dari layout dashboard
        const burgerMenu = document.querySelector('.burger-menu');
        const sideMenu = document.querySelector('.side-menu');
        const overlay = document.querySelector('.overlay');
        const navItems = document.querySelectorAll('.nav-item');
        const mobileContainer = document.querySelector('.mobile-container');


        // --- FUNGSI-FUNGSI ---

        // Fungsi Render Kartu Pengguna
        function renderUsers() {
            userListContainer.innerHTML = '';
            users.forEach(user => {
                const userCardHTML = `
                    <div class="user-card">
                        <div class="user-avatar">${user.name.charAt(0)}</div>
                        <div class="user-info">
                            <p class="name">${user.name}</p>
                            <p class="email">${user.email}</p>
                        </div>
                    </div>
                `;
                userListContainer.insertAdjacentHTML('beforeend', userCardHTML);
            });
        }

        // Fungsi Modal
        function openModal() { addUserModal.classList.add('active'); }
        function closeModal() { addUserModal.classList.remove('active'); }

        // Fungsi Menu Samping
        function toggleSideMenu() {
            sideMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Fungsi Form Submit
        function handleFormSubmit(event) {
            event.preventDefault();
            const newName = nameInput.value.trim();
            const newEmail = emailInput.value.trim();

            if (newName && newEmail) {
                const newUser = { id: Date.now(), name: newName, email: newEmail };
                users.push(newUser);
                renderUsers();
                closeModal();
                addUserForm.reset();
            }
        }

        // Fungsi untuk tinggi 100vh di mobile
        function setContainerHeight() {
            mobileContainer.style.height = window.innerHeight + 'px';
        }

        // --- EVENT LISTENERS ---

        // Listener untuk manajemen pengguna
        addUserBtnFab.addEventListener('click', openModal);
        cancelBtn.addEventListener('click', closeModal);
        addUserModal.addEventListener('click', (e) => { if (e.target === addUserModal) closeModal(); });
        addUserForm.addEventListener('submit', handleFormSubmit);

        // Listener untuk layout dashboard
        burgerMenu.addEventListener('click', toggleSideMenu);
        overlay.addEventListener('click', toggleSideMenu);
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                navItems.forEach(nav => nav.classList.remove('active'));
                e.currentTarget.classList.add('active');
            });
        });
        window.addEventListener('resize', setContainerHeight);


        // --- INISIALISASI HALAMAN ---
        renderUsers();
        setContainerHeight();

    </script>
</body>
</html>
