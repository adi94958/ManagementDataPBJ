@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Tambah Berkas & Tagihan PBJ')
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
                <h1>Tambah Berkas PBJ</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Berkas PBJ</li>
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

    <form method="post" action="{{ route('berkas_pbj.add') }}" id="form-tambah-berkas">
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
                                    <input type="text" id="nomor_kontrak" name="nomor_kontrak" class="form-control @error('nomor_kontrak') is-invalid @enderror" placeholder="Masukkan Nomor Kontrak" value="{{ old('nomor_kontrak') }}" required>
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
                                    <input type="text" id="nama_kontrak" name="nama_kontrak" class="form-control @error('nama_kontrak') is-invalid @enderror" placeholder="Masukkan Nama Kontrak" value="{{ old('nama_kontrak') }}" required>
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
                                    <input type="date" id="tanggal_kontrak_mulai" name="tanggal_kontrak_mulai" class="form-control @error('tanggal_kontrak_mulai') is-invalid @enderror" value="{{ old('tanggal_kontrak_mulai') }}" required>
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
                                    <input type="date" id="tanggal_kontrak_selesai" name="tanggal_kontrak_selesai" class="form-control @error('tanggal_kontrak_selesai') is-invalid @enderror" value="{{ old('tanggal_kontrak_selesai') }}" required>
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
                                    <label for="nilai_kontrak_pbj">Nilai Kontrak <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="nilai_kontrak_pbj" name="nilai_kontrak_pbj" class="form-control @error('nilai_kontrak_pbj') is-invalid @enderror" placeholder="Masukkan Nilai Kontrak" value="{{ old('nilai_kontrak_pbj') }}" required>
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
                                    <input type="text" id="nama_vendor" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror" placeholder="Masukkan Nama Vendor" value="{{ old('nama_vendor') }}" required>
                                    @error('nama_vendor')
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
                                    <input type="text" id="nomor_bapp" name="nomor_bapp" class="form-control @error('nomor_bapp') is-invalid @enderror" placeholder="Masukkan Nomor BAPP" value="{{ old('nomor_bapp') }}">
                                    @error('nomor_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_permohonan_bapp">Nomor Permohonan dari Vendor</label>
                                    <input type="text" id="nomor_permohonan_bapp" name="nomor_permohonan_bapp" class="form-control @error('nomor_permohonan_bapp') is-invalid @enderror" placeholder="Masukkan Nomor Permnohonan" value="{{ old('nomor_permohonan_bapp') }}">
                                    @error('nomor_permohonan_bapp')
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
                                    <label for="tanggal_bapp">Tanggal BAPP</label>
                                    <input type="date" id="tanggal_bapp" name="tanggal_bapp" class="form-control @error('tanggal_bapp') is-invalid @enderror" placeholder="Masukkan Tanggal BAPP" value="{{ old('tanggal_bapp') }}">
                                    @error('tanggal_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan_bapp">Tanggal Permohonan dari Vendor</label>
                                    <input type="date" id="tanggal_permohonan_bapp" name="tanggal_permohonan_bapp" class="form-control @error('tanggal_permohonan_bapp') is-invalid @enderror" placeholder="Masukkan Tanggal Permohonan" value="{{ old('tanggal_permohonan_bapp') }}">
                                    @error('tanggal_permohonan_bapp')
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
                                    <label for="jumlah_bayar_termin_1_bapp">Jumlah Bayar Termin 1</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" id="jumlah_bayar_termin_1_bapp" name="jumlah_bayar_termin_1_bapp" class="form-control @error('jumlah_bayar_termin_1_bapp') is-invalid @enderror" placeholder="Masukkan Jumlah Bayar Termin 1" value="{{ old('jumlah_bayar_termin_1_bapp') }}">
                                        @error('jumlah_bayar_termin_1_bapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jangka_waktu_pemeliharaan_bapp">Jangka Waktu Pemeliharaan</label>
                                    <div class="input-group">
                                        <input type="text" id="jangka_waktu_pemeliharaan_bapp" name="jangka_waktu_pemeliharaan_bapp" class="form-control @error('jangka_waktu_pemeliharaan_bapp') is-invalid @enderror" placeholder="Masukkan Jangka Waktu Pemeliharaan" value="{{ old('jangka_waktu_pemeliharaan_bapp') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                        @error('jangka_waktu_pemeliharaan_bapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="bastp" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_bastp">Nomor BASTP</label>
                                    <input type="text" id="nomor_bastp" name="nomor_bastp" class="form-control @error('nomor_bastp') is-invalid @enderror" placeholder="Masukkan Nomor BASTP" value="{{ old('nomor_bastp') }}">
                                    @error('nomor_bastp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_permohonan_bastp">Nomor Permohonan dari Vendor</label>
                                    <input type="text" id="nomor_permohonan_bastp" name="nomor_permohonan_bastp" class="form-control @error('nomor_permohonan_bastp') is-invalid @enderror" placeholder="Masukkan Nomor Permnohonan" value="{{ old('nomor_permohonan_bastp') }}">
                                    @error('nomor_permohonan_bastp')
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
                                    <label for="tanggal_bastp">Tanggal BASTP</label>
                                    <input type="date" id="tanggal_bastp" name="tanggal_bastp" class="form-control @error('tanggal_bastp') is-invalid @enderror" placeholder="Masukkan Tanggal BASTP" value="{{ old('tanggal_bastp') }}">
                                    @error('tanggal_bapp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_permohonan_bastp">Tanggal Permohonan dari Vendor</label>
                                    <input type="date" id="tanggal_permohonan_bastp" name="tanggal_permohonan_bastp" class="form-control @error('tanggal_permohonan_bastp') is-invalid @enderror" placeholder="Masukkan Tanggal Permohonan" value="{{ old('tanggal_permohonan_bastp') }}">
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
                                        <input type="text" id="jumlah_bayar_termin_1_bastp" name="jumlah_bayar_termin_1_bastp" class="form-control @error('jumlah_bayar_termin_1_bastp') is-invalid @enderror" placeholder="Masukkan Jumlah Bayar Termin 1" value="{{ old('jumlah_bayar_termin_1_bastp') }}">
                                        @error('jumlah_bayar_termin_1_bastp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jangka_waktu_pemeliharaan_bastp">Jangka Waktu Pemeliharaan</label>
                                    <div class="input-group">
                                        <input type="text" id="jangka_waktu_pemeliharaan_bastp" name="jangka_waktu_pemeliharaan_bastp" class="form-control @error('jangka_waktu_pemeliharaan_bastp') is-invalid @enderror" placeholder="Masukkan Jangka Waktu Pemeliharaan" value="{{ old('jangka_waktu_pemeliharaan_bastp') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                        @error('jangka_waktu_pemeliharaan_bastp')
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
                                    <input type="text" id="nomor_ba_pemeriksaan_pekerjaan_pho" name="nomor_ba_pemeriksaan_pekerjaan_pho" class="form-control @error('nomor_ba_pemeriksaan_pekerjaan_pho') is-invalid @enderror" placeholder="Masukkan Nomor BA Pemeriksaan Pekerjaan PHO" value="{{ old('nomor_ba_pemeriksaan_pekerjaan_pho') }}">
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
                                    <input type="date" id="tanggal_ba_pemeriksaan_pekerjaan_pho" name="tanggal_ba_pemeriksaan_pekerjaan_pho" class="form-control @error('tanggal_ba_pemeriksaan_pekerjaan_pho') is-invalid @enderror" placeholder="Masukkan Tanggal BA Pemeriksaan Pekerjaan PHO" value="{{ old('tanggal_ba_pemeriksaan_pekerjaan_pho') }}">
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
                                    <input type="text" id="nomor_ba_serah_terima_pho" name="nomor_ba_serah_terima_pho" class="form-control @error('nomor_ba_serah_terima_pho') is-invalid @enderror" placeholder="Masukkan Nomor BA Serah Terima PHO" value="{{ old('nomor_ba_serah_terima_pho') }}">
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
                                    <input type="date" id="tanggal_ba_serah_terima_pho" name="tanggal_ba_serah_terima_pho" class="form-control @error('tanggal_ba_serah_terima_pho') is-invalid @enderror" placeholder="Masukkan Tanggal BA Serah Terima PHO" value="{{ old('tanggal_ba_serah_terima_pho') }}">
                                    @error('tanggal_ba_serah_terima_pho')
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
                                    <input type="text" id="nomor_surat_permohonan_fho_vendor" name="nomor_surat_permohonan_fho_vendor" class="form-control @error('nomor_surat_permohonan_fho_vendor') is-invalid @enderror" placeholder="Masukkan Nomor Surat Permohonan FHO Vendor" value="{{ old('nomor_surat_permohonan_fho_vendor') }}">
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
                                    <input type="date" id="tanggal_surat_permohonan_fho_vendor" name="tanggal_surat_permohonan_fho_vendor" class="form-control @error('tanggal_surat_permohonan_fho_vendor') is-invalid @enderror" placeholder="Masukkan Tanggal Surat Permohonan FHO Vendor" value="{{ old('tanggal_surat_permohonan_fho_vendor') }}">
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
                                    <input type="text" id="nomor_surat_laporan_tindak_lanjut_fho" name="nomor_surat_laporan_tindak_lanjut_fho" class="form-control @error('nomor_surat_laporan_tindak_lanjut_fho') is-invalid @enderror" placeholder="Masukkan Nomor Surat Laporan Tindak Lanjut Perbaikan FHO" value="{{ old('nomor_surat_laporan_tindak_lanjut_fho') }}">
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
                                    <input type="date" id="tanggal_surat_laporan_tindak_lanjut_fho" name="tanggal_surat_laporan_tindak_lanjut_fho" class="form-control @error('tanggal_surat_laporan_tindak_lanjut_fho') is-invalid @enderror" placeholder="Masukkan Tanggal Surat Laporan Tindak Lanjut Perbaikan FHO" value="{{ old('tanggal_surat_laporan_tindak_lanjut_fho') }}">
                                    @error('tanggal_surat_laporan_tindak_lanjut_fho')
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
                <button type="submit" class="btn btn-success float-right">Tambah Berkas PBJ</button>
            </div>
        </div>
    </form>
</section>

@endsection
@section('script_footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formSubmit = document.getElementById('form-tambah-berkas');

        formSubmit.addEventListener('submit', function(e) {
            // Check BAPP tab
            const bappTab = document.getElementById('bapp');
            const bappFields = bappTab.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
            let bappFilled = false;
            let bappComplete = true;

            bappFields.forEach(function(field) {
                if (field.value.trim() !== '') {
                    bappFilled = true;
                }
            });

            if (bappFilled) {
                bappFields.forEach(function(field) {
                    if (field.value.trim() === '') {
                        bappComplete = false;
                        field.classList.add('is-invalid');
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                            const errorMsg = document.createElement('span');
                            errorMsg.classList.add('invalid-feedback');
                            errorMsg.setAttribute('role', 'alert');
                            errorMsg.innerHTML = '<strong>Bidang ini harus diisi jika Anda mengisi bagian BAPP.</strong>';
                            field.parentNode.appendChild(errorMsg);
                        }
                    }
                });
            }

            // Check BASTP tab
            const bastpTab = document.getElementById('bastp');
            const bastpFields = bastpTab.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
            let bastpFilled = false;
            let bastpComplete = true;

            bastpFields.forEach(function(field) {
                if (field.value.trim() !== '') {
                    bastpFilled = true;
                }
            });

            if (bastpFilled) {
                bastpFields.forEach(function(field) {
                    if (field.value.trim() === '') {
                        bastpComplete = false;
                        field.classList.add('is-invalid');
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                            const errorMsg = document.createElement('span');
                            errorMsg.classList.add('invalid-feedback');
                            errorMsg.setAttribute('role', 'alert');
                            errorMsg.innerHTML = '<strong>Bidang ini harus diisi jika Anda mengisi bagian BASTP.</strong>';
                            field.parentNode.appendChild(errorMsg);
                        }
                    }
                });
            }

            // Check PHO tab
            const phoTab = document.getElementById('pho');
            const phoFields = phoTab.querySelectorAll('input[type="text"], input[type="date"]');
            let phoFilled = false;
            let phoComplete = true;

            phoFields.forEach(function(field) {
                if (field.value.trim() !== '') {
                    phoFilled = true;
                }
            });

            if (phoFilled) {
                phoFields.forEach(function(field) {
                    if (field.value.trim() === '') {
                        phoComplete = false;
                        field.classList.add('is-invalid');
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                            const errorMsg = document.createElement('span');
                            errorMsg.classList.add('invalid-feedback');
                            errorMsg.setAttribute('role', 'alert');
                            errorMsg.innerHTML = '<strong>Bidang ini harus diisi jika Anda mengisi bagian PHO.</strong>';
                            field.parentNode.appendChild(errorMsg);
                        }
                    }
                });
            }

            // If any of the tabs are filled but incomplete, prevent form submission
            if ((bappFilled && !bappComplete) || (bastpFilled && !bastpComplete) || (phoFilled && !phoComplete)) {
                e.preventDefault();
                e.stopPropagation();

                // Show alert message
                const alertDiv = document.createElement('div');
                alertDiv.classList.add('alert', 'alert-danger', 'mt-3');
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = '<strong>Perhatian!</strong> Harap lengkapi semua bidang pada tab yang Anda isi sebelum mengirimkan formulir.';

                // Insert alert at the top of the form
                formSubmit.insertBefore(alertDiv, formSubmit.firstChild);

                // Auto-scroll to the alert
                alertDiv.scrollIntoView({
                    behavior: 'smooth'
                });

                // Remove alert after 5 seconds
                setTimeout(function() {
                    alertDiv.remove();
                }, 5000);
            }

            // Optional: Clear validation styles when fields are filled
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

            // If validation passes and form is about to be submitted
            if (!((bappFilled && !bappComplete) || (bastpFilled && !bastpComplete) || (phoFilled && !phoComplete))) {
                // Prevent the default form submission
                e.preventDefault();

                // Show "loading" SweetAlert
                Swal.fire({
                    title: 'Sedang Memproses',
                    html: 'Mohon tunggu sebentar...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form programmatically
                setTimeout(() => {
                    formSubmit.submit();
                }, 500);
            }

        });
    });
</script>
@endsection