@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Dashboard Kontrak')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filter Data Kontrak</h3>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" method="GET" action="{{ route('home') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jangka Waktu Kontrak</label>
                                            <div class="row">
                                                <div class="col-md">
                                                    <input type="date"
                                                        class="form-control"
                                                        name="tanggal_awal"
                                                        id="tanggal_awal"
                                                        value="{{ request('tanggal_awal') }}">
                                                </div>
                                                <div class="col-md">
                                                    <input type="date"
                                                        class="form-control"
                                                        name="tanggal_akhir"
                                                        id="tanggal_akhir"
                                                        value="{{ request('tanggal_akhir') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status Kontrak</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>
                                                    Semua</option>
                                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>
                                                    Kontrak Aktif</option>
                                                <option value="berakhir"
                                                    {{ request('status') == 'berakhir' ? 'selected' : '' }}>Kontrak Berakhir
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('home') }}" class="btn btn-default">Reset</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-file-contract"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kontrak</span>
                            <span class="info-box-number">{{ $totalKontrak }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kontrak Aktif</span>
                            <span class="info-box-number">{{ $kontrakAktif }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-end"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kontrak Berakhir</span>
                            <span class="info-box-number">{{ $kontrakBerakhir }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Nilai Kontrak</span>
                            <span class="info-box-number">Rp {{ number_format($totalNilaiKontrak, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="row">
                <!-- Kontrak Aktif -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Kontrak</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tableKontrak">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">No. Kontrak</th>
                                            <th width="25%">Nama Kontrak</th>
                                            <th width="15%">Vendor</th>
                                            <th width="15%">Nilai Kontrak</th>
                                            <th width="10%">Tanggal Mulai</th>
                                            <th width="10%">Tanggal Selesai</th>
                                            <th width="5%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kontrakData as $index => $kontrak)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $kontrak->nomor_kontrak }}</td>
                                                <td>{{ $kontrak->nama_kontrak }}</td>
                                                <td>{{ $kontrak->nama_vendor }}</td>
                                                <td>Rp {{ number_format($kontrak->nilai_kontrak_pbj, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($kontrak->tanggal_kontrak_mulai)->format('d-m-Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($kontrak->tanggal_kontrak_selesai)->format('d-m-Y') }}
                                                </td>
                                                <td>
                                                    @if (\Carbon\Carbon::parse($kontrak->tanggal_kontrak_selesai)->gte(\Carbon\Carbon::now()))
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-warning">Berakhir</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data kontrak</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function() {
            // Initialize DataTables
            $('#tableKontrak').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

        });
    </script>
@endsection
