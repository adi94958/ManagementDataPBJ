<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
