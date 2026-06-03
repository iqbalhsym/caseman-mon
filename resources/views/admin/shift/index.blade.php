@section('title', 'Shift')

<x-staradmin>

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .week-display {
            font-weight: 600;
            font-size: 1rem;
            min-width: 180px;
            text-align: center;
        }
        .calendar-nav button:not(#add-shift-btn) {
            background-color: #f4f5f7;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 4px 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .calendar-nav button:not(#add-shift-btn):hover {
            background-color: #e0e0e0;
        }
        .day-column {
            flex: 1;
            border-right: 1px solid #e0e0e0;
            min-height: 350px;
        }
        .day-column:last-child { border-right: none; }
        .day-header {
            text-align: center;
            padding: 10px 8px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }
        .day-name { font-size: 0.75rem; font-weight: 700; color: #6c757d; }
        .day-date {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .day-date.today {
            background-color: #1f3bb3;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 4px auto;
        }
        .shift-item {
            background-color: #e0e9f8;
            border-left: 3px solid #1f3bb3;
            padding: 6px 8px;
            margin: 6px 6px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.78rem;
            transition: all 0.15s;
        }
        .shift-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .shift-item .user-name {
            font-weight: 600;
            margin-bottom: 1px;
        }
        /* Select2 fix inside modal */
        .select2-container { width: 100% !important; }
        .select2-dropdown { z-index: 9999; }
        @media (max-width: 768px) {
            .calendar-nav { flex-wrap: wrap; justify-content: center; }
            .calendar-nav button#add-shift-btn { width: 100%; margin-top: 8px; }
            .day-name { font-size: 0.65rem; }
        }
    </style>
@endpush

    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Jadwal Shift</a>
                        </li>
                    </ul>
                </div>
                
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                            <div class="calendar-nav">
                                                <button id="prev-week-btn"><i class="mdi mdi-chevron-left"></i></button>
                                                <div class="week-display" id="week-display"></div>
                                                <button id="next-week-btn"><i class="mdi mdi-chevron-right"></i></button>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm text-white mb-0" id="add-shift-btn">
                                                <i class="mdi mdi-calendar-plus"></i> Tambah Shift
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <div class="calendar-grid-container border rounded">
                                                <div class="calendar-grid d-flex" id="calendar-grid">
                                                    <!-- Kalender diisi oleh JS -->
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

    <!-- Bootstrap 5 Modal -->
    <div class="modal fade" id="shift-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Shift Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="shift-form">
                    <div class="modal-body">
                        <input type="hidden" id="shift-id">
                        
                        <div class="form-group">
                            <label for="user-select">Pengguna</label>
                            <select class="form-control" id="user-select" required>
                                <option value="">-- Cari & pilih pengguna --</option>
                            </select>
                            <small class="text-muted">Ketik nama untuk mencari pengguna.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="shift-dates">Tanggal (bisa pilih lebih dari satu)</label>
                            <input type="text" class="form-control bg-white" id="shift-dates" placeholder="Pilih tanggal..." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="lantai-select">Lokasi Lantai (bisa pilih lebih dari satu)</label>
                            <select class="form-control" id="lantai-select" name="lantai[]" multiple="multiple" required style="width: 100%">
                                @foreach($lantais as $lantai)
                                    <option value="{{ $lantai }}">Lantai {{ $lantai }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="start-time">Jam Mulai</label>
                                <input type="time" class="form-control" id="start-time" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="end-time">Jam Selesai</label>
                                <input type="time" class="form-control" id="end-time" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white me-auto" id="delete-shift-btn" style="display: none;">Hapus</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="save-btn" class="btn btn-primary text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const users = @json($users);
        const shifts = @json($shifts);

        const calendarGrid = document.getElementById('calendar-grid');
        const weekDisplay = document.getElementById('week-display');
        const prevWeekBtn = document.getElementById('prev-week-btn');
        const nextWeekBtn = document.getElementById('next-week-btn');
        const addShiftBtn = document.getElementById('add-shift-btn');
        const shiftModal = new bootstrap.Modal(document.getElementById('shift-modal'));
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('shift-form');
        const userSelect = document.getElementById('user-select');
        const shiftIdInput = document.getElementById('shift-id');
        const deleteBtn = document.getElementById('delete-shift-btn');
        const saveBtn = document.getElementById('save-btn');

        let currentDate = new Date();

        const shiftDatesInput = document.getElementById('shift-dates');
        const flatpickrInstance = flatpickr(shiftDatesInput, {
            mode: "multiple",
            dateFormat: "Y-m-d",
            disableMobile: "true"
        });

        // Init Select2 on user-select and lantai-select
        function initSelect2() {
            const selectData = [{ id: '', text: '-- Cari & pilih pengguna --' }].concat(
                users.map(u => ({ id: u.id, text: `${u.name} (${u.role})` }))
            );
            $('#user-select').select2({
                theme: 'bootstrap-5',
                data: selectData,
                dropdownParent: $('#shift-modal'),
                placeholder: 'Ketik nama untuk mencari...',
                allowClear: true,
                width: '100%',
            });

            $('#lantai-select').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#shift-modal'),
                placeholder: '-- Pilih Lantai --',
                allowClear: true,
                width: '100%',
            });
        }

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
                        <div class="day-name fw-bold">${dayNames[i]}</div>
                        <div class="day-date ${isToday ? 'today' : ''} mt-1">${day.getDate()}</div>
                    </div>
                    <div class="shifts-container p-2" id="day-${i}"></div>
                `;
                calendarGrid.appendChild(dayColumn);

                const dayShifts = shifts.filter(s => s.date === toISODateString(day));
                const shiftsContainer = dayColumn.querySelector('.shifts-container');

                dayShifts.forEach(shift => {
                    const user = users.find(u => u.id === shift.userId);

                    const shiftItem = document.createElement('div');
                    shiftItem.className = 'shift-item';
                    shiftItem.innerHTML = `
                        <div class="user-name">${user ? user.name : 'Unknown'}</div>
                        <div class="text-muted"><i class="mdi mdi-map-marker-outline"></i> Lt. ${shift.lantai || '-'}</div>
                        <div class="text-muted"><i class="mdi mdi-clock-outline"></i> ${shift.startTime} - ${shift.endTime}</div>
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
                    Swal.fire("Gagal !!", data.responseText, "error")
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
                        <div class="day-name fw-bold">${dayNames[i]}</div>
                        <div class="day-date ${isToday ? 'today' : ''} mt-1">${day.getDate()}</div>
                    </div>
                    <div class="shifts-container p-2" id="day-${i}"></div>
                `;
                calendarGrid.appendChild(dayColumn);

                const dayShifts = shifts2.filter(s => s.date === toISODateString(day));
                const shiftsContainer = dayColumn.querySelector('.shifts-container');

                dayShifts.forEach(shift => {
                    const user = users.find(u => u.id === shift.userId);

                    const shiftItem = document.createElement('div');
                    shiftItem.className = 'shift-item';
                    shiftItem.innerHTML = `
                        <div class="user-name">${user ? user.name : 'Unknown'}</div>
                        <div class="text-muted"><i class="mdi mdi-map-marker-outline"></i> Lt. ${shift.lantai || '-'}</div>
                        <div class="text-muted"><i class="mdi mdi-clock-outline"></i> ${shift.startTime} - ${shift.endTime}</div>
                    `;
                    shiftItem.addEventListener('click', () => openModal(shift));
                    shiftsContainer.appendChild(shiftItem);
                });
            }
        }

        function openModal(shift = null) {
            form.reset();
            flatpickrInstance.clear();
            $('#user-select').val('').trigger('change'); // reset Select2
            $('#lantai-select').val([]).trigger('change'); // reset Select2

            if (shift) {
                modalTitle.textContent = 'Edit Shift';
                shiftIdInput.value = shift.id;
                $('#user-select').val(shift.userId).trigger('change'); // set Select2
                flatpickrInstance.setDate(shift.date);
                document.getElementById('start-time').value = shift.startTime;
                document.getElementById('end-time').value = shift.endTime;
                
                // Set multiple Select2 value for lantai-select
                if (shift.lantai) {
                    const lantaiArr = typeof shift.lantai === 'string' ? shift.lantai.split(',') : [shift.lantai];
                    $('#lantai-select').val(lantaiArr).trigger('change');
                } else {
                    $('#lantai-select').val([]).trigger('change');
                }
                
                deleteBtn.style.display = 'block';
                shiftDatesInput.disabled = true;
            } else {
                modalTitle.textContent = 'Tambah Shift Baru';
                shiftIdInput.value = '';
                flatpickrInstance.setDate(toISODateString(currentDate));
                document.getElementById('start-time').value = '08:00';
                document.getElementById('end-time').value = '16:00';
                deleteBtn.style.display = 'none';
                shiftDatesInput.disabled = false;
            }
            shiftModal.show();
        }

        function closeModal() {
            shiftModal.hide();
        }

        function getWeekStart(d) {
            d = new Date(d);
            const day = d.getDay();
            const diff = d.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(d.setDate(diff));
        }
        function toISODateString(d) {
            return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
        }

        async function handleFormSubmit(event) {
            event.preventDefault();
            saveBtn.disabled = true;
            saveBtn.innerHTML = 'Menyimpan <i class="mdi mdi-loading mdi-spin"></i>';

            const id = shiftIdInput.value;
            const isUpdating = id !== '';

            const payload = {
                userId: parseInt($('#user-select').val()),
                startTime: document.getElementById('start-time').value,
                endTime: document.getElementById('end-time').value,
                lantai: $('#lantai-select').val(),
            };

            let url;
            let method;

            if (isUpdating) {
                url = `{{ url('admin/shift') }}/${id}`;
                method = 'PUT';
            } else {
                const selectedDates = shiftDatesInput.value.split(', ').filter(date => date);
                payload.dates = selectedDates;
                url = "{{ route('admin.shift.store') }}";
                method = 'POST';
            }

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok || result.status === 'error') {
                    let errorMessage = result.message || 'Gagal menyimpan data.';
                    if (result.errors) {
                        errorMessage += '\n' + Object.values(result.errors).join('\n');
                    }
                    Swal.fire("Gagal", errorMessage, "error");
                } else {
                    getRefreshData();
                    closeModal();
                    const successMessage = isUpdating ? 'Shift berhasil diperbarui.' : 'Shift berhasil disimpan.';
                    showToast(successMessage, 'success');
                }

            } catch (error) {
                console.error('Terjadi kesalahan:', error);
                Swal.fire("Gagal", 'Terjadi kesalahan saat berkomunikasi dengan server.', "error");
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = 'Simpan';
            }
        }

        async function handleDeleteShift() {
            const id = shiftIdInput.value;
            if (!id) return;

            Swal.fire({
                title: 'Yakin Ingin Menghapus ?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f3bb3',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(async function(result) {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`{{ route('admin.shift.index') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            showToast('Gagal menghapus data. Status: ' + response.status, 'error');
                        } else {
                            getRefreshData();
                            closeModal();
                            Swal.fire("Berhasil", "Shift berhasil dihapus.", "success");
                        }
                    } catch (error) {
                        showToast('Terjadi kesalahan saat menghapus shift.', 'error');
                    }
                }
            })
        }

        form.addEventListener('submit', handleFormSubmit);
        deleteBtn.addEventListener('click', handleDeleteShift);
        prevWeekBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() - 7);
            renderCalendar();
        });
        nextWeekBtn.addEventListener('click', () => {
            currentDate.setDate(currentDate.getDate() + 7);
            renderCalendar();
        });
        addShiftBtn.addEventListener('click', () => openModal());

        initSelect2();     // <- ganti populateUserDropdown
        renderCalendar();

    </script>
@endpush

</x-staradmin>
