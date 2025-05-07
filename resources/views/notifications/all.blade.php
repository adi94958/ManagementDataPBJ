@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Semua Notifikasi')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Semua Notifikasi</h1>

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="breadcrumb-item active">Notifikasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Notifikasi</h3>
                <div class="card-tools">
                    <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pesan</th>
                                <th width="200">Waktu</th>
                                <th width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($notifications->count() > 0)
                                @foreach ($notifications as $notification)
                                    <tr class="{{ $notification->read_at ? '' : 'bg-light' }}">
                                        <td>
                                            {{ $notification->data['message'] }}
                                            <br>
                                            @if (isset($notification->data['type']))
                                                @if ($notification->data['type'] == 'kontrak')
                                                    <span class="badge badge-primary">Kontrak</span>
                                                @elseif($notification->data['type'] == 'pemeliharaan')
                                                    <span class="badge badge-info">Pemeliharaan</span>
                                                @endif
                                            @endif
                                        </td>


                                        <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, H:i') }}
                                        </td>
                                        <td>
                                            @if ($notification->read_at)
                                                <span class="badge badge-success">Dibaca</span>
                                            @else
                                                <span class="badge badge-warning">Belum Dibaca</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada notifikasi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $notifications->links('pagination::simple-bootstrap-4') }}
            </div>
        </div>
    </section>
@endsection
