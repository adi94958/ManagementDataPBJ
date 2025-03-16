<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\BerkasPBJController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['prefix' => 'dashboard/superadmin', 'middleware' => ['auth', 'checkRole:1']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('superadmin.dashboard');

    Route::controller(AkunController::class)
        ->prefix('akun')
        ->as('akun.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('showdata', 'dataTable')->name('dataTable');
            Route::match(['get', 'post'], 'tambah', 'tambahAkun')->name('add');
            Route::match(['get', 'post'], '{id}/ubah', 'ubahAkun')->name('edit');
            Route::delete('{id}/hapus', 'hapusAkun')->name('delete');
        });
});

Route::group(['prefix' => 'dashboard/admin', 'middleware' => ['auth', 'checkRole:2']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('profile');
        Route::post('update', [HomeController::class, 'updateprofile'])->name('profile.update');
    });

    Route::controller(BerkasPBJController::class)
        ->prefix('berkas_pbj')
        ->as('berkas_pbj.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('dataTable', 'dataTable')->name('dataTable');
            Route::post('getBappDetails', 'getBappDetails')->name('getBappDetails');
            Route::post('getBastpDetails', 'getBastpDetails')->name('getBastpDetails');
            Route::post('getPhoDetails', 'getPhoDetails')->name('getPhoDetails');
            Route::post('getFhoDetails', 'getFhoDetails')->name('getFhoDetails');
            Route::match(['get', 'post'], 'tambah', 'tambahBerkasPBJ')->name('add');
            Route::match(['get', 'post'], 'update', 'update')->name('update');
            Route::delete('delete', 'delete')->name('delete');
        });
});
