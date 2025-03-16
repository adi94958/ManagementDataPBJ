@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Berkas & Tagihan PBJ')
@section('script_head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Berkas & Tagihan PBJ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Berkas & Tagihan PBJ</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div>
                <a href="{{ route('berkas_pbj.add') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Berkas & Tagihan PBJ
                </a>
            </div>
        </div>
        <div class="card-body p-0" style="margin: 20px">
            <table id="previewBerkasPBJ" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>Nomor Kontrak</th>
                        <th>Nama Kontrak</th>
                        <th>Tanggal Kontrak</th>
                        <th>Jangka Waktu Kontrak</th>
                        <th>Nilai Kontrak</th>
                        <th>Nama Vendor</th>
                        <th>BAPP</th>
                        <th>BASTP</th>
                        <th>PHO</th>
                        <th>FHO</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal BAPP -->
<div class="modal fade" id="bappModal" tabindex="-1" role="dialog" aria-labelledby="bappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="bappModalLabel">Detail BAPP</h5>
                <button type="button" class="btn-close modal-close-btn" onclick="closeModal('bappModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Card untuk detail BAPP -->
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor Permohonan dari Vendor</span>
                                        <span class="info-box-number" id="bappNomorPermohonan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal Permohonan dari Vendor</span>
                                        <span class="info-box-number" id="bappTanggalPermohonan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor BAPP</span>
                                        <span class="info-box-number" id="nomorBAPP">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal BASTP</span>
                                        <span class="info-box-number" id="tanggalBAPP">-</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Jumlah Bayar Termin 1</span>
                                        <span class="info-box-number" id="bappJumlahBayarTermin1">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Saldo</span>
                                        <span class="info-box-number" id="bappSaldo">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Jangka Waktu Pemeliharaan</span>
                                        <span class="info-box-number" id="bappJangkaWaktuPemeliharaan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn" onclick="closeModal('bappModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal BASTP -->
<div class="modal fade" id="bastpModal" tabindex="-1" role="dialog" aria-labelledby="bastpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="bastpModalLabel">Detail BASTP</h5>
                <button type="button" class="btn-close modal-close-btn" onclick="closeModal('bastpModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Card untuk detail BASTP -->
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor Permohonan</span>
                                        <span class="info-box-number" id="bastpNomorPermohonan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal Permohonan</span>
                                        <span class="info-box-number" id="bastpTanggalPermohonan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor BASTP</span>
                                        <span class="info-box-number" id="nomorBASTP">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal BASTP</span>
                                        <span class="info-box-number" id="tanggalBASTP">-</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Jumlah Bayar Termin 1</span>
                                        <span class="info-box-number" id="bastpJumlahBayarTermin1">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Saldo</span>
                                        <span class="info-box-number" id="bastpSaldo">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Jangka Waktu Pemeliharaan</span>
                                        <span class="info-box-number" id="bastpJangkaWaktuPemeliharaan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn" onclick="closeModal('bastpModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal PHO -->
<div class="modal fade" id="phoModal" tabindex="-1" role="dialog" aria-labelledby="phoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="phoModalLabel">Detail PHO</h5>
                <button type="button" class="btn-close modal-close-btn" onclick="closeModal('phoModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Card untuk detail PHO -->
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor BA Pemeriksaan Pekerjaan PHO</span>
                                        <span class="info-box-number" id="phoNomorBaPemeriksaan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal BA Pemeriksaan Pekerjaan PHO</span>
                                        <span class="info-box-number" id="phoTanggalBaPemeriksaan">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor BA Serah Terima PHO</span>
                                        <span class="info-box-number" id="phoNomorBaSerahTerima">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal BA Serah Terima PHO</span>
                                        <span class="info-box-number" id="phoTanggalBaSerahTerima">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn" onclick="closeModal('phoModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal FHO -->
<div class="modal fade" id="fhoModal" tabindex="-1" role="dialog" aria-labelledby="fhoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="fhoModalLabel">Detail FHO</h5>
                <button type="button" class="btn-close modal-close-btn" onclick="closeModal('fhoModal')" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Card untuk detail FHO -->
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom -->
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor Surat Permohonan FHO Vendor</span>
                                        <span class="info-box-number" id="fhoNomorSuratPermohonan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal Surat Permohonan FHO Vendor</span>
                                        <span class="info-box-number" id="fhoTanggalSuratPermohonan">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Nomor Surat Laporan Tindak Lanjut Perbaikan FHO</span>
                                        <span class="info-box-number" id="fhoNomorSuratLaporan">-</span>
                                    </div>
                                </div>
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tanggal Surat Laporan Tindak Lanjut Perbaikan FHO</span>
                                        <span class="info-box-number" id="fhoTanggalSuratLaporan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-close-btn" onclick="closeModal('fhoModal')">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_footer')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#previewBerkasPBJ').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "{{ route('berkas_pbj.dataTable') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    _token: "{{csrf_token()}}"
                }
            },
            "columns": [{
                    "data": "nomor_kontrak"
                },
                {
                    "data": "nama_kontrak"
                },
                {
                    "data": "tanggal_kontrak_mulai"
                },
                {
                    "data": "tanggal_kontrak_selesai",
                    "render": function(data, type, row) {
                        // Mendapatkan tanggal selesai kontrak dari data
                        let tanggalSelesai = row.tanggal_kontrak_selesai;

                        if (!tanggalSelesai) {
                            return "Tanggal selesai tidak tersedia";
                        }

                        // Mengkonversi string tanggal ke objek Date
                        let endDate = new Date(tanggalSelesai);
                        let today = new Date();

                        // Menghitung selisih dalam milisecond
                        let timeDiff = endDate - today;

                        // Menghitung selisih dalam hari
                        let daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                        if (daysDiff < 0) {
                            return "Kontrak telah berakhir";
                        } else if (daysDiff === 0) {
                            return "Kontrak berakhir hari ini";
                        } else {
                            return daysDiff + " hari tersisa";
                        }
                    }
                },
                {
                    "data": "nilai_kontrak_pbj",
                    "render": function(data, type, row) {
                        let rupiah = row.nilai_kontrak_pbj;
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(rupiah);
                    }
                },
                {
                    "data": "nama_vendor"
                },
                {
                    "data": "bapp",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-info btn-sm" onclick="showBappDetails('${row.nomor_kontrak}')">
                           Detail
                        </button>`;
                    }
                },
                {
                    "data": "bastp",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-info btn-sm" onclick="showBastpDetails('${row.nomor_kontrak}')">
                           Detail
                        </button>`;
                    }
                },
                {
                    "data": "pho",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-info btn-sm" onclick="showPhoDetails('${row.nomor_kontrak}')">
                            Detail
                        </button>`;
                    }
                },
                {
                    "data": "fho",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-info btn-sm" onclick="showFhoDetails('${row.nomor_kontrak}')">
                            Detail
                        </button>`;
                    }
                },
                {
                    "data": "action",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row) {
                        return `
                            <div class="d-flex">
                                <button class="btn btn-warning btn-sm mr-1" onclick="editData('${row.nomor_kontrak}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete('${row.nomor_kontrak}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            "language": {
                "decimal": "",
                "emptyTable": "Tak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                "infoFiltered": "(difilter dari _MAX_ total entri)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Loading...",
                "processing": "Sedang Mengambil Data...",
                "search": "Pencarian:",
                "zeroRecords": "Tidak ada data yang cocok ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom ascending",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom descending"
                }
            }
        });
    });

    function closeModal(modalId) {
        // For Bootstrap 5
        if (typeof bootstrap !== 'undefined') {
            var myModal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            if (myModal) {
                myModal.hide();
                return;
            }
        }

        // For Bootstrap 4 fallback
        $('#' + modalId).modal('hide');
    }

    function showBappDetails(nomorKontrak) {
        $.ajax({
            url: "{{ route('berkas_pbj.getBappDetails') }}",
            type: 'POST',
            data: {
                nomor_kontrak: nomorKontrak,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                if (response.data) {
                    // Format mata uang
                    const formatCurrency = (value) => {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    };

                    // Format tanggal dari ISO format ke format Indonesia (dd-mm-yyyy)
                    const formatDate = (dateString) => {
                        if (!dateString) return '-';
                        // Mengambil hanya bagian tanggal (yyyy-mm-dd) dari ISO string
                        const datePart = dateString.split('T')[0];
                        // Memisahkan komponen tanggal
                        const [year, month, day] = datePart.split('-');
                        // Mengembalikan dalam format dd-mm-yyyy
                        return `${day}-${month}-${year}`;
                    };

                    // Mengisi data ke elemen HTML
                    $('#bappNomorPermohonan').text(response.data.nomor_permohonan_bapp || '-');
                    $('#bappTanggalPermohonan').text(formatDate(response.data.tanggal_permohonan_bapp) || '-');
                    $('#nomorBAPP').text(response.data.nomor_bapp || '-');
                    $('#tanggalBAPP').text(formatDate(response.data.tanggal_bapp));
                    $('#bappNilaiKontrak').text(formatCurrency(response.data.nilai_kontrak_bapp) || '-');
                    $('#bappJumlahBayarTermin1').text(formatCurrency(response.data.jumlah_bayar_termin_1_bapp) || '-');
                    $('#bappSaldo').text(formatCurrency(response.data.saldo) || '-');
                    $('#bappJangkaWaktuPemeliharaan').text((response.data.jangka_waktu_pemeliharaan_bapp ? response.data.jangka_waktu_pemeliharaan_bapp + ' hari' : '-'));
                } else {
                    // Reset semua field jika tidak ada data
                    $('#bappNomorPermohonan, #bappTanggalPermohonan, #nomorBAPP, #tanggalBAPP, #bappNilaiKontrak, #bappJumlahBayarTermin1, #bappSaldo, #bappJangkaWaktuPemeliharaan').text('-');
                }

                // Tampilkan modal
                $('#bappModal').modal('show');

                $('.modal-close-btn').off('click').on('click', function() {
                    $('#bappModal').modal('hide');
                });
            },
            error: function() {
                Swal.fire('Informasi!', 'Tidak ada data BAPP', 'info');
            }
        });

    }

    function showBastpDetails(nomorKontrak) {
        $.ajax({
            url: "{{ route('berkas_pbj.getBastpDetails') }}",
            type: 'POST',
            data: {
                nomor_kontrak: nomorKontrak,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                if (response.data) {
                    // Format mata uang
                    const formatCurrency = (value) => {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    };

                    // Format tanggal dari ISO format ke format Indonesia (dd-mm-yyyy)
                    const formatDate = (dateString) => {
                        if (!dateString) return '-';
                        // Mengambil hanya bagian tanggal (yyyy-mm-dd) dari ISO string
                        const datePart = dateString.split('T')[0];
                        // Memisahkan komponen tanggal
                        const [year, month, day] = datePart.split('-');
                        // Mengembalikan dalam format dd-mm-yyyy
                        return `${day}-${month}-${year}`;
                    };

                    // Mengisi data ke elemen HTML dengan ID baru
                    $('#bastpNomorPermohonan').text(response.data.nomor_permohonan_bastp || '-');
                    $('#bastpTanggalPermohonan').text(formatDate(response.data.tanggal_permohonan_bastp) || '-');
                    $('#nomorBASTP').text(response.data.nomor_bastp || '-');
                    $('#tanggalBASTP').text(formatDate(response.data.tanggal_bastp));
                    $('#bastpNilaiKontrak').text(formatCurrency(response.data.nilai_kontrak_bastp) || '-');
                    $('#bastpJumlahBayarTermin1').text(formatCurrency(response.data.jumlah_bayar_termin_1_bastp) || '-');
                    $('#bastpSaldo').text(formatCurrency(response.data.saldo) || '-');
                    $('#bastpJangkaWaktuPemeliharaan').text((response.data.jangka_waktu_pemeliharaan_bastp ? response.data.jangka_waktu_pemeliharaan_bastp + ' hari' : '-'));
                } else {
                    // Reset semua field jika tidak ada data
                    $('#bastpNomorPermohonan, #bastpTanggalPermohonan, #nomorBASTP, #tanggalBASTP, #bastpNilaiKontrak, #bastpJumlahBayarTermin1, #bastpSaldo, #bastpJangkaWaktuPemeliharaan').text('-');
                }

                // Tampilkan modal
                $('#bastpModal').modal('show');

                // Add event listener for close button
                $('.modal-close-btn').off('click').on('click', function() {
                    $('#bastpModal').modal('hide');
                });
            },
            error: function() {
                Swal.fire('Informasi!', 'Tidak ada data BASTP', 'info');
            }
        });
    }


    function showPhoDetails(nomorKontrak) {
        $.ajax({
            url: "{{ route('berkas_pbj.getPhoDetails') }}",
            type: 'POST',
            data: {
                nomor_kontrak: nomorKontrak,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                if (response.data) {
                    // Format tanggal dari ISO format ke format Indonesia (dd-mm-yyyy)
                    const formatDate = (dateString) => {
                        if (!dateString) return '-';
                        // Mengambil hanya bagian tanggal (yyyy-mm-dd) dari ISO string
                        const datePart = dateString.split('T')[0];
                        // Memisahkan komponen tanggal
                        const [year, month, day] = datePart.split('-');
                        // Mengembalikan dalam format dd-mm-yyyy
                        return `${day}-${month}-${year}`;
                    };

                    // Mengisi data ke elemen HTML dengan ID baru
                    $('#phoNomorBaPemeriksaan').text(response.data.nomor_ba_pemeriksaan_pekerjaan_pho || '-');
                    $('#phoTanggalBaPemeriksaan').text(formatDate(response.data.tanggal_ba_pemeriksaan_pekerjaan_pho) || '-');
                    $('#phoNomorBaSerahTerima').text(response.data.nomor_ba_serah_terima_pho || '-');
                    $('#phoTanggalBaSerahTerima').text(formatDate(response.data.tanggal_ba_serah_terima_pho));
                } else {
                    // Reset semua field jika tidak ada data
                    $('#phoNomorBaPemeriksaan, #phoTanggalBaPemeriksaan, #phoNomorBaSerahTerima, #phoTanggalBaSerahTerima').text('-');
                }

                // Tampilkan modal
                $('#phoModal').modal('show');

                // Add event listener for close button
                $('.modal-close-btn').off('click').on('click', function() {
                    $('#phoModal').modal('hide');
                });
            },
            error: function() {
                Swal.fire('Informasi!', 'Tidak ada data PHO', 'info');
            }
        });
    }

    function showFhoDetails(nomorKontrak) {
        $.ajax({
            url: "{{ route('berkas_pbj.getFhoDetails') }}",
            type: 'POST',
            data: {
                nomor_kontrak: nomorKontrak,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                if (response.data) {
                    // Format tanggal dari ISO format ke format Indonesia (dd-mm-yyyy)
                    const formatDate = (dateString) => {
                        if (!dateString) return '-';
                        // Mengambil hanya bagian tanggal (yyyy-mm-dd) dari ISO string
                        const datePart = dateString.split('T')[0];
                        // Memisahkan komponen tanggal
                        const [year, month, day] = datePart.split('-');
                        // Mengembalikan dalam format dd-mm-yyyy
                        return `${day}-${month}-${year}`;
                    };

                    // Mengisi data ke elemen HTML dengan ID baru
                    $('#fhoNomorSuratPermohonan').text(response.data.nomor_surat_permohonan_fho_vendor || '-');
                    $('#fhoTanggalSuratPermohonan').text(formatDate(response.data.tanggal_surat_permohonan_fho_vendor) || '-');
                    $('#fhoNomorSuratLaporan').text(response.data.nomor_surat_laporan_tindak_lanjut_fho || '-');
                    $('#fhoTanggalSuratLaporan').text(formatDate(response.data.tanggal_surat_laporan_tindak_lanjut_fho));
                } else {
                    // Reset semua field jika tidak ada data
                    $('#fhoNomorSuratPermohonan, #fhoTanggalSuratPermohonan, #fhoNomorSuratLaporan, #fhoTanggalSuratLaporan').text('-');
                }

                // Tampilkan modal
                $('#fhoModal').modal('show');

                // Add event listener for close button
                $('.modal-close-btn').off('click').on('click', function() {
                    $('#fhoModal').modal('hide');
                });
            },
            error: function() {
                Swal.fire('Informasi!', 'Tidak ada data FHO', 'info');
            }
        });
    }

    function confirmDelete(nomorKontrak) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus data!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteData(nomorKontrak);
            }
        });
    }

    function deleteData(nomorKontrak) {
        $.ajax({
            url: "{{ route('berkas_pbj.delete') }}",
            type: 'DELETE',
            data: {
                nomor_kontrak: nomorKontrak,
                _token: "{{csrf_token()}}"
            },
            success: function(response) {
                Swal.fire(
                    'Terhapus!',
                    response.message,
                    'success'
                );
                // Refresh the DataTable
                $('#previewBerkasPBJ').DataTable().ajax.reload();
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menghapus data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire(
                    'Error!',
                    errorMessage,
                    'error'
                );
            }
        });
    }

    // function editData(nomorKontrak) {
    //     // Alternative AJAX approach:

    //     $.ajax({
    //         url: "{{ route('berkas_pbj.update') }}",
    //         type: 'POST',
    //         data: {
    //             nomor_kontrak: nomorKontrak,
    //             _token: "{{csrf_token()}}"
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 // Either redirect to edit page
    //                 window.location.href = "{{ route('berkas_pbj.update', ['nomor_kontrak' => ':nomor_kontrak']) }}".replace(':nomor_kontrak', nomorKontrak);

    //                 // Or open a modal with edit form (more complex implementation)
    //                 // showEditModal(response.data);
    //             } else {
    //                 Swal.fire('Error!', response.message || 'Gagal mengambil data untuk edit', 'error');
    //             }
    //         },
    //         error: function(xhr) {
    //             let errorMessage = 'Terjadi kesalahan saat mengambil data';
    //             if (xhr.responseJSON && xhr.responseJSON.message) {
    //                 errorMessage = xhr.responseJSON.message;
    //             }
    //             Swal.fire('Error!', errorMessage, 'error');
    //         }
    //     });

    //}
    function editData(nomorKontrak) {
        // Create a hidden form
        var form = document.createElement('form');
        form.method = 'GET';
        form.action = "{{ route('berkas_pbj.update') }}";
        form.style.display = 'none';

        // Create input for the contract number
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'nomor_kontrak';
        input.value = nomorKontrak;

        // Create CSRF token input
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";

        // Add inputs to form
        form.appendChild(input);
        form.appendChild(csrfInput);

        // Add form to body and submit
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection