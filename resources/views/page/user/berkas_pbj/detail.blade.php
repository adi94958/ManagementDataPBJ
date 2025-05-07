@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Detail Berkas dan Tagihan PBJ')
@section('script_head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Berkas PBJ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('berkas_pbj.index') }}">Berkas dan Tagihan PBJ</a>
                    </li>
                    <li class="breadcrumb-item active">Detail Berkas dan Tagihan PBJ</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    @if(session('status'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
        {{ session('status') }}
    </div>
    @endif

    <div class="row">
        <div class="col d-flex justify-content-center">
            <div class="card card-primary w-100 h-100">
                <div class="card-header">
                    <h3 class="card-title">Informasi Berkas PBJ</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Kontrak</label>
                                <p class="form-control-static">{{ $berkasPBJ->nomor_kontrak }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Kontrak</label>
                                <p class="form-control-static">{{ $berkasPBJ->nama_kontrak }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Kontrak Mulai</label>
                                <p class="form-control-static">{{ \Carbon\Carbon::parse($berkasPBJ->tanggal_kontrak_mulai)->format('d-m-Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Kontrak Selesai</label>
                                <p class="form-control-static">{{ \Carbon\Carbon::parse($berkasPBJ->tanggal_kontrak_selesai)->format('d-m-Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nilai Kontrak</label>
                                <p class="form-control-static">Rp {{ number_format($berkasPBJ->nilai_kontrak_pbj, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Vendor</label>
                                <p class="form-control-static">{{ $berkasPBJ->nama_vendor }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pemeliharaan Mulai</label>
                                <p class="form-control-static">
                                    @if($berkasPBJ->tanggal_mulai_pemeliharaan)
                                        {{ \Carbon\Carbon::parse($berkasPBJ->tanggal_mulai_pemeliharaan)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pemeliharaan Selesai</label>
                                <p class="form-control-static">
                                    @if($berkasPBJ->tanggal_selesai_pemeliharaan)
                                        {{ \Carbon\Carbon::parse($berkasPBJ->tanggal_selesai_pemeliharaan)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>File Berkas PBJ</label>
                            <div class="form-group">
                                
                                @if($berkasPBJ->file_path)
                                {{-- Tombol Lihat Dokumen --}}
                                <a href="{{ asset('storage/' . $berkasPBJ->file_path) }}" 
                                    target="_blank" 
                                    class="btn btn-success btn-sm mr-1">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                
                                {{-- Tombol Download Dokumen --}}
                                <a href="{{ asset('storage/' . $berkasPBJ->file_path) }}" 
                                    download 
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @else
                                <p class="form-control-static">Tidak ada berkas</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Detail Tagihan</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="tagihanTabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#bapp">BAPP</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bastp">BASTP</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pho">PHO</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#fho">FHO</a></li>
            </ul>
            <div class="tab-content mt-3">
                <div id="bapp" class="tab-pane fade show active">
                    @if(!empty($tagihanBAPP->nomor_bapp) || !empty($tagihanBAPP->tanggal_bapp) || !empty($tagihanBAPP->nomor_permohonan_bapp) || !empty($tagihanBAPP->tanggal_permohonan_bapp))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BAPP</label>
                                    <p class="form-control-static">{{ $tagihanBAPP->nomor_bapp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BAPP</label>
                                    <p class="form-control-static">
                                        @if($tagihanBAPP->tanggal_bapp)
                                            {{ \Carbon\Carbon::parse($tagihanBAPP->tanggal_bapp)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Permohonan dari Vendor</label>
                                    <p class="form-control-static">{{ $tagihanBAPP->nomor_permohonan_bapp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Permohonan dari Vendor</label>
                                    <p class="form-control-static">
                                        @if($tagihanBAPP->tanggal_permohonan_bapp)
                                            {{ \Carbon\Carbon::parse($tagihanBAPP->tanggal_permohonan_bapp)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p>Tidak ada data BAPP untuk kontrak ini.</p>
                        </div>
                    @endif
                </div>
                
                <div id="bastp" class="tab-pane fade">
                    @if(!empty($tagihanBASTP->nomor_bastp) || !empty($tagihanBASTP->tanggal_bastp) || !empty($tagihanBASTP->nomor_permohonan_bastp) || !empty($tagihanBASTP->tanggal_permohonan_bastp) || !empty($tagihanBASTP->jumlah_bayar_termin_1_bastp))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BASTP</label>
                                    <p class="form-control-static">{{ $tagihanBASTP->nomor_bastp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BASTP</label>
                                    <p class="form-control-static">
                                        @if($tagihanBASTP->tanggal_bastp)
                                            {{ \Carbon\Carbon::parse($tagihanBASTP->tanggal_bastp)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Permohonan dari Vendor</label>
                                    <p class="form-control-static">{{ $tagihanBASTP->nomor_permohonan_bastp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Permohonan dari Vendor</label>
                                    <p class="form-control-static">
                                        @if($tagihanBASTP->tanggal_permohonan_bastp)
                                            {{ \Carbon\Carbon::parse($tagihanBASTP->tanggal_permohonan_bastp)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Bayar Termin 1</label>
                                    <p class="form-control-static">
                                        @if($tagihanBASTP->jumlah_bayar_termin_1_bastp)
                                            Rp {{ number_format($tagihanBASTP->jumlah_bayar_termin_1_bastp, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p>Tidak ada data BASTP untuk kontrak ini.</p>
                        </div>
                    @endif
                </div>
                
                <div id="pho" class="tab-pane fade">
                    @if(!empty($tagihanPHO->nomor_ba_pemeriksaan_pekerjaan_pho) || !empty($tagihanPHO->tanggal_ba_pemeriksaan_pekerjaan_pho) || 
                        !empty($tagihanPHO->nomor_ba_serah_terima_pho) || !empty($tagihanPHO->tanggal_ba_serah_terima_pho) || 
                        !empty($tagihanPHO->nomor_bapp_pada_pho) || !empty($tagihanPHO->tanggal_bapp_pada_pho) || 
                        !empty($tagihanPHO->nomor_bastp_pada_pho) || !empty($tagihanPHO->tanggal_bastp_pada_pho) || 
                        !empty($tagihanPHO->nomor_permohonan_pho_vendor) || !empty($tagihanPHO->tanggal_permohonan_pho_vendor))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BA Pemeriksaan Pekerjaan PHO</label>
                                    <p class="form-control-static">{{ $tagihanPHO->nomor_ba_pemeriksaan_pekerjaan_pho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BA Pemeriksaan Pekerjaan PHO</label>
                                    <p class="form-control-static">
                                        @if($tagihanPHO->tanggal_ba_pemeriksaan_pekerjaan_pho)
                                            {{ \Carbon\Carbon::parse($tagihanPHO->tanggal_ba_pemeriksaan_pekerjaan_pho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BA Serah Terima PHO</label>
                                    <p class="form-control-static">{{ $tagihanPHO->nomor_ba_serah_terima_pho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BA Serah Terima PHO</label>
                                    <p class="form-control-static">
                                        @if($tagihanPHO->tanggal_ba_serah_terima_pho)
                                            {{ \Carbon\Carbon::parse($tagihanPHO->tanggal_ba_serah_terima_pho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BAPP</label>
                                    <p class="form-control-static">{{ $tagihanPHO->nomor_bapp_pada_pho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BAPP</label>
                                    <p class="form-control-static">
                                        @if($tagihanPHO->tanggal_bapp_pada_pho)
                                            {{ \Carbon\Carbon::parse($tagihanPHO->tanggal_bapp_pada_pho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BASTP</label>
                                    <p class="form-control-static">{{ $tagihanPHO->nomor_bastp_pada_pho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BASTP</label>
                                    <p class="form-control-static">
                                        @if($tagihanPHO->tanggal_bastp_pada_pho)
                                            {{ \Carbon\Carbon::parse($tagihanPHO->tanggal_bastp_pada_pho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Permohonan dari Vendor</label>
                                    <p class="form-control-static">{{ $tagihanPHO->nomor_permohonan_pho_vendor ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Permohonan dari Vendor</label>
                                    <p class="form-control-static">
                                        @if($tagihanPHO->tanggal_permohonan_pho_vendor)
                                            {{ \Carbon\Carbon::parse($tagihanPHO->tanggal_permohonan_pho_vendor)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p>Tidak ada data PHO untuk kontrak ini.</p>
                        </div>
                    @endif
                </div>
                
                <div id="fho" class="tab-pane fade">
                    @if(!empty($tagihanFHO->nomor_surat_permohonan_fho_vendor) || !empty($tagihanFHO->tanggal_surat_permohonan_fho_vendor) || 
                        !empty($tagihanFHO->nomor_surat_laporan_tindak_lanjut_fho) || !empty($tagihanFHO->tanggal_surat_laporan_tindak_lanjut_fho) || 
                        !empty($tagihanFHO->nomor_bapp_pada_fho) || !empty($tagihanFHO->tanggal_bapp_pada_fho) || 
                        !empty($tagihanFHO->nomor_bastp_pada_fho) || !empty($tagihanFHO->tanggal_bastp_pada_fho))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Surat Permohonan FHO Vendor</label>
                                    <p class="form-control-static">{{ $tagihanFHO->nomor_surat_permohonan_fho_vendor ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Surat Permohonan FHO Vendor</label>
                                    <p class="form-control-static">
                                        @if($tagihanFHO->tanggal_surat_permohonan_fho_vendor)
                                            {{ \Carbon\Carbon::parse($tagihanFHO->tanggal_surat_permohonan_fho_vendor)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Surat Laporan Tindak Lanjut Perbaikan FHO</label>
                                    <p class="form-control-static">{{ $tagihanFHO->nomor_surat_laporan_tindak_lanjut_fho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Surat Laporan Tindak Lanjut Perbaikan FHO</label>
                                    <p class="form-control-static">
                                        @if($tagihanFHO->tanggal_surat_laporan_tindak_lanjut_fho)
                                            {{ \Carbon\Carbon::parse($tagihanFHO->tanggal_surat_laporan_tindak_lanjut_fho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BAPP</label>
                                    <p class="form-control-static">{{ $tagihanFHO->nomor_bapp_pada_fho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BAPP</label>
                                    <p class="form-control-static">
                                        @if($tagihanFHO->tanggal_bapp_pada_fho)
                                            {{ \Carbon\Carbon::parse($tagihanFHO->tanggal_bapp_pada_fho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor BASTP</label>
                                    <p class="form-control-static">{{ $tagihanFHO->nomor_bastp_pada_fho ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BASTP</label>
                                    <p class="form-control-static">
                                        @if($tagihanFHO->tanggal_bastp_pada_fho)
                                            {{ \Carbon\Carbon::parse($tagihanFHO->tanggal_bastp_pada_fho)->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p>Tidak ada data FHO untuk kontrak ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <a href="{{ route('berkas_pbj.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</section>
@endsection