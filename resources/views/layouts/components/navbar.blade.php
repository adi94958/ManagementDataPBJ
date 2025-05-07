<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        @if (auth()->user()->role == 'user')
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/dashboard/user" class="nav-link">Home</a>
            </li>
        @endif
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        @if (auth()->user()->role == 'user')
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <span
                            class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ auth()->user()->unreadNotifications->count() }}
                        Notifikasi</span>
                    <div class="dropdown-divider"></div>

                    @if (auth()->user()->unreadNotifications->count() > 0)
                        @foreach (auth()->user()->unreadNotifications->take(2) as $notification)
                            <a href="{{ route('notifications.all') }}" class="dropdown-item text-wrap"
                                style="white-space: normal; max-width: 300px;">
                                <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
                                <span class="d-inline-block"
                                    style="max-width: 100%;">{{ $notification->data['message'] }}</span>
                                <span
                                    class="float-right text-muted text-sm">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                        @endforeach

                        @if (auth()->user()->unreadNotifications->count() > 2)
                            <a href="{{ route('notifications.all') }}" class="dropdown-item text-center">
                                {{ auth()->user()->unreadNotifications->count() - 2 }} notifikasi lainnya
                            </a>
                            <div class="dropdown-divider"></div>
                        @endif
                    @else
                        <a href="#" class="dropdown-item text-wrap" style="white-space: normal;">
                            <i class="fas fa-check mr-2 text-success"></i> Tidak ada notifikasi baru
                        </a>
                        <a href="{{ route('notifications.all') }}" class="dropdown-item text-center">
                            Cek Notifikasi
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
                </div>
            </li>
        @endif

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                @if (Auth::user()->user_image)
                    <img src="{{ Auth::user()->user_image }}" class="user-image img-circle elevation-2"
                        alt="User Imagess">
                @else
                    <img src="{{ asset('vendor/adminlte3/img/user.png') }}"
                        class="user-image img-circle elevation-2" alt="User Imagess">
                @endif
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    @if (Auth::user()->user_image)
                        <img src="{{ Auth::user()->user_image }}" class="img-circle elevation-2" alt="User Imagess">
                    @else
                        <img src="{{ asset('vendor/adminlte3/img/user.png') }}" class="img-circle elevation-2"
                            alt="User Imagess">
                    @endif

                    <p>
                        {{ Auth::user()->name }}
                        <small>Bergabung pada @DateIndo(Auth::user()->created_at)</small>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    @if (auth()->user()->role == 'admin')
                        <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">Profile</a>
                    @else
                        <a href="{{ route('user.profile') }}" class="btn btn-default btn-flat">Profile</a>
                    @endif
                    <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <i class="ni ni-user-run"></i>
                        <span>Logout</span>
                    </a>
                    {{-- <a href="#" class="btn btn-default btn-flat float-right">Sign out</a> --}}
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
