<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\BerkasPBJ;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Artisan::call('cek:tanggal-kontrak');
        $status = $request->status ?? 'semua';
        $query = BerkasPBJ::query();


        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {

            // Ini logika kalau mau lebih ketat jadi hanya menampilkan dari range mulai dan akhir yang dipilih user
            $query->whereDate('tanggal_kontrak_mulai', '>=', $request->tanggal_awal)
                ->whereDate('tanggal_kontrak_selesai', '<=', $request->tanggal_akhir);
        }


        // Filter berdasarkan status
        $hariIni = Carbon::now();
        if ($status == 'aktif') {
            $query->where('tanggal_kontrak_selesai', '>=', $hariIni);
        } elseif ($status == 'berakhir') {
            $query->where('tanggal_kontrak_selesai', '<', $hariIni);
        }

        // Clone query untuk mendapatkan total sebelum filtering tambahan
        $totalKontrak = $query->count();

        // Kontrak aktif (tanggal_kontrak_selesai >= hari ini)
        $kontrakAktif = $query->clone()->where('tanggal_kontrak_selesai', '>=', $hariIni)->count();

        // Kontrak berakhir (tanggal_kontrak_selesai < hari ini)
        $kontrakBerakhir = $query->clone()->where('tanggal_kontrak_selesai', '<', $hariIni)->count();

        // Total nilai kontrak
        $totalNilaiKontrak = $query->clone()->sum('nilai_kontrak_pbj');

        // Data untuk tabel kontrak
        $kontrakData = $query->clone()
            ->orderBy('tanggal_kontrak_mulai', 'desc')
            ->get();

        return view('home', compact(
            'totalKontrak',
            'kontrakAktif',
            'kontrakBerakhir',
            'totalNilaiKontrak',
            'kontrakData'
        ));
    }

    public function profile()
    {
        return view('page.profile');
    }

    public function updateprofile(Request $request)
    {
        $usr = User::findOrFail(Auth::user()->id);
        if ($request->input('type') == 'change_profile') {
            $this->validate($request, [
                'name' => 'string|max:200|min:3',
                'email' => 'string|min:3|email',
                'user_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1024'
            ]);

            // Menyiapkan data untuk diupdate
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Jika ada file gambar yang diunggah
            if ($request->hasFile('user_image')) {
                $img_old = Auth::user()->user_image;

                // Hapus gambar lama jika ada
                if ($img_old && file_exists(public_path() . $img_old)) {
                    Storage::delete(str_replace('/storage', 'public', $img_old));
                }

                // Tentukan folder penyimpanan berdasarkan role
                $role = Auth::user()->role;
                $folder = $role === 'admin' ? 'admin/user_profile' : 'user/user_profile';

                // Upload gambar baru
                $nama_gambar = time() . '_' . $request->file('user_image')->getClientOriginalName();
                $upload = $request->file('user_image')->storeAs("public/$folder", $nama_gambar);

                // Simpan path yang dapat diakses publik
                $data['user_image'] = Storage::url($upload);
            }

            // Update data user
            $usr->update($data);
            return redirect()->route(Auth::user()->role . '.profile')->with('status', 'Perubahan telah tersimpan');
        } elseif ($request->input('type') == 'change_password') {
            $this->validate($request, [
                'password' => 'min:8|confirmed|required',
                'password_confirmation' => 'min:8|required',
            ]);
            $usr->update([
                'password' => Hash::make($request->password)
            ]);
            return redirect()->route(Auth::user()->role . '.profile')->with('status', 'Perubahan telah tersimpan');
        }
    }
}
