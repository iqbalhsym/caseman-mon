@section('title', 'Buat Permintaan')

<x-staradmin>

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Formulir Pengajuan Permintaan</h4>
                    <p class="card-description text-danger">
                        <i class="mdi mdi-alert-circle"></i> Perhatian: Persetujuan casemanager hanya diajukan untuk pasien BPJS Kesehatan/UHC. Serta pastikan tidak ada di list auto acc.
                    </p>
                    
                    <form id="pengajuan-form" class="form-sample mt-4">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">
                        
                        <div class="row">
                            <!-- Bagian Data Pasien -->
                            <div class="col-md-6 border-end pe-4">
                                <h5 class="text-primary mb-4 border-bottom pb-2">Data Pasien & Lokasi</h5>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="tanggal_masuk">Tanggal Masuk</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="no_rm">No. Rekam Medis</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="no_rm" name="no_rm" placeholder="Masukkan No. RM...">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="nama">Nama Pasien</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama pasien...">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="jaminan">Status Jaminan</label>
                                    <div class="col-sm-8">
                                        <select class="form-select" id="jaminan" name="jaminan">
                                            <option value="">Pilih Jaminan...</option>
                                            @foreach ($penjamin as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
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
                                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" placeholder="Masukkan diagnosis pasien..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Detail Permintaan -->
                            <div class="col-md-6 ps-4">
                                <h5 class="text-primary mb-4 border-bottom pb-2">Detail Permintaan & Berkas</h5>
                                
                                <div id="paket-container">
                                    <div class="paket-item border rounded p-3 mb-3 bg-light position-relative">
                                        <h6 class="text-secondary mb-3">Paket Detail 1</h6>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Kategori</label>
                                            <div class="col-sm-8">
                                                <select class="form-select kategori-input" name="kategori[]" required>
                                                    <option value="">Pilih Kategori...</option>
                                                    <option value="obat">Obat</option>
                                                    <option value="lab">Laboratorium</option>
                                                    <option value="rad">Radiologi</option>
                                                    <option value="bmhp">BMHP</option>
                                                    <option value="darah">Produk Darah</option>
                                                    <option value="lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row d-none riwayat-group">
                                            <label class="col-sm-4 col-form-label">Riwayat Permintaan</label>
                                            <div class="col-sm-8">
                                                <select class="form-select riwayat-input" name="riwayat[]">
                                                    <option value="">Pilih Riwayat...</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control keterangan-input" name="keterangan[]" rows="3" placeholder="Jelaskan detail permintaan Anda..." required></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Indikasi</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control indikasi-input" name="indikasi[]" rows="3" placeholder="Jelaskan indikasi medis..." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="button" id="btn-tambah-paket" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-plus"></i> Tambah Detail Paket</button>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file">File Pendukung 1</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="file" name="file">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file2">File Pendukung 2</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="file2" name="file2">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="file3">File Pendukung 3</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" id="file3" name="file3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4 pt-4 border-top">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('admin.permintaan.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" id="btnSubmit" class="btn btn-primary text-white">Kirim Pengajuan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
        $('#no_rm').focus();

        function validasiForm() {
            var no_rm = document.getElementById("no_rm").value;
            var nama = document.getElementById("nama").value;
            var jaminan = document.getElementById("jaminan").value;
            var lokasi = document.getElementById("lokasi").value;
            var diagnosis = document.getElementById("diagnosis").value;
            
            var valid = true;
            $('.kategori-input, .keterangan-input, .indikasi-input').each(function() {
                if ($(this).val().trim() === '') {
                    valid = false;
                }
            });

            if (no_rm === "" || nama === "" || jaminan === "" || lokasi === "" || diagnosis === "" || !valid) {
                showToast('Semua field utama dan detail paket harus diisi', 'error');
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
                    url: "{{ route('admin.permintaan.store') }}",
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

                        showToast(data.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.permintaan.index') }}";
                        }, 1000);
                    },
                    error: function (data) {
                        showToast(data.responseText, 'error');
                    },
                    complete: function() {
                        $('#btnSubmit').prop('disabled', false)
                        $('#btnSubmit').html('Kirim Pengajuan')
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

        const handleSearch = function () {
            const q = $('#no_rm').val().trim();
            if (q.length === 0) return;

            // Show loading state
            $('#nama').attr('placeholder', 'Mencari data...');

            fetchSearch(q).done(function (resp) {
                if (resp.status === 'success') {
                    // Always update name if found
                    $('#nama').val(resp.data.nama);
                    
                    // Only fill others if empty to avoid overwriting manual input
                    if (!$('#jaminan').val()) $('#jaminan').val(resp.data.jaminan_id);
                    if (!$('#lokasi').val()) $('#lokasi').val(resp.data.lokasi_id);
                    if (!$('#diagnosis').val()) $('#diagnosis').val(resp.data.diagnosis);
                    
                    showToast('Data pasien ditemukan', 'success');
                } else {
                    $('#nama').attr('placeholder', 'Masukkan nama pasien...');
                }
            }).fail(function () {
                $('#nama').attr('placeholder', 'Masukkan nama pasien...');
            });
        };

        $('#no_rm').on('blur', handleSearch);

        // Riwayat by MR and category
        function fetchRiwayat(rm, cat) {
            return $.getJSON("{{ route('admin.permintaan.riwayat.rm') }}", { rm: rm, cat: cat });
        }

        $(document).on('change', '.kategori-input', function() {
            const container = $(this).closest('.paket-item');
            const rm = $('#no_rm').val().trim();
            const cat = $(this).val().trim();
            const riwayatGroup = container.find('.riwayat-group');
            const riwayatInput = container.find('.riwayat-input');

            if (rm.length === 0 || cat.length === 0) {
                return;
            }

            fetchRiwayat(rm, cat).done(function (resp) {
                if (resp.status === 'success') {
                    riwayatInput.empty();
                    riwayatInput.append('<option value="">Pilih Riwayat...</option>');

                    if (resp.data.length > 0) {
                        riwayatGroup.removeClass('d-none');
                        $.each(resp.data, function(key, value) {
                            riwayatInput.append('<option value="'+ value.keterangan + ' | ' + value.indikasi +'">' + value.kategori + ' | ' + value.tanggal + ' | '+ value.keterangan + '</option>');
                        });
                    }else{
                        riwayatGroup.addClass('d-none');
                    }
                }else{
                    riwayatGroup.addClass('d-none');
                }
            }).fail(function () {
                riwayatGroup.addClass('d-none');
            });
        });

        $(document).on('change', '.riwayat-input', function () {
            const container = $(this).closest('.paket-item');
            if ($(this).val()) {
                var keterangan = $(this).val().split(' | ')[0];
                var indikasi = $(this).val().split(' | ')[1];
                container.find('.keterangan-input').val(keterangan);
                container.find('.indikasi-input').val(indikasi);
            }
        });

        // Dynamic Package Logic
        let paketCount = 1;
        const maxPaket = 3;

        $('#btn-tambah-paket').click(function() {
            if (paketCount >= maxPaket) {
                showToast('Maksimal hanya 3 paket detail', 'warning');
                return;
            }

            paketCount++;
            
            const newPaket = `
                <div class="paket-item border rounded p-3 mb-3 bg-light position-relative mt-3">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-hapus-paket"><i class="mdi mdi-close"></i> Hapus</button>
                    <h6 class="text-secondary mb-3">Paket Detail ${paketCount}</h6>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Kategori</label>
                        <div class="col-sm-8">
                            <select class="form-select kategori-input" name="kategori[]" required>
                                <option value="">Pilih Kategori...</option>
                                <option value="obat">Obat</option>
                                <option value="lab">Laboratorium</option>
                                <option value="rad">Radiologi</option>
                                <option value="bmhp">BMHP</option>
                                <option value="darah">Produk Darah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row d-none riwayat-group">
                        <label class="col-sm-4 col-form-label">Riwayat Permintaan</label>
                        <div class="col-sm-8">
                            <select class="form-select riwayat-input" name="riwayat[]">
                                <option value="">Pilih Riwayat...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea class="form-control keterangan-input" name="keterangan[]" rows="3" placeholder="Jelaskan detail permintaan Anda..." required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Indikasi</label>
                        <div class="col-sm-8">
                            <textarea class="form-control indikasi-input" name="indikasi[]" rows="3" placeholder="Jelaskan indikasi medis..." required></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            $('#paket-container').append(newPaket);
            updatePaketLabels();
            
            if (paketCount >= maxPaket) {
                $(this).hide();
            }
        });

        $(document).on('click', '.btn-hapus-paket', function() {
            $(this).closest('.paket-item').remove();
            paketCount--;
            updatePaketLabels();
            
            if (paketCount < maxPaket) {
                $('#btn-tambah-paket').show();
            }
        });

        function updatePaketLabels() {
            $('.paket-item').each(function(index) {
                $(this).find('h6').text('Paket Detail ' + (index + 1));
            });
        }

    </script>
@endpush

</x-staradmin>
