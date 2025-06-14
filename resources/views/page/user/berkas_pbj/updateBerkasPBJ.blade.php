@extends('layouts.base_admin.base_dashboard') @section('judul', 'Ubah Berkas PBJ')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Ubah Berkas PBJ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Ubah Berkas PBJ</li>
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Error!</h4>
        {{ session('error') }}
    </div>
    @endif

    <form method="post" enctype="multipart/form-data" id="form-edit-berkas">
        @csrf

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
                                    <label for="nomor_kontrak">Nomor Kontrak <span class="text-danger">*</span></label>
                                    <input type="text" id="nomor_kontrak" name="nomor_kontrak" class="form-control @error('nomor_kontrak') is-invalid @enderror" placeholder="Masukan Nomor Kontrak" value="{{ old('nomor_kontrak', $berkas_pbj->nomor_kontrak) }}" required>
                                    <input type="hidden" id="old_nomor_kontrak" name="old_nomor_kontrak"  value="{{ old('nomor_kontrak', $berkas_pbj->nomor_kontrak) }}">
                                    @error('nomor_kontrak')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_kontrak">Nama Kontrak <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_kontrak" name="nama_kontrak" class="form-control @error('nama_kontrak') is-invalid @enderror" placeholder="Masukan Nama Kontrak" value="{{ old('nama_kontrak', $berkas_pbj->nama_kontrak) }}" required>
                                    @error('nama_kontrak')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_kontrak_mulai">Tanggal Kontrak Mulai<span class="text-danger">*</span></label>
                                    <input type="date" id="tanggal_kontrak_mulai" name="tanggal_kontrak_mulai" class="form-control @error('tanggal_kontrak_mulai') is-invalid @enderror" value="{{ old('tanggal_kontrak_mulai', $berkas_pbj->tanggal_kontrak_mulai) }}" required>
                                    @error('tanggal_kontrak_mulai')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_kontrak_selesai">Tanggal Kontrak Selesai<span class="text-danger">*</span></label>
                                    <input type="date" id="tanggal_kontrak_selesai" name="tanggal_kontrak_selesai" class="form-control @error('tanggal_kontrak_selesai') is-invalid @enderror" value="{{ old('tanggal_kontrak_selesai', $berkas_pbj->tanggal_kontrak_selesai) }}" required>
                                    @error('tanggal_kontrak_selesai')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_kontrak_pbj">Nilai Kontrak</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="nilai_kontrak_pbj" name="nilai_kontrak_pbj" class="form-control @error('nilai_kontrak_pbj') is-invalid @enderror" placeholder="Masukan Nilai Kontrak" value="{{ old('nilai_kontrak_pbj', $berkas_pbj->nilai_kontrak_pbj) }}" >
                                        @error('nilai_kontrak_pbj')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_vendor">Nama Vendor <span class="text-danger">*</span></label>
                                    <input type="text" id="nama_vendor" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror" placeholder="Masukan Nama Vendor" value="{{ old('nama_vendor', $berkas_pbj->nama_vendor) }}" required>
                                    @error('nama_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai_pemeliharaan">Tanggal Pemeliharaan Mulai</label>
                                    <input type="date" id="tanggal_mulai_pemeliharaan" name="tanggal_mulai_pemeliharaan" class="form-control @error('tanggal_mulai_pemeliharaan') is-invalid @enderror" value="{{ old('tanggal_mulai_pemeliharaan', $berkas_pbj->tanggal_mulai_pemeliharaan) }}" nullable>
                                    @error('tanggal_mulai_pemeliharaan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai_pemeliharaan">Tanggal Pemeliharaan Selesai</label>
                                    <input type="date" id="tanggal_selesai_pemeliharaan" name="tanggal_selesai_pemeliharaan" class="form-control @error('tanggal_selesai_pemeliharaan') is-invalid @enderror" value="{{ old('tanggal_selesai_pemeliharaan', $berkas_pbj->tanggal_selesai_pemeliharaan) }}" nullable>
                                    @error('tanggal_selesai_pemeliharaan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- ======= Blok Edit/Ganti/Hapus File ======= --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file_path">Berkas PBJ <small>(PDF, maks. 16MB)</small></label>

                                    @if($berkas_pbj->file_path)
                                        <div class="mb-2">
                                            {{-- Tombol Lihat --}}
                                            <a href="{{ asset('storage/' . $berkas_pbj->file_path) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-success mr-1">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            {{-- Tombol Download --}}
                                            <a href="{{ asset('storage/' . $berkas_pbj->file_path) }}"
                                                download
                                                class="btn btn-sm btn-primary mr-1">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                            {{-- Tombol Hapus --}}
                                            <button type="button" class="btn btn-sm btn-danger" onclick="clearFileInput()">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                        {{-- Tombol Ganti File --}}
                                        <div class="custom-file">
                                            <input
                                                type="file"
                                                name="file_path"
                                                id="file_path"
                                                class="custom-file-input @error('file_path') is-invalid @enderror"
                                                accept=".pdf"
                                            >
                                            <label
                                                class="custom-file-label"
                                                for="file_path"
                                                id="fileLabel"
                                                data-default-label="{{ $berkas_pbj->file_path ? basename($berkas_pbj->file_path) : 'Pilih file' }}"
                                            >
                                                {{ $berkas_pbj->file_path ? basename($berkas_pbj->file_path) : 'Pilih file' }}
                                            </label>
                                        </div>
                                    @else
                                        {{-- Input Upload Baru --}}
                                        <div class="custom-file">
                                            <input type="file"
                                                name="file_path"
                                                id="file_path"
                                                class="custom-file-input @error('file_path') is-invalid @enderror"
                                                accept=".pdf">
                                            <label class="custom-file-label" for="file_path">Unggah Berkas PBJ</label>
                                        </div>
                                    @endif

                                    @error('file_path')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <small class="form-text text-muted">
                                        PDF maks. 16MB. Pilih file baru untuk mengganti.
                                    </small>
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bapp">Nomor BAPP</label>
                                    <input type="text" id="nomor_bapp" name="nomor_bapp" class="form-control @error('nomor_bapp') is-invalid @enderror" placeholder="Masukan Nomor BAPP" value="{{ old('nomor_bapp', $bapp->nomor_bapp) }}">
                                    @error('nomor_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bapp">Tanggal BAPP</label>
                                    <input type="date" id="tanggal_bapp" name="tanggal_bapp" class="form-control @error('tanggal_bapp') is-invalid @enderror" value="{{ old('tanggal_bapp', $bapp->tanggal_bapp ? $bapp->tanggal_bapp->format('Y-m-d') : '') }}">
                                    @error('tanggal_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_permohonan_bapp">Nomor Permohonan dari Vendor</label>
                                    <input type="text" id="nomor_permohonan_bapp" name="nomor_permohonan_bapp" class="form-control @error('nomor_permohonan_bapp') is-invalid @enderror" placeholder="Masukan Nomor Permohonan dari Vendor" value="{{ old('nomor_permohonan_bapp', $bapp->nomor_permohonan_bapp) }}">
                                    @error('nomor_permohonan_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan_bapp">Tanggal Permohonan dari Vendor</label>
                                    <input type="date" id="tanggal_permohonan_bapp" name="tanggal_permohonan_bapp" class="form-control @error('tanggal_permohonan_bapp') is-invalid @enderror" value="{{ old('tanggal_permohonan_bapp', $bapp->tanggal_permohonan_bapp ? $bapp->tanggal_permohonan_bapp->format('Y-m-d') : '') }}">
                                    @error('tanggal_permohonan_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bastp" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bastp">Nomor BASTP</label>
                                    <input type="text" id="nomor_bastp" name="nomor_bastp" class="form-control @error('nomor_bastp') is-invalid @enderror" placeholder="Masukan Nomor BASTP" value="{{ old('nomor_bastp', $bastp->nomor_bastp) }}">
                                    @error('nomor_bastp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bastp">Tanggal BASTP</label>
                                    <input type="date" id="tanggal_bastp" name="tanggal_bastp" class="form-control @error('tanggal_bastp') is-invalid @enderror" value="{{ old('tanggal_bastp', $bastp->tanggal_bastp ? $bastp->tanggal_bastp->format('Y-m-d') : '') }}">
                                    @error('tanggal_bastp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_permohonan_bastp">Nomor Permohonan dari Vendor</label>
                                    <input type="text" id="nomor_permohonan_bastp" name="nomor_permohonan_bastp" class="form-control @error('nomor_permohonan_bastp') is-invalid @enderror" placeholder="Masukan Nomor Permohonan dari Vendor" value="{{ old('nomor_permohonan_bastp', $bastp->nomor_permohonan_bastp) }}">
                                    @error('nomor_permohonan_bastp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan_bastp">Tanggal Permohonan dari Vendor</label>
                                    <input type="date" id="tanggal_permohonan_bastp" name="tanggal_permohonan_bastp" class="form-control @error('tanggal_permohonan_bastp') is-invalid @enderror" value="{{ old('tanggal_permohonan_bastp', $bastp->tanggal_permohonan_bastp ? $bastp->tanggal_permohonan_bastp->format('Y-m-d') : '') }}">
                                    @error('tanggal_permohonan_bastp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_bayar_termin_1_bastp">Jumlah Bayar Termin 1</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="jumlah_bayar_termin_1_bastp" name="jumlah_bayar_termin_1_bastp" class="form-control @error('jumlah_bayar_termin_1_bastp') is-invalid @enderror" placeholder="Masukan Jumlah Bayar Termin 1" value="{{ old('jumlah_bayar_termin_1_bastp', $bastp->jumlah_bayar_termin_1_bastp) }}">
                                        @error('jumlah_bayar_termin_1_bastp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="pho" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_ba_pemeriksaan_pekerjaan_pho">Nomor BA Pemeriksaan Pekerjaan PHO</label>
                                    <input type="text" id="nomor_ba_pemeriksaan_pekerjaan_pho" name="nomor_ba_pemeriksaan_pekerjaan_pho" class="form-control @error('nomor_ba_pemeriksaan_pekerjaan_pho') is-invalid @enderror" placeholder="Masukan Nomor BA Pemeriksaan Pekerjaan PHO" value="{{ old('nomor_ba_pemeriksaan_pekerjaan_pho', $pho->nomor_ba_pemeriksaan_pekerjaan_pho) }}">
                                    @error('nomor_ba_pemeriksaan_pekerjaan_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_ba_pemeriksaan_pekerjaan_pho">Tanggal BA Pemeriksaan Pekerjaan PHO</label>
                                    <input type="date" id="tanggal_ba_pemeriksaan_pekerjaan_pho" name="tanggal_ba_pemeriksaan_pekerjaan_pho" class="form-control @error('tanggal_ba_pemeriksaan_pekerjaan_pho') is-invalid @enderror" placeholder="Masukan Tanggal BA Pemeriksaan Pekerjaan PHO" value="{{ old('tanggal_ba_pemeriksaan_pekerjaan_pho', $pho->tanggal_ba_pemeriksaan_pekerjaan_pho ) }}">
                                    @error('tanggal_ba_pemeriksaan_pekerjaan_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_ba_serah_terima_pho">Nomor BA Serah Terima PHO</label>
                                    <input type="text" id="nomor_ba_serah_terima_pho" name="nomor_ba_serah_terima_pho" class="form-control @error('nomor_ba_serah_terima_pho') is-invalid @enderror" placeholder="Masukan Nomor BA Serah Terima PHO" value="{{ old('nomor_ba_serah_terima_pho', $pho->nomor_ba_serah_terima_pho) }}">
                                    @error('nomor_ba_serah_terima_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_ba_serah_terima_pho">Tanggal BA Serah Terima PHO</label>
                                    <input type="date" id="tanggal_ba_serah_terima_pho" name="tanggal_ba_serah_terima_pho" class="form-control @error('tanggal_ba_serah_terima_pho') is-invalid @enderror" placeholder="Masukan Tanggal BA Serah Terima PHO" value="{{ old('tanggal_ba_serah_terima_pho', $pho->tanggal_ba_serah_terima_pho ) }}">
                                    @error('tanggal_ba_serah_terima_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bapp_pada_pho">Nomor BAPP</label>
                                    <input type="text" id="nomor_bapp_pada_pho" name="nomor_bapp_pada_pho" class="form-control @error('nomor_bapp_pada_pho') is-invalid @enderror" placeholder="Masukan Nomor BAPP" value="{{ old('nomor_bapp_pada_pho', $pho->nomor_bapp_pada_pho) }}">
                                    @error('nomor_bapp_pada_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bapp_pada_pho">Tanggal BAPP</label>
                                    <input type="date" id="tanggal_bapp_pada_pho" name="tanggal_bapp_pada_pho" class="form-control @error('tanggal_bapp_pada_pho') is-invalid @enderror" placeholder="Masukan Tanggal BAPP" value="{{ old('tanggal_bapp_pada_pho', $pho->tanggal_bapp_pada_pho ) }}">
                                    @error('tanggal_bapp_pada_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bastp_pada_pho">Nomor BASTP</label>
                                    <input type="text" id="nomor_bastp_pada_pho" name="nomor_bastp_pada_pho" class="form-control @error('nomor_bastp_pada_pho') is-invalid @enderror" placeholder="Masukan Nomor BASTP" value="{{ old('nomor_bastp_pada_pho', $pho->nomor_bastp_pada_pho) }}">
                                    @error('nomor_bastp_pada_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bastp_pada_pho">Tanggal BASTP</label>
                                    <input type="date" id="tanggal_bastp_pada_pho" name="tanggal_bastp_pada_pho" class="form-control @error('tanggal_bastp_pada_pho') is-invalid @enderror" placeholder="Masukan Tanggal BASTP" value="{{ old('tanggal_bastp_pada_pho', $pho->tanggal_bastp_pada_pho) }}">
                                    @error('tanggal_bastp_pada_pho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_permohonan_pho_vendor">Nomor Permohonan dari Vendor</label>
                                    <input type="text" id="nomor_permohonan_pho_vendor" name="nomor_permohonan_pho_vendor" class="form-control @error('nomor_permohonan_pho_vendor') is-invalid @enderror" placeholder="Masukan Nomor Permohonan dari Vendor" value="{{ old('nomor_permohonan_pho_vendor', $pho->nomor_permohonan_pho_vendor) }}">
                                    @error('nomor_permohonan_pho_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan_pho_vendor">Tanggal Permohonan dari Vendor</label>
                                    <input type="date" id="tanggal_permohonan_pho_vendor" name="tanggal_permohonan_pho_vendor" class="form-control @error('tanggal_permohonan_pho_vendor') is-invalid @enderror" placeholder="Masukan Tanggal Permohonan dari Vendor" value="{{ old('tanggal_permohonan_pho_vendor', $pho->tanggal_permohonan_pho_vendor) }}">
                                    @error('tanggal_permohonan_pho_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="fho" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_surat_permohonan_fho_vendor">Nomor Surat Permohonan FHO Vendor</label>
                                    <input type="text" id="nomor_surat_permohonan_fho_vendor" name="nomor_surat_permohonan_fho_vendor" class="form-control @error('nomor_surat_permohonan_fho_vendor') is-invalid @enderror" placeholder="Masukan Nomor Surat Permohonan FHO Vendor" value="{{ old('nomor_surat_permohonan_fho_vendor', $fho->nomor_surat_permohonan_fho_vendor) }}">
                                    @error('nomor_surat_permohonan_fho_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_surat_permohonan_fho_vendor">Tanggal Surat Permohonan FHO Vendor</label>
                                    <input type="date" id="tanggal_surat_permohonan_fho_vendor" name="tanggal_surat_permohonan_fho_vendor" class="form-control @error('tanggal_surat_permohonan_fho_vendor') is-invalid @enderror" placeholder="Masukan Tanggal Surat Permohonan FHO Vendor" value="{{ old('tanggal_surat_permohonan_fho_vendor', $fho->tanggal_surat_permohonan_fho_vendor) }}">
                                    @error('tanggal_surat_permohonan_fho_vendor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_surat_laporan_tindak_lanjut_fho">Nomor Surat Laporan Tindak Lanjut Perbaikan FHO</label>
                                    <input type="text" id="nomor_surat_laporan_tindak_lanjut_fho" name="nomor_surat_laporan_tindak_lanjut_fho" class="form-control @error('nomor_surat_laporan_tindak_lanjut_fho') is-invalid @enderror" placeholder="Masukan Nomor Surat Laporan Tindak Lanjut Perbaikan FHO" value="{{ old('nomor_surat_laporan_tindak_lanjut_fho', $fho->nomor_surat_laporan_tindak_lanjut_fho) }}">
                                    @error('nomor_surat_laporan_tindak_lanjut_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_surat_laporan_tindak_lanjut_fho">Tanggal Surat Laporan Tindak Lanjut Perbaikan FHO</label>
                                    <input type="date" id="tanggal_surat_laporan_tindak_lanjut_fho" name="tanggal_surat_laporan_tindak_lanjut_fho" class="form-control @error('tanggal_surat_laporan_tindak_lanjut_fho') is-invalid @enderror" placeholder="Masukan Tanggal Surat Laporan Tindak Lanjut Perbaikan FHO" value="{{ old('tanggal_surat_laporan_tindak_lanjut_fho', $fho->tanggal_surat_laporan_tindak_lanjut_fho) }}">
                                    @error('tanggal_surat_laporan_tindak_lanjut_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bapp_pada_fho">Nomor BAPP</label>
                                    <input type="text" id="nomor_bapp_pada_fho" name="nomor_bapp_pada_fho" class="form-control @error('nomor_bapp_pada_fho') is-invalid @enderror" placeholder="Masukan Nomor BAPP" value="{{ old('nomor_bapp_pada_fho', $fho->nomor_bapp_pada_fho) }}">
                                    @error('nomor_bapp_pada_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bapp_pada_fho">Tanggal BAPP</label>
                                    <input type="date" id="tanggal_bapp_pada_fho" name="tanggal_bapp_pada_fho" class="form-control @error('tanggal_bapp_pada_fho') is-invalid @enderror" placeholder="Masukan Tanggal BAPP" value="{{ old('tanggal_bapp_pada_fho', $fho->tanggal_bapp_pada_fho ) }}">
                                    @error('tanggal_bapp_pada_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bastp_pada_fho">Nomor BASTP</label>
                                    <input type="text" id="nomor_bastp_pada_fho" name="nomor_bastp_pada_fho" class="form-control @error('nomor_bastp_pada_fho') is-invalid @enderror" placeholder="Masukan Nomor BASTP" value="{{ old('nomor_bastp_pada_fho', $fho->nomor_bastp_pada_fho) }}">
                                    @error('nomor_bastp_pada_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_bastp_pada_fho">Tanggal BASTP</label>
                                    <input type="date" id="tanggal_bastp_pada_fho" name="tanggal_bastp_pada_fho" class="form-control @error('tanggal_bastp_pada_fho') is-invalid @enderror" placeholder="Masukan Tanggal BASTP" value="{{ old('tanggal_bastp_pada_fho', $fho->tanggal_bastp_pada_fho) }}">
                                    @error('tanggal_bastp_pada_fho')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-4">
                <a href="{{ route('berkas_pbj.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success float-right">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</section>
<!-- /.content -->

@endsection
@section('script_footer')
<script>
    function clearFileInput() {
        // Mengosongkan input file dan label
        document.getElementById('file_path').value = '';
        document.getElementById('fileLabel').textContent = 'Pilih file';
    }

    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const fileName = event.target.files.length
                ? event.target.files[0].name
                : 'Pilih file';
            const label = event.target.nextElementSibling;
            label.textContent = fileName;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const formSubmit = document.getElementById('form-edit-berkas');

        formSubmit.addEventListener('submit', function(e) {
            let formError = false;

            // BAPP
            const nomorBappInput = document.getElementById('nomor_bapp');
            const bappOtherFields = [
                document.getElementById('tanggal_bapp'),
                document.getElementById('nomor_permohonan_bapp'),
                document.getElementById('tanggal_permohonan_bapp')
            ];

            // Bersihkan error
            [nomorBappInput, ...bappOtherFields].forEach(field => {
                if (!field) return;
                field.classList.remove('is-invalid');
                let nextError = field.nextElementSibling;
                while (nextError && nextError.classList.contains('invalid-feedback')) {
                    let toRemove = nextError;
                    nextError = nextError.nextElementSibling;
                    toRemove.remove();
                }
            });

            let bappOtherFilled = bappOtherFields.some(field => field && field.value.trim() !== '');
            if (bappOtherFilled && nomorBappInput.value.trim() === '') {
                e.preventDefault();
                formError = true;
                nomorBappInput.classList.add('is-invalid');
                const errorMsg = document.createElement('span');
                errorMsg.classList.add('invalid-feedback');
                errorMsg.setAttribute('role', 'alert');
                errorMsg.innerHTML = '<strong>Kolom nomor BAPP wajib diisi jika field BAPP lain diisi.</strong>';
                nomorBappInput.parentNode.appendChild(errorMsg);
            }

            // BASTP
            const nomorBastpInput = document.getElementById('nomor_bastp');
            const bastpOtherFields = [
                document.getElementById('tanggal_bastp'),
                document.getElementById('nomor_permohonan_bastp'),
                document.getElementById('tanggal_permohonan_bastp'),
                document.getElementById('jumlah_bayar_termin_1_bastp')
            ];
            [nomorBastpInput, ...bastpOtherFields].forEach(field => {
                if (!field) return;
                field.classList.remove('is-invalid');
                let nextError = field.nextElementSibling;
                while (nextError && nextError.classList.contains('invalid-feedback')) {
                    let toRemove = nextError;
                    nextError = nextError.nextElementSibling;
                    toRemove.remove();
                }
            });
            let bastpOtherFilled = bastpOtherFields.some(field => field && field.value.trim() !== '');
            if (bastpOtherFilled && nomorBastpInput.value.trim() === '') {
                e.preventDefault();
                formError = true;
                nomorBastpInput.classList.add('is-invalid');
                const errorMsg = document.createElement('span');
                errorMsg.classList.add('invalid-feedback');
                errorMsg.setAttribute('role', 'alert');
                errorMsg.innerHTML = '<strong>Kolom nomor BASTP wajib diisi jika field BASTP lain diisi.</strong>';
                nomorBastpInput.parentNode.appendChild(errorMsg);
            }

            // PHO
            const nomorPhoInput = document.getElementById('nomor_ba_pemeriksaan_pekerjaan_pho');
            const phoOtherFields = [
                document.getElementById('tanggal_ba_pemeriksaan_pekerjaan_pho'),
                document.getElementById('nomor_ba_serah_terima_pho'),
                document.getElementById('tanggal_ba_serah_terima_pho'),
                document.getElementById('nomor_bapp_pada_pho'),
                document.getElementById('tanggal_bapp_pada_pho'),
                document.getElementById('nomor_bastp_pada_pho'),
                document.getElementById('tanggal_bastp_pada_pho'),
                document.getElementById('nomor_permohonan_pho_vendor'),
                document.getElementById('tanggal_permohonan_pho_vendor')
            ];
            [nomorPhoInput, ...phoOtherFields].forEach(field => {
                if (!field) return;
                field.classList.remove('is-invalid');
                let nextError = field.nextElementSibling;
                while (nextError && nextError.classList.contains('invalid-feedback')) {
                    let toRemove = nextError;
                    nextError = nextError.nextElementSibling;
                    toRemove.remove();
                }
            });
            let phoOtherFilled = phoOtherFields.some(field => field && field.value.trim() !== '');
            if (phoOtherFilled && nomorPhoInput.value.trim() === '') {
                e.preventDefault();
                formError = true;
                nomorPhoInput.classList.add('is-invalid');
                const errorMsg = document.createElement('span');
                errorMsg.classList.add('invalid-feedback');
                errorMsg.setAttribute('role', 'alert');
                errorMsg.innerHTML = '<strong>Kolom nomor BA Pemeriksaan Pekerjaan PHO wajib diisi jika field PHO lain diisi.</strong>';
                nomorPhoInput.parentNode.appendChild(errorMsg);
            }

            // FHO
            const nomorFhoInput = document.getElementById('nomor_surat_permohonan_fho_vendor');
            const fhoOtherFields = [
                document.getElementById('tanggal_surat_permohonan_fho_vendor'),
                document.getElementById('nomor_surat_laporan_tindak_lanjut_fho'),
                document.getElementById('tanggal_surat_laporan_tindak_lanjut_fho'),
                document.getElementById('nomor_bapp_pada_fho'),
                document.getElementById('tanggal_bapp_pada_fho'),
                document.getElementById('nomor_bastp_pada_fho'),
                document.getElementById('tanggal_bastp_pada_fho')
            ];
            [nomorFhoInput, ...fhoOtherFields].forEach(field => {
                if (!field) return;
                field.classList.remove('is-invalid');
                let nextError = field.nextElementSibling;
                while (nextError && nextError.classList.contains('invalid-feedback')) {
                    let toRemove = nextError;
                    nextError = nextError.nextElementSibling;
                    toRemove.remove();
                }
            });
            let fhoOtherFilled = fhoOtherFields.some(field => field && field.value.trim() !== '');
            if (fhoOtherFilled && nomorFhoInput.value.trim() === '') {
                e.preventDefault();
                formError = true;
                nomorFhoInput.classList.add('is-invalid');
                const errorMsg = document.createElement('span');
                errorMsg.classList.add('invalid-feedback');
                errorMsg.setAttribute('role', 'alert');
                errorMsg.innerHTML = '<strong>Kolom nomor Surat Permohonan FHO Vendor wajib diisi jika field FHO lain diisi.</strong>';
                nomorFhoInput.parentNode.appendChild(errorMsg);
            }

            // Optional: Clear validation styles on input
            document.querySelectorAll('.is-invalid').forEach(function(field) {
                field.addEventListener('input', function() {
                    if (field.value.trim() !== '') {
                        field.classList.remove('is-invalid');
                        const errorMsg = field.nextElementSibling;
                        if (errorMsg && errorMsg.classList.contains('invalid-feedback')) {
                            errorMsg.remove();
                        }
                    }
                });
            });

            if (formError) {
                // Optional: Show alert at the top if desired
                const existingAlert = document.querySelector('.alert-danger');
                if (!existingAlert) {
                    const alertDiv = document.createElement('div');
                    alertDiv.classList.add('alert', 'alert-danger', 'mt-3');
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.innerHTML = '<strong>Perhatian!</strong> Nomor wajib diisi jika Anda mengisi bagian data lain pada tab terkait.';
                    formSubmit.insertBefore(alertDiv, formSubmit.firstChild);

                    setTimeout(function() {
                        alertDiv.remove();
                    }, 5000);
                }
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
</script>
@endsection