    <?php

    use App\Http\Controllers\AkunController;
    use App\Http\Controllers\BerkasPBJController;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\NotificationController;

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

    Route::get('/debug-session', function () {
        return [
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'csrf_token' => csrf_token(),
            'cookies' => request()->cookies->all(),
            'headers' => request()->headers->all(),
        ];
    });

    Route::get('/login', function () {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->role == 'user') {
                return redirect()->route('home');
            }
        }
        return view('auth.login');
    })->name('login');

    Auth::routes();

    Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {
        Route::group(['prefix' => 'dashboard/admin', 'middleware' => ['auth']], function () {
            Route::get('/', function () {
                return redirect()->route('akun.index'); // Ganti 'home' dengan nama route tujuanmu
            })->name('admin.index');

            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', [HomeController::class, 'profile'])->name('admin.profile');
                Route::post('update', [HomeController::class, 'updateprofile'])->name('admin.profile.update');
            });

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

        Route::group(['prefix' => 'dashboard/user', 'middleware' => ['auth', 'checkRole:user']], function () {
            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', [HomeController::class, 'profile'])->name('user.profile');
                Route::post('update', [HomeController::class, 'updateprofile'])->name('user.profile.update');
            });

            Route::controller(BerkasPBJController::class)
                ->prefix('berkas_pbj')
                ->as('berkas_pbj.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('dataTable', 'dataTable')->name('dataTable');
                    Route::match(['get', 'post'], 'tambah', 'tambahBerkasPBJ')->name('add');
                    Route::match(['get', 'post'], 'update', 'update')->name('update');
                    Route::match(['get', 'post'], 'detail', 'getDetail')->name('detail');
                    Route::delete('delete', 'delete')->name('delete');
                });
        });

        // Notification routes
        Route::group(['middleware' => 'auth', 'prefix' => 'notifications'], function () {
            Route::get('all', [NotificationController::class, 'allNotifications'])->name('notifications.all');
            Route::post('mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
            Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
        });
    });

    Route::fallback(function () {
        // Redirect ke halaman login jika belum login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Redirect ke dashboard berdasarkan role
        if (auth()->user()->role == 'admin') {
            return redirect()->route('akun.index');
        } elseif (auth()->user()->role == 'user') {
            return redirect()->route('home');
        }

        // Default fallback jika role tidak dikenal
        abort(403, 'Unauthorized action.');
    });
