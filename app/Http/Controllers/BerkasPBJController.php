<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerkasPBJ;
use App\Models\TagihanFHO;
use App\Models\TagihanPHO;
use App\Models\TagihanBAPP;
use App\Models\TagihanBASTP;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class BerkasPBJController extends Controller
{
    public function index()
    {
        return view('page.user.berkas_pbj.index');
    }

    public function dataTable(Request $request)
    {
        $query = BerkasPBJ::query();

        return DataTables::of($query)
            ->addColumn('jangka_waktu_tersisa', function ($berkas) {
                $now = now();
                $tanggalSelesai = Carbon::parse($berkas->tanggal_kontrak_selesai);

                if ($now->greaterThan($tanggalSelesai)) {
                    return "Kontrak telah berakhir";
                }
                return $now->diffInDays($tanggalSelesai) . " hari tersisa";
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nomor_kontrak', 'LIKE', "%{$search}%")
                            ->orWhere('nama_kontrak', 'LIKE', "%{$search}%")
                            ->orWhere('tanggal_kontrak_mulai', 'LIKE', "%{$search}%")
                            ->orWhere('tanggal_kontrak_selesai', 'LIKE', "%{$search}%")
                            ->orWhere('nilai_kontrak_pbj', 'LIKE', "%{$search}%")
                            ->orWhere('nama_vendor', 'LIKE', "%{$search}%");
                    });
                }
            })
            ->addColumn('jangka_waktu_pemeliharaan', function ($berkas) {
                $now = now();
                $tanggalSelesai = Carbon::parse($berkas->tanggal_selesai_pememliharaan);

                if ($now->greaterThan($tanggalSelesai)) {
                    return "Pemeliharaan telah berakhir";
                }
                return $now->diffInDays($tanggalSelesai) . " hari tersisa";
            })
            ->make(true);
    }

    public function getDetail(Request $request)
    {
        $id = $request->input('nomor_kontrak');

        // Ambil data dari model BerkasPBJ
        $berkasPBJ = BerkasPBJ::where('nomor_kontrak', $id)->firstOrFail();

        // Ambil data dari model TagihanBAPP
        $tagihanBAPP = TagihanBAPP::where('nomor_kontrak', $id)->first();
        //dd($tagihanBAPP);

        // Ambil data dari model TagihanBASTP
        $tagihanBASTP = TagihanBASTP::where('nomor_kontrak', $id)->first();

        // Ambil data dari model TagihanPHO
        $tagihanPHO = TagihanPHO::where('nomor_kontrak', $id)->first();

        // Ambil data dari model TagihanFHO
        $tagihanFHO = TagihanFHO::where('nomor_kontrak', $id)->first();

        // Kirimkan data ke view
        return view('page.user.berkas_pbj.detail', compact('berkasPBJ', 'tagihanBAPP', 'tagihanBASTP', 'tagihanPHO', 'tagihanFHO'));
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->input('nomor_kontrak');
            $berkasPBJ = BerkasPBJ::where('nomor_kontrak', $id)->first();

            if (!$berkasPBJ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            // Delete related records
            TagihanBAPP::where('nomor_kontrak', $id)->delete();
            TagihanBASTP::where('nomor_kontrak', $id)->delete();
            TagihanPHO::where('nomor_kontrak', $id)->delete();
            TagihanFHO::where('nomor_kontrak', $id)->delete();

            // Delete the main record
            $berkasPBJ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tambahBerkasPBJ(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate the main BerkasPBJ data
            $validator = Validator::make($request->all(), [
                'nomor_kontrak' => 'required|unique:berkas_pbj,nomor_kontrak',
                'nama_kontrak' => 'required',
                'tanggal_kontrak_mulai' => 'required|date',
                'tanggal_kontrak_selesai' => 'required|date|after_or_equal:tanggal_kontrak_mulai',
                'nilai_kontrak_pbj' => 'required|numeric|min:0',
                'nama_vendor' => 'required',
                'tanggal_mulai_pemeliharaan' => 'nullable|date',
                'tanggal_selesai_pemeliharaan' => 'nullable|date|after_or_equal:tanggal_mulai_pemeliharaan',
                'file_path' => 'nullable|file|mimes:pdf|max:16384',
            ]);

            // Check if BAPP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bapp') || $request->filled('nomor_permohonan_bapp') ||
                $request->filled('tanggal_permohonan_bapp') || $request->filled('tanggal_bapp')
            ) {
                $bappRules = [
                    'nomor_bapp' => 'required',
                    'nomor_permohonan_bapp' => 'required',
                    'tanggal_permohonan_bapp' => 'required|date',
                    'tanggal_bapp' => 'required|date'
                ];

                $validator->addRules($bappRules);
            }

            // Check if BASTP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bastp') || $request->filled('nomor_permohonan_bastp') ||
                $request->filled('tanggal_permohonan_bastp') || $request->filled('tanggal_bastp') ||
                $request->filled('jumlah_bayar_termin_1_bastp')
            ) {
                $bastpRules = [
                    'nomor_bastp' => 'required',
                    'nomor_permohonan_bastp' => 'required',
                    'tanggal_permohonan_bastp' => 'required|date',
                    'tanggal_bastp' => 'required|date',
                    'jumlah_bayar_termin_1_bastp' => 'required|numeric|min:0',
                ];

                $validator->addRules($bastpRules);
            }

            // Check if PHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_ba_pemeriksaan_pekerjaan_pho') || $request->filled('tanggal_ba_pemeriksaan_pekerjaan_pho') ||
                $request->filled('nomor_ba_serah_terima_pho') || $request->filled('tanggal_ba_serah_terima_pho') ||
                $request->filled('nomor_bapp_pada_pho') || $request->filled('tanggal_bapp_pada_pho') || $request->filled('nomor_bastp_pada_pho') ||
                $request->filled('tanggal_bastp_pada_pho') || $request->filled('nomor_permohonan_pho_vendor') || $request->filled('tanggal_permohonan_pho_vendor')
            ) {
                $phoRules = [
                    'nomor_ba_pemeriksaan_pekerjaan_pho' => 'required',
                    'tanggal_ba_pemeriksaan_pekerjaan_pho' => 'required|date',
                    'nomor_ba_serah_terima_pho' => 'required',
                    'tanggal_ba_serah_terima_pho' => 'required|date',
                    'nomor_bapp_pada_pho' => 'required',
                    'tanggal_bapp_pada_pho' => 'required|date',
                    'nomor_bastp_pada_pho' => 'required',
                    'tanggal_bastp_pada_pho' => 'required|date',
                    'nomor_permohonan_pho_vendor' => 'required',
                    'tanggal_permohonan_pho_vendor' => 'required|date',
                ];

                $validator->addRules($phoRules);
            }

            // Check if FHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_surat_permohonan_fho_vendor') || $request->filled('tanggal_surat_permohonan_fho_vendor') ||
                $request->filled('nomor_surat_laporan_tindak_lanjut_fho') || $request->filled('tanggal_surat_laporan_tindak_lanjut_fho') ||
                $request->filled('nomor_bapp_pada_fho') || $request->filled('tanggal_bapp_pada_fho') || $request->filled('nomor_bastp_pada_fho') ||
                $request->filled('tanggal_bastp_pada_fho')
            ) {
                $fhoRules = [
                    'nomor_surat_permohonan_fho_vendor' => 'required',
                    'tanggal_surat_permohonan_fho_vendor' => 'required|date',
                    'nomor_surat_laporan_tindak_lanjut_fho' => 'required',
                    'tanggal_surat_laporan_tindak_lanjut_fho' => 'required|date',
                    'nomor_bapp_pada_fho' => 'required',
                    'tanggal_bapp_pada_fho' => 'required|date',
                    'nomor_bastp_pada_fho' => 'required',
                    'tanggal_bastp_pada_fho' => 'required|date'
                ];

                $validator->addRules($fhoRules);
            }

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();
            try {
                // Check for duplicates in other tables before proceeding

                // Check BAPP duplicates
                if ($request->filled('nomor_bapp')) {
                    $duplicateBapp = TagihanBAPP::where('nomor_bapp', $request->nomor_bapp)->first();
                    if ($duplicateBapp) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Nomor BAPP "' . $request->nomor_bapp . '" sudah digunakan pada kontrak lain.')
                            ->withInput();
                    }
                }

                // Check BASTP duplicates
                if ($request->filled('nomor_bastp')) {
                    $duplicateBastp = TagihanBASTP::where('nomor_bastp', $request->nomor_bastp)->first();
                    if ($duplicateBastp) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Nomor BASTP "' . $request->nomor_bastp . '" sudah digunakan pada kontrak lain.')
                            ->withInput();
                    }
                }

                // Check PHO duplicates
                if ($request->filled('nomor_ba_pemeriksaan_pekerjaan_pho')) {
                    $duplicatePho = TagihanPHO::where('nomor_ba_pemeriksaan_pekerjaan_pho', $request->nomor_ba_pemeriksaan_pekerjaan_pho)->first();
                    if ($duplicatePho) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Nomor BA Pemeriksaan Pekerjaan PHO "' . $request->nomor_ba_pemeriksaan_pekerjaan_pho . '" sudah digunakan pada kontrak lain.')
                            ->withInput();
                    }
                }

                // Check FHO duplicates
                if ($request->filled('nomor_surat_permohonan_fho_vendor')) {
                    $duplicateFho = TagihanFHO::where('nomor_surat_permohonan_fho_vendor', $request->nomor_surat_permohonan_fho_vendor)->first();
                    if ($duplicateFho) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', 'Nomor Surat Permohonan FHO "' . $request->nomor_surat_permohonan_fho_vendor . '" sudah digunakan pada kontrak lain.')
                            ->withInput();
                    }
                }

                // Now proceed with creating records as all checks passed
                // Create BerkasPBJ
                $berkasPBJData = [
                    'nomor_kontrak' => $request->nomor_kontrak,
                    'nama_kontrak' => $request->nama_kontrak,
                    'tanggal_kontrak_mulai' => $request->tanggal_kontrak_mulai,
                    'tanggal_kontrak_selesai' => $request->tanggal_kontrak_selesai,
                    'nilai_kontrak_pbj' => $request->nilai_kontrak_pbj,
                    'nama_vendor' => $request->nama_vendor,
                    'tanggal_mulai_pemeliharaan' => $request->tanggal_mulai_pemeliharaan,
                    'tanggal_selesai_pemeliharaan' => $request->tanggal_selesai_pemeliharaan,
                ];

                // Only store file if it exists
                if ($request->hasFile('file_path')) {
                    $file = $request->file('file_path');

                    // Ambil nama file asli yang di-upload user
                    $originalName = $file->getClientOriginalName();

                    // Simpan dengan nama asli/sanitasi ke folder files di disk public
                    $path = $file->storeAs('files', $originalName, 'public');

                    // Assign path baru ke data yang akan di-update
                    $berkasPBJData['file_path'] = $path;
                }

                BerkasPBJ::create($berkasPBJData);

                // Create BAPP if data is provided
                if ($request->filled('nomor_bapp')) {
                    TagihanBAPP::create([
                        'nomor_bapp' => $request->nomor_bapp,
                        'nomor_kontrak' => $request->nomor_kontrak,
                        'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                        'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                        'tanggal_bapp' => $request->tanggal_bapp,
                    ]);
                }

                // Create BASTP if data is provided
                if ($request->filled('nomor_bastp')) {
                    TagihanBASTP::create([
                        'nomor_bastp' => $request->nomor_bastp,
                        'nomor_kontrak' => $request->nomor_kontrak,
                        'nomor_permohonan_bastp' => $request->nomor_permohonan_bastp,
                        'tanggal_permohonan_bastp' => $request->tanggal_permohonan_bastp,
                        'tanggal_bastp' => $request->tanggal_bastp,
                        'jumlah_bayar_termin_1_bastp' => $request->jumlah_bayar_termin_1_bastp,
                    ]);
                }

                // Create PHO if data is provided
                if ($request->filled('nomor_ba_pemeriksaan_pekerjaan_pho')) {
                    TagihanPHO::create([
                        'nomor_kontrak' => $request->nomor_kontrak,
                        'nomor_ba_pemeriksaan_pekerjaan_pho' => $request->nomor_ba_pemeriksaan_pekerjaan_pho,
                        'tanggal_ba_pemeriksaan_pekerjaan_pho' => $request->tanggal_ba_pemeriksaan_pekerjaan_pho,
                        'nomor_ba_serah_terima_pho' => $request->nomor_ba_serah_terima_pho,
                        'tanggal_ba_serah_terima_pho' => $request->tanggal_ba_serah_terima_pho,
                        'nomor_bapp_pada_pho' => $request->nomor_bapp_pada_pho,
                        'tanggal_bapp_pada_pho' => $request->tanggal_bapp_pada_pho,
                        'nomor_bastp_pada_pho' => $request->nomor_bastp_pada_pho,
                        'tanggal_bastp_pada_pho' => $request->tanggal_bastp_pada_pho,
                        'nomor_permohonan_pho_vendor' => $request->nomor_permohonan_pho_vendor,
                        'tanggal_permohonan_pho_vendor' => $request->tanggal_permohonan_pho_vendor,
                    ]);
                }

                // Create FHO if data is provided
                if ($request->filled('nomor_surat_permohonan_fho_vendor')) {
                    TagihanFHO::create([
                        'nomor_kontrak' => $request->nomor_kontrak,
                        'nomor_surat_permohonan_fho_vendor' => $request->nomor_surat_permohonan_fho_vendor,
                        'tanggal_surat_permohonan_fho_vendor' => $request->tanggal_surat_permohonan_fho_vendor,
                        'nomor_surat_laporan_tindak_lanjut_fho' => $request->nomor_surat_laporan_tindak_lanjut_fho,
                        'tanggal_surat_laporan_tindak_lanjut_fho' => $request->tanggal_surat_laporan_tindak_lanjut_fho,
                        'nomor_bapp_pada_fho' => $request->nomor_bapp_pada_fho,
                        'tanggal_bapp_pada_fho' => $request->tanggal_bapp_pada_fho,
                        'nomor_bastp_pada_fho' => $request->nomor_bastp_pada_fho,
                        'tanggal_bastp_pada_fho' => $request->tanggal_bastp_pada_fho,
                    ]);
                }

                DB::commit();

                return redirect()->route('berkas_pbj.index')
                    ->with('status', 'Berkas PBJ berhasil ditambahkan');
            } catch (\Exception $e) {
                DB::rollBack();

                // Provide a more user-friendly error message for duplicate entry errors
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    // Extract the duplicated value from the error message
                    preg_match("/Duplicate entry '(.+?)' for key/", $e->getMessage(), $matches);
                    $duplicateValue = $matches[1] ?? 'unknown';

                    return redirect()->back()
                        ->with('error', 'Terjadi kesalahan: Nomor "' . $duplicateValue . '" sudah digunakan pada dokumen lain. Silakan gunakan nomor yang berbeda.')
                        ->withInput();
                }

                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return view('page.user.berkas_pbj.addBerkasPBJ');
    }

    public function update(Request $request)
    {
        if ($request->has('notification_id')) {
            auth()->user()->notifications()->where('id', $request->notification_id)->first()?->markAsRead();
        }

        $id = $request->input('nomor_kontrak');

        if ($request->isMethod('post')) {
            // Validate the main BerkasPBJ data - unique rule ignores current record
            $validator = Validator::make($request->all(), [
                'nomor_kontrak' => 'required|unique:berkas_pbj,nomor_kontrak,' . $id . ',nomor_kontrak',
                'nama_kontrak' => 'required',
                'tanggal_kontrak_mulai' => 'required|date',
                'tanggal_kontrak_selesai' => 'required|date|after_or_equal:tanggal_kontrak_mulai',
                'nilai_kontrak_pbj' => 'required|numeric|min:0',
                'nama_vendor' => 'required',
                'tanggal_mulai_pemeliharaan' => 'nullable|date',
                'tanggal_selesai_pemeliharaan' => 'nullable|date|after_or_equal:tanggal_mulai_pemeliharaan',
                'file_path' => 'nullable|file|mimes:pdf|max:16384',
            ]);

            // Check if BAPP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bapp') || $request->filled('nomor_permohonan_bapp') ||
                $request->filled('tanggal_permohonan_bapp') || $request->filled('tanggal_bapp')
            ) {
                $bappRules = [
                    'nomor_bapp' => 'required',
                    'nomor_permohonan_bapp' => 'required',
                    'tanggal_permohonan_bapp' => 'required|date',
                    'tanggal_bapp' => 'required|date',
                ];

                $validator->addRules($bappRules);
            }

            // Check if BASTP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bastp') || $request->filled('nomor_permohonan_bastp') ||
                $request->filled('tanggal_permohonan_bastp') || $request->filled('tanggal_bastp') ||
                $request->filled('jumlah_bayar_termin_1_bastp')
            ) {
                $bastpRules = [
                    'nomor_bastp' => 'required',
                    'nomor_permohonan_bastp' => 'required',
                    'tanggal_permohonan_bastp' => 'required|date',
                    'tanggal_bastp' => 'required|date',
                    'jumlah_bayar_termin_1_bastp' => 'required|numeric|min:0',
                ];

                $validator->addRules($bastpRules);
            }

            // Check if PHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_ba_pemeriksaan_pekerjaan_pho') || $request->filled('tanggal_ba_pemeriksaan_pekerjaan_pho') ||
                $request->filled('nomor_ba_serah_terima_pho') || $request->filled('tanggal_ba_serah_terima_pho') ||
                $request->filled('nomor_bapp_pada_pho') || $request->filled('tanggal_bapp_pada_pho') || $request->filled('nomor_bastp_pada_pho') ||
                $request->filled('tanggal_bastp_pada_pho') || $request->filled('nomor_permohonan_pho_vendor') || $request->filled('tanggal_permohonan_pho_vendor')
            ) {
                $phoRules = [
                    'nomor_ba_pemeriksaan_pekerjaan_pho' => 'required',
                    'tanggal_ba_pemeriksaan_pekerjaan_pho' => 'required|date',
                    'nomor_ba_serah_terima_pho' => 'required',
                    'tanggal_ba_serah_terima_pho' => 'required|date',
                    'nomor_bapp_pada_pho' => 'required',
                    'tanggal_bapp_pada_pho' => 'required|date',
                    'nomor_bastp_pada_pho' => 'required',
                    'tanggal_bastp_pada_pho' => 'required|date',
                    'nomor_permohonan_pho_vendor' => 'required',
                    'tanggal_permohonan_pho_vendor' => 'required|date',
                ];

                $validator->addRules($phoRules);
            }

            // Check if FHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_surat_permohonan_fho_vendor') || $request->filled('tanggal_surat_permohonan_fho_vendor') ||
                $request->filled('nomor_surat_laporan_tindak_lanjut_fho') || $request->filled('tanggal_surat_laporan_tindak_lanjut_fho') ||
                $request->filled('nomor_bapp_pada_fho') || $request->filled('tanggal_bapp_pada_fho') || $request->filled('nomor_bastp_pada_fho') ||
                $request->filled('tanggal_bastp_pada_fho')
            ) {
                $fhoRules = [
                    'nomor_surat_permohonan_fho_vendor' => 'required',
                    'tanggal_surat_permohonan_fho_vendor' => 'required|date',
                    'nomor_surat_laporan_tindak_lanjut_fho' => 'required',
                    'tanggal_surat_laporan_tindak_lanjut_fho' => 'required|date',
                    'nomor_bapp_pada_fho' => 'required',
                    'tanggal_bapp_pada_fho' => 'required|date',
                    'nomor_bastp_pada_fho' => 'required',
                    'tanggal_bastp_pada_fho' => 'required|date',
                ];

                $validator->addRules($fhoRules);
            }

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();
            try {

                // Handle related contract number update
                $oldNomorKontrak = $request->old_nomor_kontrak;
                $newNomorKontrak = $request->nomor_kontrak;

                // Check if the new contract number already exists (except for the current one)
                $duplicateContract = BerkasPBJ::where('nomor_kontrak', $newNomorKontrak)
                    ->where('nomor_kontrak', '!=', $oldNomorKontrak)
                    ->first();

                if ($duplicateContract) {
                    return redirect()->back()
                        ->with('error', 'Terjadi kesalahan: Nomor "' . $newNomorKontrak . '" sudah digunakan pada dokumen lain. Silakan gunakan nomor yang berbeda.')
                        ->withInput();
                }

                // Update BerkasPBJ first
                $berkasPBJ = BerkasPBJ::where('nomor_kontrak', $oldNomorKontrak)->firstOrFail();
                $berkasPBJ->update([
                    'nomor_kontrak' => $newNomorKontrak,
                    'nama_kontrak' => $request->input('nama_kontrak'),
                    'tanggal_kontrak_mulai' => $request->input('tanggal_kontrak_mulai'),
                    'tanggal_kontrak_selesai' => $request->input('tanggal_kontrak_selesai'),
                    'nilai_kontrak_pbj' => $request->input('nilai_kontrak_pbj'),
                    'nama_vendor' => $request->input('nama_vendor'),
                    'tanggal_mulai_pemeliharaan' => $request->input('tanggal_mulai_pemeliharaan', null),
                    'tanggal_selesai_pemeliharaan' => $request->input('tanggal_selesai_pemeliharaan', null),
                ]);

                // Cek jika ada file baru yang diupload
                if ($request->hasFile('file_path')) {
                    $file = $request->file('file_path');

                    // Nama file persis seperti yang di-upload user
                    $originalName = $file->getClientOriginalName();

                    // (Opsional) Hapus file lama jika ada
                    if ($berkasPBJ->file_path && Storage::disk('public')->exists($berkasPBJ->file_path)) {
                        Storage::disk('public')->delete($berkasPBJ->file_path);
                    }

                    // Simpan file baru dengan nama aslinya
                    $path = $file->storeAs('files', $originalName, 'public');

                    // Update file_path dengan path file baru
                    $berkasPBJ->file_path = $path;
                    $berkasPBJ->save();
                } else {
                    // Jika tidak ada file yang diupload, set file_path menjadi null
                    if ($berkasPBJ->file_path && Storage::disk('public')->exists($berkasPBJ->file_path)) {
                        // Hapus file lama jika ada
                        Storage::disk('public')->delete($berkasPBJ->file_path);
                    }

                    // Set file_path menjadi null jika file tidak diupload
                    $berkasPBJ->file_path = null;
                    $berkasPBJ->save();
                }


                // Update all related records if contract number changed
                if ($oldNomorKontrak !== $newNomorKontrak) {
                    // Update BAPP records
                    TagihanBAPP::where('nomor_kontrak', $oldNomorKontrak)
                        ->update(['nomor_kontrak' => $newNomorKontrak]);

                    // Update BASTP records
                    TagihanBASTP::where('nomor_kontrak', $oldNomorKontrak)
                        ->update(['nomor_kontrak' => $newNomorKontrak]);

                    // Update PHO records
                    TagihanPHO::where('nomor_kontrak', $oldNomorKontrak)
                        ->update(['nomor_kontrak' => $newNomorKontrak]);

                    // Update FHO records
                    TagihanFHO::where('nomor_kontrak', $oldNomorKontrak)
                        ->update(['nomor_kontrak' => $newNomorKontrak]);
                }

                // Check if BAPP fields are all empty
                $isBappEmpty = !$request->filled('nomor_bapp') &&
                    !$request->filled('nomor_permohonan_bapp') &&
                    !$request->filled('tanggal_permohonan_bapp') &&
                    !$request->filled('tanggal_bapp');

                // Update or create BAPP with improved duplicate handling
                if (!$isBappEmpty) {
                    $bapp = TagihanBAPP::where('nomor_kontrak', $newNomorKontrak)->first();
                    $newNomorBapp = $request->nomor_bapp;

                    // Skip duplicate check for existing BAPP numbers that belong to this contract
                    $shouldCheckDuplicate = true;
                    if ($bapp && $bapp->nomor_bapp == $newNomorBapp) {
                        $shouldCheckDuplicate = false; // Skip duplicate check if we're not changing the BAPP number
                    }

                    if ($shouldCheckDuplicate) {
                        // Only check for duplicates if we're creating a new BAPP or changing the BAPP number
                        $duplicateBapp = TagihanBAPP::where('nomor_bapp', $newNomorBapp)
                            ->where('nomor_kontrak', '!=', $newNomorKontrak)
                            ->first();

                        if ($duplicateBapp) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', 'Nomor BAPP "' . $newNomorBapp . '" sudah digunakan pada kontrak lain.')
                                ->withInput();
                        }
                    }

                    // Rest of your BAPP update/create logic
                    if ($bapp) {
                        // Update existing record
                        $bapp->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bapp' => $newNomorBapp,
                            'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                            'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                            'tanggal_bapp' => $request->tanggal_bapp,
                        ]);
                    } else {
                        // Create new record
                        TagihanBAPP::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bapp' => $newNomorBapp,
                            'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                            'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                            'tanggal_bapp' => $request->tanggal_bapp,
                        ]);
                    }
                }

                // Check if BASTP fields are all empty
                $isBastpEmpty = !$request->filled('nomor_bastp') &&
                    !$request->filled('nomor_permohonan_bastp') &&
                    !$request->filled('tanggal_permohonan_bastp') &&
                    !$request->filled('tanggal_bastp') &&
                    !$request->filled('jumlah_bayar_termin_1_bastp');

                // Update or create BASTP with better duplicate handling
                if (!$isBastpEmpty) {
                    $bastp = TagihanBASTP::where('nomor_kontrak', $newNomorKontrak)->first();
                    $newNomorBastp = $request->nomor_bastp;

                    // Skip duplicate check for existing BAPP numbers that belong to this contract
                    $shouldCheckDuplicate = true;
                    if ($bastp && $bastp->nomor_bastp == $newNomorBastp) {
                        $shouldCheckDuplicate = false; // Skip duplicate check if we're not changing the BAPP number
                    }

                    if ($shouldCheckDuplicate) {
                        // Modified duplicate check - exclude current contract when checking
                        $duplicateBastp = TagihanBASTP::where('nomor_bastp', $newNomorBastp)
                            ->where('nomor_kontrak', '!=', $newNomorKontrak) // Use new contract number here
                            ->first();

                        if ($duplicateBastp) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', 'Nomor BASTP "' . $newNomorBastp . '" sudah digunakan pada kontrak lain.')
                                ->withInput();
                        }
                    }

                    if ($bastp) {
                        // Update existing record
                        $bastp->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bastp' => $newNomorBastp,
                            'nomor_permohonan_bastp' => $request->nomor_permohonan_bastp,
                            'tanggal_permohonan_bastp' => $request->tanggal_permohonan_bastp,
                            'tanggal_bastp' => $request->tanggal_bastp,
                            'jumlah_bayar_termin_1_bastp' => $request->jumlah_bayar_termin_1_bastp,
                        ]);
                    } else {
                        // Create new record
                        TagihanBASTP::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bastp' => $newNomorBastp,
                            'nomor_permohonan_bastp' => $request->nomor_permohonan_bastp,
                            'tanggal_permohonan_bastp' => $request->tanggal_permohonan_bastp,
                            'tanggal_bastp' => $request->tanggal_bastp,
                            'jumlah_bayar_termin_1_bastp' => $request->jumlah_bayar_termin_1_bastp,
                        ]);
                    }
                } else {
                    // Delete BASTP record if all fields are empty
                    TagihanBASTP::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                // Check if PHO fields are all empty
                $isPhoEmpty = !$request->filled('nomor_ba_pemeriksaan_pekerjaan_pho') &&
                    !$request->filled('tanggal_ba_pemeriksaan_pekerjaan_pho') &&
                    !$request->filled('nomor_ba_serah_terima_pho') &&
                    !$request->filled('tanggal_ba_serah_terima_pho') &&
                    !$request->filled('nomor_bapp_pada_pho') &&
                    !$request->filled('tanggal_bapp_pada_pho') &&
                    !$request->filled('nomor_bastp_pada_pho') &&
                    !$request->filled('tanggal_bastp_pada_pho') &&
                    !$request->filled('nomor_permohonan_pho_vendor') &&
                    !$request->filled('tanggal_permohonan_pho_vendor');

                // Update or create PHO with better duplicate handling
                if (!$isPhoEmpty) {
                    $pho = TagihanPHO::where('nomor_kontrak', $newNomorKontrak)->first();
                    $newNomorBaPHO = $request->nomor_ba_pemeriksaan_pekerjaan_pho;

                    $shouldCheckDuplicate = true;
                    if ($pho && $pho->nomor_ba_pemeriksaan_pekerjaan_pho == $newNomorBaPHO) {
                        $shouldCheckDuplicate = false; // Skip duplicate check if we're not changing the BAPP number
                    }

                    if ($shouldCheckDuplicate) {
                        // Modified duplicate check - exclude current contract when checking
                        $duplicatePho = TagihanPHO::where('nomor_ba_pemeriksaan_pekerjaan_pho', $newNomorBaPHO)
                            ->where('nomor_kontrak', '!=', $newNomorKontrak) // Use new contract number here
                            ->first();

                        if ($duplicatePho) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', 'Nomor BA Pemeriksaan Pekerjaan PHO "' . $newNomorBaPHO . '" sudah digunakan pada kontrak lain.')
                                ->withInput();
                        }
                    }

                    if ($pho) {
                        // Update existing record
                        $pho->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_ba_pemeriksaan_pekerjaan_pho' => $newNomorBaPHO,
                            'tanggal_ba_pemeriksaan_pekerjaan_pho' => $request->tanggal_ba_pemeriksaan_pekerjaan_pho,
                            'nomor_ba_serah_terima_pho' => $request->nomor_ba_serah_terima_pho,
                            'tanggal_ba_serah_terima_pho' => $request->tanggal_ba_serah_terima_pho,
                            'nomor_bapp_pada_pho' => $request->nomor_bapp_pada_pho,
                            'tanggal_bapp_pada_pho' => $request->tanggal_bapp_pada_pho,
                            'nomor_bastp_pada_pho' => $request->nomor_bastp_pada_pho,
                            'tanggal_bastp_pada_pho' => $request->tanggal_bastp_pada_pho,
                            'nomor_permohonan_pho_vendor' => $request->nomor_permohonan_pho_vendor,
                            'tanggal_permohonan_pho_vendor' => $request->tanggal_permohonan_pho_vendor,
                        ]);
                    } else {
                        // Create new record
                        TagihanPHO::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_ba_pemeriksaan_pekerjaan_pho' => $newNomorBaPHO,
                            'tanggal_ba_pemeriksaan_pekerjaan_pho' => $request->tanggal_ba_pemeriksaan_pekerjaan_pho,
                            'nomor_ba_serah_terima_pho' => $request->nomor_ba_serah_terima_pho,
                            'tanggal_ba_serah_terima_pho' => $request->tanggal_ba_serah_terima_pho,
                            'nomor_bapp_pada_pho' => $request->nomor_bapp_pada_pho,
                            'tanggal_bapp_pada_pho' => $request->tanggal_bapp_pada_pho,
                            'nomor_bastp_pada_pho' => $request->nomor_bastp_pada_pho,
                            'tanggal_bastp_pada_pho' => $request->tanggal_bastp_pada_pho,
                            'nomor_permohonan_pho_vendor' => $request->nomor_permohonan_pho_vendor,
                            'tanggal_permohonan_pho_vendor' => $request->tanggal_permohonan_pho_vendor,
                        ]);
                    }
                } else {
                    // Delete PHO record if all fields are empty
                    TagihanPHO::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                // Check if FHO fields are all empty
                $isFhoEmpty = !$request->filled('nomor_surat_permohonan_fho_vendor') &&
                    !$request->filled('tanggal_surat_permohonan_fho_vendor') &&
                    !$request->filled('nomor_surat_laporan_tindak_lanjut_fho') &&
                    !$request->filled('tanggal_surat_laporan_tindak_lanjut_fho') &&
                    !$request->filled('nomor_bapp_pada_fho') &&
                    !$request->filled('tanggal_bapp_pada_fho') &&
                    !$request->filled('nomor_bastp_pada_fho') &&
                    !$request->filled('tanggal_bastp_pada_fho');

                // Update or create FHO with better duplicate handling
                if (!$isFhoEmpty) {
                    $fho = TagihanFHO::where('nomor_kontrak', $newNomorKontrak)->first();
                    $newNomorPermohonanFHO = $request->nomor_surat_permohonan_fho_vendor;

                    $shouldCheckDuplicate = true;
                    if ($pho && $pho->nomor_surat_permohonan_fho_vendor == $newNomorPermohonanFHO) {
                        $shouldCheckDuplicate = false; // Skip duplicate check if we're not changing the BAPP number
                    }

                    if ($shouldCheckDuplicate) {
                        // Modified duplicate check - exclude current contract when checking
                        $duplicateFho = TagihanFHO::where('nomor_surat_permohonan_fho_vendor', $newNomorPermohonanFHO)
                            ->where('nomor_kontrak', '!=', $newNomorKontrak) // Use new contract number here
                            ->first();

                        if ($duplicateFho) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', 'Nomor Surat Permohonan FHO "' . $newNomorPermohonanFHO . '" sudah digunakan pada kontrak lain.')
                                ->withInput();
                        }
                    }

                    if ($fho) {
                        // Update existing record
                        $fho->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_surat_permohonan_fho_vendor' => $newNomorPermohonanFHO,
                            'tanggal_surat_permohonan_fho_vendor' => $request->tanggal_surat_permohonan_fho_vendor,
                            'nomor_surat_laporan_tindak_lanjut_fho' => $request->nomor_surat_laporan_tindak_lanjut_fho,
                            'tanggal_surat_laporan_tindak_lanjut_fho' => $request->tanggal_surat_laporan_tindak_lanjut_fho,
                            'nomor_bapp_pada_fho' => $request->nomor_bapp_pada_fho,
                            'tanggal_bapp_pada_fho' => $request->tanggal_bapp_pada_fho,
                            'nomor_bastp_pada_fho' => $request->nomor_bastp_pada_fho,
                            'tanggal_bastp_pada_fho' => $request->tanggal_bastp_pada_fho,
                        ]);
                    } else {
                        // Create new record
                        TagihanFHO::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_surat_permohonan_fho_vendor' => $newNomorPermohonanFHO,
                            'tanggal_surat_permohonan_fho_vendor' => $request->tanggal_surat_permohonan_fho_vendor,
                            'nomor_surat_laporan_tindak_lanjut_fho' => $request->nomor_surat_laporan_tindak_lanjut_fho,
                            'tanggal_surat_laporan_tindak_lanjut_fho' => $request->tanggal_surat_laporan_tindak_lanjut_fho,
                            'nomor_bapp_pada_fho' => $request->nomor_bapp_pada_fho,
                            'tanggal_bapp_pada_fho' => $request->tanggal_bapp_pada_fho,
                            'nomor_bastp_pada_fho' => $request->nomor_bastp_pada_fho,
                            'tanggal_bastp_pada_fho' => $request->tanggal_bastp_pada_fho,
                        ]);
                    }
                } else {
                    // Delete FHO record if all fields are empty
                    TagihanFHO::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                DB::commit();

                return redirect()->route('berkas_pbj.index')
                    ->with('status', 'Berkas PBJ berhasil diperbarui');
            } catch (\Exception $e) {
                DB::rollBack();

                // Provide a more user-friendly error message for duplicate entry errors
                if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'PRIMARY') !== false) {
                    // Extract the duplicated value from the error message
                    preg_match("/Duplicate entry '(.+?)' for key/", $e->getMessage(), $matches);
                    $duplicateValue = $matches[1] ?? 'unknown';

                    return redirect()->back()
                        ->with('error', 'Terjadi kesalahan: Nomor "' . $duplicateValue . '" sudah digunakan pada dokumen lain. Silakan gunakan nomor yang berbeda.')
                        ->withInput();
                }

                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                    ->withInput();
            }
        }

        // GET method - display the edit form with existing data
        $berkas_pbj = BerkasPBJ::where('nomor_kontrak', $id)->firstOrFail();
        $bapp = TagihanBAPP::where('nomor_kontrak', $id)->first() ?: new TagihanBAPP(); // Initialize empty object if null
        $bastp = TagihanBASTP::where('nomor_kontrak', $id)->first() ?: new TagihanBASTP(); // Initialize empty object if null
        $pho = TagihanPHO::where('nomor_kontrak', $id)->first() ?: new TagihanPHO(); // Initialize empty object if null
        $fho = TagihanFHO::where('nomor_kontrak', $id)->first() ?: new TagihanFHO(); // Initialize empty object if null

        return view('page.user.berkas_pbj.updateBerkasPBJ', compact('berkas_pbj', 'bapp', 'bastp', 'pho', 'fho'));
    }
}
