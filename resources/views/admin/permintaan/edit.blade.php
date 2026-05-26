@section('title', 'Edit Permintaan')

<x-staradmin>

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
                        
                        <div class="row">
                            <!-- Bagian Data Pasien -->
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
                                            <option value="umum">Pasien Umum</option>
                                            <option value="bpjs">Pasien BPJS Kesehatan</option>
                                            <option value="uhc">Pasien UHC</option>
                                            <option value="bpjs_jasaraharja">Jasa Raharja - BPJS Kesehatan</option>
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

                            <!-- Bagian Detail Permintaan -->
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
                                        <input type="file" class="form-control" id="file" name="file">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file2">File Pendukung 2
                                        {!! $data->file2 ? '<br><a href="' . asset($data->file2) . '" target="_blank" class="badge badge-success mt-1">Lihat File Saat Ini</a>' : '' !!}
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="file2" name="file2">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file3">File Pendukung 3
                                        {!! $data->file3 ? '<br><a href="' . asset($data->file3) . '" target="_blank" class="badge badge-success mt-1">Lihat File Saat Ini</a>' : '' !!}
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="file3" name="file3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('admin.permintaan.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" id="btnSubmit" class="btn btn-primary text-white">Update Pengajuan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
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
            var keterangan = document.getElementById("keterangan").value;
            var indikasi = document.getElementById("indikasi").value;
            var tanggal_masuk = document.getElementById("tanggal_masuk").value;

            // Jika kategori bukan obat, detail obat dikosongkan untuk validasi
            if (kategori !== 'obat') {
                document.getElementById("detail_obat").value = '';
            }

            if (no_rm === "" || nama === "" || jaminan === "" || lokasi === "" || diagnosis === "" || kategori === "" || indikasi === "") {
                showToast('Semua field harus diisi, kecuali file pendukung', 'error');
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
                        $('#btnSubmit').html('Update Pengajuan');
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
            if (q.length === 0) {
                return;
            }

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
            if (rm.length === 0 || cat.length === 0) {
                return;
            }

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

        // Sync contenteditable to hidden textarea
        $(document).on('input', '.obat-input-div', function() {
            var content = $(this).html();
            $('#detail_obat').val(content);
        });

        $(document).on('focus', '.obat-input-div', function() {
            if ($(this).html().trim() === '<br>') $(this).html('');
        });

    </script>
@endpush

</x-staradmin>
