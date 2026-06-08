@section('title', 'Edit Permintaan')

<x-staradmin>
    @push('style')
        <style>
            .paste-zone:hover, .paste-zone:focus {
                border-color: #1f3bb3 !important;
                background-color: #f0f3ff !important;
                color: #1f3bb3 !important;
            }
        </style>
    @endpush

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Formulir Pengajuan Permintaan</h4>
                    <p class="card-description text-danger">
                        <i class="mdi mdi-alert-circle"></i> Perhatian: Persetujuan casemanager hanya diajukan untuk pasien BPJS Kesehatan/UHC. Serta pastikan tidak ada di list auto acc.
                    </p>

                    <form id="pengajuan-form" class="form-sample mt-4">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">
                        <input type="hidden" id="dataId" name="id" value="{{ $data->id }}">

                        
                        {{-- VARIABEL PENGAMAN ROLE --}}
                        @php
                            $isTenagaMedis = auth()->user()->role->name === 'tenagamedis';
                            $isCaseManager = in_array(auth()->user()->role->name, ['casemanager', 'administrator']);
                        @endphp

                        <div class="row">
                            <div class="col-md-6 border-end pe-4">
                                <h5 class="text-primary mb-4 border-bottom pb-2">Data Pasien & Lokasi</h5>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="tanggal_masuk">Tanggal Masuk</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ date('Y-m-d', strtotime($data->tanggal)) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="no_rm">No. Rekam Medis</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="no_rm" name="no_rm" value="{{ $data->no_rm }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="nama">Nama Pasien</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $data->nama }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="jaminan">Status Jaminan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="jaminan" name="jaminan">
                                            @forelse ($penjamin as $item)
                                                <option value="{{ $item->nama }}"
                                                    {{ old('jaminan', $data->jaminan) == $item->nama ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @empty
                                                <option value="">Tidak ada data penjaminan</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="lokasi">Lokasi Ruangan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="lokasi" name="lokasi">
                                            <option value="">Pilih Lokasi...</option>
                                            @foreach ($lokasi as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }} Lt. {{ $item->lantai }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="diagnosis">Diagnosis</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4">{{ $data->diagnosis }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-4">
                                <h5 class="text-primary mb-4 border-bottom pb-2">Detail Permintaan & Berkas</h5>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="kategori">Kategori</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="kategori" name="kategori">
                                            <option value="">Pilih Kategori...</option>
                                            <option value="obat">Obat</option>
                                            <option value="lab">Laboratorium</option>
                                            <option value="rad">Radiologi</option>
                                            <option value="bmhp">BMHP</option>
                                            <option value="darah">Produk Darah</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row d-none" id="riwayat-group">
                                    <label class="col-sm-4 col-form-label" for="riwayat">Riwayat Permintaan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="riwayat" name="riwayat">
                                            <option value="">Pilih Riwayat...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="keterangan">Keterangan</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $data->keterangan }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row {{ $data->kategori == 'obat' ? '' : 'd-none' }}" id="detail-obat-group">
                                    <label class="col-sm-4 col-form-label text-success" for="detail_obat">Detail Obat</label>
                                    <div class="col-sm-8">
                                        <div class="form-control obat-input-div" contenteditable="true" style="min-height: 80px; overflow: auto; resize: vertical;" data-placeholder="Ketik @ untuk tag nama obat, atau ketik teks biasa...">{!! $data->detail_obat !!}</div>
                                        <textarea class="form-control d-none" id="detail_obat" name="detail_obat" rows="3">{{ $data->detail_obat }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="indikasi">Indikasi</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="indikasi" name="indikasi" rows="3">{{ $data->indikasi }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file">File Pendukung 1
                                        {!! $data->file ? '<br><a href="' . asset($data->file) . '" target="_blank" class="badge badge-success mt-1">Lihat File Saat Ini</a>' : '' !!}
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="d-flex gap-2">
                                            <input type="file" class="form-control file-input-control" id="file" name="file" data-preview="preview-file" data-paste="paste-zone-file">
                                            <div class="paste-zone flex-shrink-0" id="paste-zone-file" tabindex="0" style="border: 2px dashed #ccc; border-radius: 4px; padding: 6px 12px; text-align: center; cursor: pointer; background: #fafafa; font-size: 0.8rem; color: #6c757d; outline: none; min-width: 130px; height: 38px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                                <i class="mdi mdi-content-paste me-1"></i> Paste (Ctrl+V)
                                            </div>
                                        </div>
                                        <div class="preview-container mt-2 align-items-center" id="preview-file" style="display:none; gap: 10px;">
                                            <img src="" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px; display: block;" class="img-thumbnail">
                                            <div>
                                                <button type="button" class="btn btn-xs btn-danger text-white btn-clear-file" data-target="file">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file2">File Pendukung 2
                                        {!! $data->file2 ? '<br><a href="' . asset($data->file2) . '" target="_blank" class="badge badge-success mt-1">Lihat File Saat Ini</a>' : '' !!}
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="d-flex gap-2">
                                            <input type="file" class="form-control file-input-control" id="file2" name="file2" data-preview="preview-file2" data-paste="paste-zone-file2">
                                            <div class="paste-zone flex-shrink-0" id="paste-zone-file2" tabindex="0" style="border: 2px dashed #ccc; border-radius: 4px; padding: 6px 12px; text-align: center; cursor: pointer; background: #fafafa; font-size: 0.8rem; color: #6c757d; outline: none; min-width: 130px; height: 38px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                                <i class="mdi mdi-content-paste me-1"></i> Paste (Ctrl+V)
                                            </div>
                                        </div>
                                        <div class="preview-container mt-2 align-items-center" id="preview-file2" style="display:none; gap: 10px;">
                                            <img src="" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px; display: block;" class="img-thumbnail">
                                            <div>
                                                <button type="button" class="btn btn-xs btn-danger text-white btn-clear-file" data-target="file2">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file3">File Pendukung 3
                                        {!! $data->file3 ? '<br><a href="' . asset($data->file3) . '" target="_blank" class="badge badge-success mt-1">Lihat File Saat Ini</a>' : '' !!}
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="d-flex gap-2">
                                            <input type="file" class="form-control file-input-control" id="file3" name="file3" data-preview="preview-file3" data-paste="paste-zone-file3">
                                            <div class="paste-zone flex-shrink-0" id="paste-zone-file3" tabindex="0" style="border: 2px dashed #ccc; border-radius: 4px; padding: 6px 12px; text-align: center; cursor: pointer; background: #fafafa; font-size: 0.8rem; color: #6c757d; outline: none; min-width: 130px; height: 38px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                                <i class="mdi mdi-content-paste me-1"></i> Paste (Ctrl+V)
                                            </div>
                                        </div>
                                        <div class="preview-container mt-2 align-items-center" id="preview-file3" style="display:none; gap: 10px;">
                                            <img src="" style="max-height: 80px; border: 1px solid #ddd; border-radius: 4px; display: block;" class="img-thumbnail">
                                            <div>
                                                <button type="button" class="btn btn-xs btn-danger text-white btn-clear-file" data-target="file3">Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ========================================================================= --}}
                                {{-- MENU RESPONS ACC / TOLAK (HANYA MUNCUL UNTUK CASE MANAGER & ADMINISTRATOR) --}}
                                {{-- ========================================================================= --}}
                                @if($isCaseManager)
                                    <div class="mt-4 pt-3 border-top border-warning">
                                        <h5 class="text-warning mb-3"><i class="mdi mdi-gavel"></i> Keputusan Case Manager</h5>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="status">Respon Status</label>
                                            <div class="col-sm-8">
                                                <select class="form-select border-warning" id="status" name="status">
                                                    <option value="menunggu" {{ $data->status == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                                    <option value="disetujui" {{ $data->status == 'disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                                                    <option value="ditolak" {{ $data->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    <option value="dibatalkan" {{ $data->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="catatan_diterima">Catatan / Alasan</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control border-warning" id="catatan_diterima" name="catatan_diterima" rows="3" placeholder="Masukkan catatan persetujuan atau alasan penolakan di sini...">{{ $data->catatan_diterima }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- ========================================================================= --}}

                            </div>
                        </div>

                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('admin.permintaan.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" id="btnSubmit" class="btn btn-primary text-white">
                                    {{ $isCaseManager ? 'Simpan Keputusan' : 'Update Pengajuan' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
        // Set awal data select
        $('#jaminan').val("{{ $data->ruangan }}");
        $('#lokasi').val("{{ $data->lokasi_id }}");
        $('#kategori').val("{{ $data->kategori }}");

        function validasiForm() {
            var no_rm = document.getElementById("no_rm").value;
            var nama = document.getElementById("nama").value;
            var jaminan = document.getElementById("jaminan").value;
            var lokasi = document.getElementById("lokasi").value;
            var diagnosis = document.getElementById("diagnosis").value;
            var kategori = document.getElementById("kategori").value;
            var indikasi = document.getElementById("indikasi").value;
            var detail_obat = document.getElementById("detail_obat").value;

            if (kategori !== 'obat') {
                document.getElementById("detail_obat").value = '';
                detail_obat = '';
            }

            if (no_rm === "" || nama === "" || jaminan === "" || lokasi === "" || diagnosis === "" || kategori === "" || (kategori === 'obat' && detail_obat === "") || indikasi === "") {
                showToast('Semua field data medis harus diisi, kecuali file pendukung', 'error');
                return false;
            }

            return true;
        }

        $('#btnSubmit').click(function (e) {
            e.preventDefault();

            if (validasiForm()) {
                $('#btnSubmit').prop('disabled', true);
                $('#btnSubmit').html('Loading <i class="mdi mdi-loading mdi-spin"></i>');

                var formData = new FormData($('#pengajuan-form')[0]);

                $.ajax({
                    data: formData,
                    url: "{{ route('admin.update-permintaan') }}",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status === 'error'){
                            showToast(data.message, 'error')
                            return false;
                        }

                        showToast(data.message)
                        window.location.href = "{{ route('admin.permintaan.index') }}";
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                    },
                    complete: function() {
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html("{{ $isCaseManager ? 'Simpan Keputusan' : 'Update Pengajuan' }}");
                    }
                });
            }
        });

        // Search Pasien by RM
        function debounce(fn, delay) {
            let t;
            return function (...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        function fetchSearch(q) {
            return $.getJSON("{{ route('admin.permintaan.search.rm') }}", { q: q });
        }

        const handleSearch = debounce(function () {
            const q = $('#no_rm').val().trim();
            if (q.length === 0) return;

            fetchSearch(q).done(function (resp) {
                if (resp.status === 'success') {
                    $('#nama').val(resp.data.nama);
                    $('#jaminan').val(resp.data.jaminan_id);
                    $('#lokasi').val(resp.data.lokasi_id);
                    $('#diagnosis').val(resp.data.diagnosis);
                }
            });
        }, 1000);

        $('#no_rm').on('input', handleSearch);

        // Riwayat by MR and category
        function fetchRiwayat(rm, cat) {
            return $.getJSON("{{ route('admin.permintaan.riwayat.rm') }}", { rm: rm, cat: cat });
        }

        const handleRiwayat = debounce(function () {
            const rm = $('#no_rm').val().trim();
            const cat = $('#kategori').val().trim();
            if (rm.length === 0 || cat.length === 0) return;

            fetchRiwayat(rm, cat).done(function (resp) {
                if (resp.status === 'success') {
                    $('#riwayat').empty();
                    $('#riwayat').append('<option value="">Pilih Riwayat...</option>');

                    if (resp.data.length > 0) {
                        $('#riwayat-group').removeClass('d-none');
                        $.each(resp.data, function(key, value) {
                            $('#riwayat').append('<option data-obat="'+ (value.detail_obat || '') +'" value="'+ value.keterangan + ' | ' + value.indikasi +'">' + value.kategori + ' | ' + value.tanggal + ' | '+ value.keterangan + '</option>');
                        });
                    }else{
                        $('#riwayat-group').addClass('d-none');
                    }
                }else{
                    $('#riwayat-group').addClass('d-none');
                }
            }).fail(function () {
                $('#riwayat-group').addClass('d-none');
            });
        }, 1000);

        $('#kategori').on('change', function () {
            handleRiwayat();
            if ($(this).val() === 'obat') {
                $('#detail-obat-group').removeClass('d-none');
            } else {
                $('#detail-obat-group').addClass('d-none');
            }
        });

        $('#riwayat').on('change', function () {
            var keterangan = $('#riwayat').val().split(' | ')[0];
            var indikasi = $('#riwayat').val().split(' | ')[1];
            var detailObat = $(this).find(':selected').data('obat') || '';
            $('#keterangan').val(keterangan);
            $('#indikasi').val(indikasi);
            if ($('#kategori').val() === 'obat') {
                $('#detail_obat').val(detailObat);
                $('.obat-input-div').html(detailObat);
            }
        });

        // --- Tribute.js Logic ---
        var tribute = new Tribute({
            trigger: '@',
            values: function (text, cb) {
                if(text.length < 2) return cb([]);
                $.ajax({
                    url: "{{ route('admin.obat.search') }}",
                    data: { q: text },
                    dataType: 'json',
                    success: function (data) {
                        var mapped = data.map(function (item) {
                            var generic = item.nama_generik ? ' - ' + item.nama_generik : '';
                            return {
                                key: item.nama_item + ' (' + item.kode_item + ')' + generic,
                                value: item.nama_item,
                                warna: item.warna
                            };
                        });
                        cb(mapped);
                    }
                });
            },
            selectTemplate: function (item) {
                if (typeof item === 'undefined') return null;
                var badgeClass = 'bg-secondary';
                if (item.original.warna === 'hijau') badgeClass = 'bg-success';
                else if (item.original.warna === 'kuning') badgeClass = 'bg-warning text-dark';
                else if (item.original.warna === 'merah') badgeClass = 'bg-danger';

                return '<span contenteditable="false" class="badge ' + badgeClass + '">' + item.original.value + '</span> ';
            },
            menuItemTemplate: function (item) {
                return item.string;
            },
            replaceTextSuffix: ''
        });

        tribute.attach(document.querySelectorAll('.obat-input-div'));

        $(document).on('input', '.obat-input-div', function() {
            var content = $(this).html();
            $('#detail_obat').val(content);
        });

        $(document).on('focus', '.obat-input-div', function() {
            if ($(this).html().trim() === '<br>') $(this).html('');
        });

        // --- Logika Paste & Pratinjau File Pendukung ---
        function showFilePreview(file, previewId) {
            const previewEl = $(`#${previewId}`);
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewEl.find('img').attr('src', e.target.result).show();
                    previewEl.css('display', 'flex');
                };
                reader.readAsDataURL(file);
            } else {
                previewEl.find('img').hide();
                previewEl.hide();
            }
        }

        $(document).on('change', '.file-input-control', function() {
            const file = this.files[0];
            const previewId = $(this).data('preview');
            showFilePreview(file, previewId);
        });

        $(document).on('paste', '.paste-zone', function(e) {
            const inputId = $(this).attr('id').replace('paste-zone-', '');
            const inputEl = document.getElementById(inputId);
            const previewId = $(inputEl).data('preview');
            
            const clipboardData = e.clipboardData || e.originalEvent.clipboardData;
            const items = clipboardData.items;
            
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf("image") !== -1) {
                    const blob = items[i].getAsFile();
                    const file = new File([blob], "pasted_image_" + Date.now() + ".png", { type: blob.type });
                    
                    const container = new DataTransfer();
                    container.items.add(file);
                    inputEl.files = container.files;
                    
                    showFilePreview(file, previewId);
                    showToast('Gambar berhasil di-paste', 'success');
                    e.preventDefault();
                    break;
                }
            }
        });

        $(document).on('click', '.btn-clear-file', function() {
            const inputId = $(this).data('target');
            const inputEl = document.getElementById(inputId);
            const previewId = $(inputEl).data('preview');
            
            inputEl.value = '';
            $(`#${previewId}`).hide();
        });
    </script>
@endpush

</x-staradmin>
