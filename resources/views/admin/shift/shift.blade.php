@section('title', 'Pengguna')

<x-layouts>

@push('style')
    <link href="{{ asset('css/shift.css') }}" rel="stylesheet">
@endpush

<div class="page-container">
    <nav class="calendar-nav">
        <button id="prev-week-btn">&lt;</button>
        <div class="week-display" id="week-display"></div>
        <button id="next-week-btn">&gt;</button>
    </nav>

    <div class="calendar-grid-container">
        <div class="calendar-grid" id="calendar-grid">
            </div>
    </div>
</div>


<button class="fab" id="add-shift-btn">
    <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
</button>


<div class="modal-container" id="shift-modal">
    <div class="modal-content">
        <header class="modal-header">
            <h2 id="modal-title">Tambah Shift Baru</h2>
        </header>
        <form id="shift-form">
            <input type="hidden" id="shift-id">
            <div class="input-group1 full-width">
                <label for="user-select">Pengguna</label>
                <select id="user-select" required></select>
            </div>
            <div class="input-group1 full-width">
                <label for="shift-date">Tanggal</label>
                <input type="date" id="shift-date" required>
            </div>
            <div class="form-grid">
                <div class="input-group1">
                    <label for="start-time">Jam Mulai</label>
                    <input type="time" id="start-time" required>
                </div>
                <div class="input-group1">
                    <label for="end-time">Jam Selesai</label>
                    <input type="time" id="end-time" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" id="delete-shift-btn" style="display: none;">Hapus</button>
                <button type="submit" id="save-btn">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('script')
    <script>
        // --- SIMULASI DATA ---
        // const users = [
        //     { id: 1, name: 'Budi Santoso' },
        //     { id: 2, name: 'Citra Lestari' },
        //     { id: 3, name: 'Adi Nugroho' },
        // ];

        const users = @json($users);

        const shifts = @json($shifts);

        // let shifts = [
        //     // Pekan ini
        //     { id: 101, userId: 2, date: '2025-07-24', startTime: '08:00', endTime: '16:00' },
        //     { id: 102, userId: 2, date: '2025-07-25', startTime: '16:00', endTime: '23:00' },
        //     { id: 103, userId: 3, date: '2025-07-26', startTime: '08:00', endTime: '16:00' },
        //     // Pekan depan
        //     { id: 104, userId: 4, date: '2025-07-27', startTime: '08:00', endTime: '16:00' },
        // ];

        // --- ELEMEN DOM ---
        const calendarGrid = document.getElementById('calendar-grid');
        const weekDisplay = document.getElementById('week-display');
        const prevWeekBtn = document.getElementById('prev-week-btn');
        const nextWeekBtn = document.getElementById('next-week-btn');
        const addShiftBtn = document.getElementById('add-shift-btn');
        const modal = document.getElementById('shift-modal');
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('shift-form');
        const userSelect = document.getElementById('user-select');
        const shiftIdInput = document.getElementById('shift-id');
        const deleteBtn = document.getElementById('delete-shift-btn');
        const saveBtn = document.getElementById('save-btn');

        // --- MANAJEMEN TANGGAL ---
        let currentDate = new Date(); // Set tanggal awal ke hari ini

        // --- FUNGSI-FUNGSI ---

        // Mengisi dropdown pengguna
        function populateUserDropdown() {
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} - (${user.role})`;
                userSelect.appendChild(option);
            });
        }

        // Render Kalender
        function renderCalendar() {
            calendarGrid.innerHTML = '';
            const weekStart = getWeekStart(currentDate);

            const options = { month: 'short', day: 'numeric' };
            const startStr = new Date(weekStart).toLocaleDateString('id-ID', options);
            const endStr = new Date(new Date(weekStart).setDate(weekStart.getDate() + 6)).toLocaleDateString('id-ID', options);
            weekDisplay.textContent = `${startStr} - ${endStr}`;

            const dayNames = ['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'];

            for (let i = 0; i < 7; i++) {
                const day = new Date(weekStart);
                day.setDate(weekStart.getDate() + i);

                const dayColumn = document.createElement('div');
                dayColumn.className = 'day-column';

                const today = new Date();
                const isToday = day.toDateString() === today.toDateString();

                dayColumn.innerHTML = `
                    <div class="day-header">
                        <div class="day-name">${dayNames[i]}</div>
                        <div class="day-date ${isToday ? 'today' : ''}">${day.getDate()}</div>
                    </div>
                    <div class="shifts-container" id="day-${i}"></div>
                `;
                calendarGrid.appendChild(dayColumn);

                const dayShifts = shifts.filter(s => s.date === toISODateString(day));
                const shiftsContainer = dayColumn.querySelector('.shifts-container');

                dayShifts.forEach(shift => {
                    const user = users.find(u => u.id === shift.userId);

                    const shiftItem = document.createElement('div');
                    shiftItem.className = 'shift-item';
                    shiftItem.innerHTML = `
                        <div class="user-name">${user.name}</div>
                        <div>${shift.startTime} - ${shift.endTime}</div>
                    `;
                    shiftItem.addEventListener('click', () => openModal(shift));
                    shiftsContainer.appendChild(shiftItem);
                });
            }
        }

        var shifts2 = [];

        function getRefreshData(){
            $.ajax({
                type: "GET",
                url: "{{ route('admin.shift.create') }}",
                success: function (data) {
                    shifts2 = data;
                    renderCalendarRefresh();
                },
                error: function (data) {
                    swal("Gagal !!", data.responseText, "error")
                }
            });
        }


        function renderCalendarRefresh() {
            calendarGrid.innerHTML = '';
            const weekStart = getWeekStart(currentDate);

            const options = { month: 'short', day: 'numeric' };
            const startStr = new Date(weekStart).toLocaleDateString('id-ID', options);
            const endStr = new Date(new Date(weekStart).setDate(weekStart.getDate() + 6)).toLocaleDateString('id-ID', options);
            weekDisplay.textContent = `${startStr} - ${endStr}`;

            const dayNames = ['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'];

            for (let i = 0; i < 7; i++) {
                const day = new Date(weekStart);
                day.setDate(weekStart.getDate() + i);

                const dayColumn = document.createElement('div');
                dayColumn.className = 'day-column';

                const today = new Date();
                const isToday = day.toDateString() === today.toDateString();

                dayColumn.innerHTML = `
                    <div class="day-header">
                        <div class="day-name">${dayNames[i]}</div>
                        <div class="day-date ${isToday ? 'today' : ''}">${day.getDate()}</div>
                    </div>
                    <div class="shifts-container" id="day-${i}"></div>
                `;
                calendarGrid.appendChild(dayColumn);

                const dayShifts = shifts2.filter(s => s.date === toISODateString(day));
                const shiftsContainer = dayColumn.querySelector('.shifts-container');

                dayShifts.forEach(shift => {
                    const user = users.find(u => u.id === shift.userId);

                    const shiftItem = document.createElement('div');
                    shiftItem.className = 'shift-item';
                    shiftItem.innerHTML = `
                        <div class="user-name">${user.name}</div>
                        <div>${shift.startTime} - ${shift.endTime}</div>
                    `;
                    shiftItem.addEventListener('click', () => openModal(shift));
                    shiftsContainer.appendChild(shiftItem);
                });
            }
        }

        // Modal logic
        function openModal(shift = null) {
            form.reset();
            if (shift) {
                // Edit mode
                modalTitle.textContent = 'Edit Shift';
                shiftIdInput.value = shift.id;
                userSelect.value = shift.userId;
                document.getElementById('shift-date').value = shift.date;
                document.getElementById('start-time').value = shift.startTime;
                document.getElementById('end-time').value = shift.endTime;
                deleteBtn.style.display = 'block';
            } else {
                // Add mode
                modalTitle.textContent = 'Tambah Shift Baru';
                shiftIdInput.value = '';

                document.getElementById('shift-date').value = toISODateString(currentDate);
                document.getElementById('start-time').value = '08:00';
                document.getElementById('end-time').value = '16:00';

                deleteBtn.style.display = 'none';
            }
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        // Helper functions
        function getWeekStart(d) {
            d = new Date(d);
            const day = d.getDay();
            const diff = d.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is sunday
            return new Date(d.setDate(diff));
        }
        function toISODateString(d) {
            return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
        }

        // Fungsi Submit Form dengan AJAX
        async function handleFormSubmit(event) {
            event.preventDefault();
            saveBtn.disabled = true; // Nonaktifkan tombol untuk mencegah klik ganda
            saveBtn.textContent = 'Menyimpan...';

            const id = shiftIdInput.value;
            const shiftData = {
                userId: parseInt(userSelect.value),
                date: document.getElementById('shift-date').value,
                startTime: document.getElementById('start-time').value,
                endTime: document.getElementById('end-time').value,
            };

            const isUpdating = id !== '';
            const url = isUpdating ? "{{ route('admin.shift.update', ':id') }}" : "{{ route('admin.shift.store') }}";
            const method = isUpdating ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(shiftData)
                });

                if (!response.ok) {
                    // Jika server mengembalikan error (misal: 422, 500)
                    showToast('Gagal menyimpan data. Status: ' + response.status, 'error');
                    // throw new Error('Gagal menyimpan data. Status: ' + response.status);
                }

                const savedShift = await response.json(); // Data balikan dari controller

                if (isUpdating) {
                    // Update data di array lokal
                    const index = shifts.findIndex(s => s.id == id);
                    shifts[index] = savedShift;
                } else {
                    // Tambah data baru ke array lokal
                    shifts.push(savedShift);
                }

                // renderCalendar();
                getRefreshData();
                renderCalendarRefresh();
                closeModal();
                showToast('Data berhasil disimpan!', 'success'); // Aktifkan jika punya fungsi toast

            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                // showToast('Terjadi kesalahan saat menyimpan.', 'error'); // Aktifkan jika punya fungsi toast
            } finally {
                saveBtn.disabled = false; // Aktifkan kembali tombol
                saveBtn.textContent = 'Simpan';
            }
        }

        // Fungsi Hapus dengan AJAX
        async function handleDeleteShift() {
            const id = shiftIdInput.value;
            if (!id) return;

            if (confirm('Apakah Anda yakin ingin menghapus shift ini?')) {
                try {
                    const response = await fetch(`{{ route('admin.shift.index') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.status !== 200) {
                        showToast('Gagal menyimpan data. Status: ' + response.status, 'error');
                        // throw new Error('Gagal menghapus data.');
                    }

                    console.log(response);


                    // Hapus data dari array lokal
                    // shifts = shifts.filter(s => s.id != id);
                    // shifts2 = shifts2.filter(s2 => s2.id != id);

                    // renderCalendar();
                    getRefreshData();
                    renderCalendarRefresh();
                    closeModal();
                    showToast('Shift berhasil dihapus.', 'success');

                } catch (error) {
                    // console.error('Terjadi kesalahan:', error);
                    showToast('Terjadi kesalahan saat menghapus shift.', 'error');
                    // showToast('Gagal menghapus shift.', 'error');
                }
            }
        }

        // --- EVENT LISTENERS ---
        form.addEventListener('submit', handleFormSubmit);
        deleteBtn.addEventListener('click', handleDeleteShift);

        // --- EVENT LISTENERS ---
        prevWeekBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() - 7);
            renderCalendar();
        });
        nextWeekBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() + 7);
            renderCalendar();
        });

        addShiftBtn.addEventListener('click', () => openModal());
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

        // --- INISIALISASI ---
        populateUserDropdown();
        renderCalendar();

    </script>
@endpush
</x-layouts>
