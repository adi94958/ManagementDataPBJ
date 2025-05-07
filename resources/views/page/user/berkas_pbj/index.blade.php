@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Berkas dan Tagihan PBJ')
@section('script_head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Berkas dan Tagihan PBJ</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="breadcrumb-item active">Berkas dan Tagihan PBJ</li>
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
                        <i class="fas fa-plus"></i> Tambah Berkas dan Tagihan PBJ
                    </a>
                </div>
            </div>
            <div class="card-body p-0" style="margin: 20px">
                <div class="table-responsive">
                    <table id="previewBerkasPBJ" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nomor Kontrak</th>
                                <th>Nama Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Jangka Waktu Kontrak</th>
                                <th>Nilai Kontrak</th>
                                <th>Nama Vendor</th>
                                <th>Jangka Waktu Pemeliharaan</th>
                                {{-- <th>File Kontrak</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
                        _token: "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": "nomor_kontrak"
                    },
                    {
                        "data": "nama_kontrak"
                    },
                    {
                        "data": "tanggal_kontrak_mulai",
                        "render": function(data, type, row) {
                            // Format tanggal dari ISO format ke format Indonesia (dd-mm-yyyy)
                            if (!data) return '-';
                            // Mengambil hanya bagian tanggal (yyyy-mm-dd) dari ISO string
                            const datePart = data.split('T')[0];
                            // Memisahkan komponen tanggal
                            const [year, month, day] = datePart.split('-');
                            // Mengembalikan dalam format dd-mm-yyyy
                            return `${day}-${month}-${year}`;
                        }
                    },
                    {
                        "data": "tanggal_kontrak_selesai",
                        "render": function(data, type, row) {
                            // Mendapatkan tanggal selesai kontrak dari data
                            let tanggalSelesai = row.tanggal_kontrak_selesai;

                            if (!tanggalSelesai) {
                                return "T-";
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
                        "data": "nama_vendor",
                    },
                    {
                        "data": "tanggal_selesai_pemeliharaan",
                        "render": function(data, type, row) {
                            // Mendapatkan tanggal selesai kontrak dari data
                            let tanggalSelesai = row.tanggal_selesai_pemeliharaan;

                            if (!tanggalSelesai) {
                                return "-";
                            }

                            // Mengkonversi string tanggal ke objek Date
                            let endDate = new Date(tanggalSelesai);
                            let today = new Date();

                            // Menghitung selisih dalam milisecond
                            let timeDiff = endDate - today;

                            // Menghitung selisih dalam hari
                            let daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                            if (daysDiff < 0) {
                                return "Pemeliharaan telah berakhir";
                            } else if (daysDiff === 0) {
                                return "Pemeliharaan berakhir hari ini";
                            } else {
                                return daysDiff + " hari tersisa";
                            }
                        }
                    },
                    {
                        "data": "action",
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return `
                            <div class="d-flex">
                                <button class="btn btn-info btn-sm mr-1" onclick="showDetailKontrak('${row.nomor_kontrak}')">
                                    <i class="fas fa-eye"></i>
                                </button>
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
                    _token: "{{ csrf_token() }}"
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

        function editData(nomorKontrak) {
            window.location.href = "{{ route('berkas_pbj.update') }}" + "?nomor_kontrak=" + encodeURIComponent(nomorKontrak);
        }

        function showDetailKontrak(nomorKontrak) {
            window.location.href = "{{ route('berkas_pbj.detail') }}" + "?nomor_kontrak=" + encodeURIComponent(nomorKontrak);
        }

    </script>
@endsection