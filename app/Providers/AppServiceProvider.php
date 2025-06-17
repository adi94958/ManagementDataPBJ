<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID.UTF-8', 'id_ID');

        View::composer('layouts.sidebar', function ($view) {
            // Periksa apakah pengguna sudah login
            if (Auth::check()) {
                // Jika sudah login, ambil role
                $role = Auth::user()->role;

                // Pilih sidebar yang sesuai berdasarkan peran
                $sidebar = "sidebar-{$role}";

                // Tampilkan sidebar yang dipilih
                $view->with(['sidebar' => $sidebar, 'userRole' => $role]);
            } else {
                // Jika pengguna belum login, set nilai default
                $view->with(['sidebar' => 'default-sidebar', 'userRole' => 'guest']);
            }
        });
    }
}
