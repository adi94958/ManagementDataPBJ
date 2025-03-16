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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BerkasPBJController extends Controller
{
    public function index()
    {
        return view('page.admin.berkas_pbj.index');
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
            ->make(true);
    }

    public function getBappDetails(Request $request)
    {
        $nilaiKontrak = BerkasPBJ::where('nomor_kontrak', $request->nomor_kontrak)->value('nilai_kontrak_pbj');
        $bappDetails = TagihanBAPP::where('nomor_kontrak', $request->nomor_kontrak)
            ->select('nomor_permohonan_bapp', 'tanggal_permohonan_bapp', 'nomor_bapp', 'tanggal_bapp', 'jumlah_bayar_termin_1_bapp', 'jangka_waktu_pemeliharaan_bapp')
            ->first();

        // Menghitung saldo
        $saldo = $nilaiKontrak - $bappDetails->jumlah_bayar_termin_1_bapp;

        // Menambahkan saldo ke dalam respons
        $bappDetails->saldo = $saldo;

        return response()->json(['data' => $bappDetails]);
    }

    public function getBastpDetails(Request $request)
    {
        $nilaiKontrak = BerkasPBJ::where('nomor_kontrak', $request->nomor_kontrak)->value('nilai_kontrak_pbj');
        $bastpDetails = TagihanBASTP::where('nomor_kontrak', $request->nomor_kontrak)
            ->select('nomor_permohonan_bastp', 'tanggal_permohonan_bastp', 'nomor_bastp', 'tanggal_bastp', 'jumlah_bayar_termin_1_bastp', 'jangka_waktu_pemeliharaan_bastp')
            ->first();

        // Menghitung saldo
        $saldo = $nilaiKontrak - $bastpDetails->jumlah_bayar_termin_1_bastp;

        // Menambahkan saldo ke dalam respons
        $bastpDetails->saldo = $saldo;

        return response()->json(['data' => $bastpDetails]);
    }

    public function getPhoDetails(Request $request)
    {
        $phoDetails = TagihanPHO::where('nomor_kontrak', $request->nomor_kontrak)
            ->select(
                'nomor_kontrak',
                'nomor_ba_pemeriksaan_pekerjaan_pho',
                'tanggal_ba_pemeriksaan_pekerjaan_pho',
                'nomor_ba_serah_terima_pho',
                'tanggal_ba_serah_terima_pho',
                'created_at',
                'updated_at'
            )
            ->first();

        if (!$phoDetails) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['data' => $phoDetails]);
    }


    public function getFhoDetails(Request $request)
    {
        $fhoDetails = TagihanFHO::where('nomor_kontrak', $request->nomor_kontrak)
            ->select(
                'nomor_kontrak',
                'nomor_surat_permohonan_fho_vendor',
                'tanggal_surat_permohonan_fho_vendor',
                'nomor_surat_laporan_tindak_lanjut_fho',
                'tanggal_surat_laporan_tindak_lanjut_fho',
                'created_at',
                'updated_at'
            )
            ->first();

        if (!$fhoDetails) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['data' => $fhoDetails]);
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
            ]);

            // Check if BAPP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bapp') || $request->filled('nomor_permohonan_bapp') ||
                $request->filled('tanggal_permohonan_bapp') || $request->filled('tanggal_bapp') ||
                $request->filled('jumlah_bayar_termin_1_bapp') ||
                $request->filled('jangka_waktu_pemeliharaan_bapp')
            ) {

                $bappRules = [
                    'nomor_bapp' => 'required',
                    'nomor_permohonan_bapp' => 'required',
                    'tanggal_permohonan_bapp' => 'required|date',
                    'tanggal_bapp' => 'required|date',
                    'jumlah_bayar_termin_1_bapp' => 'required|numeric|min:0',
                    'jangka_waktu_pemeliharaan_bapp' => 'required',
                ];

                $validator->addRules($bappRules);
            }

            // Check if BASTP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bastp') || $request->filled('nomor_permohonan_bastp') ||
                $request->filled('tanggal_permohonan_bastp') || $request->filled('tanggal_bastp') ||
                $request->filled('jumlah_bayar_termin_1_bastp') ||
                $request->filled('jangka_waktu_pemeliharaan_bastp')
            ) {

                $bastpRules = [
                    'nomor_bastp' => 'required',
                    'nomor_permohonan_bastp' => 'required',
                    'tanggal_permohonan_bastp' => 'required|date',
                    'tanggal_bastp' => 'required|date',
                    'jumlah_bayar_termin_1_bastp' => 'required|numeric|min:0',
                    'jangka_waktu_pemeliharaan_bastp' => 'required',
                ];

                $validator->addRules($bastpRules);
            }

            // Check if PHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_ba_pemeriksaan_pekerjaan_pho') || $request->filled('tanggal_ba_pemeriksaan_pekerjaan_pho') ||
                $request->filled('nomor_ba_serah_terima_pho') || $request->filled('tanggal_ba_serah_terima_pho')
            ) {

                $phoRules = [
                    'nomor_ba_pemeriksaan_pekerjaan_pho' => 'required',
                    'tanggal_ba_pemeriksaan_pekerjaan_pho' => 'required|date',
                    'nomor_ba_serah_terima_pho' => 'required',
                    'tanggal_ba_serah_terima_pho' => 'required|date',
                ];

                $validator->addRules($phoRules);
            }

            // Check if FHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_surat_permohonan_fho_vendor') || $request->filled('tanggal_surat_permohonan_fho_vendor') ||
                $request->filled('nomor_surat_laporan_tindak_lanjut_fho') || $request->filled('tanggal_surat_laporan_tindak_lanjut_fho')
            ) {

                $fhoRules = [
                    'nomor_surat_permohonan_fho_vendor' => 'required',
                    'tanggal_surat_permohonan_fho_vendor' => 'required|date',
                    'nomor_surat_laporan_tindak_lanjut_fho' => 'required',
                    'tanggal_surat_laporan_tindak_lanjut_fho' => 'required|date',
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
                // Create BerkasPBJ
                BerkasPBJ::create([
                    'nomor_kontrak' => $request->nomor_kontrak,
                    'nama_kontrak' => $request->nama_kontrak,
                    'tanggal_kontrak_mulai' => $request->tanggal_kontrak_mulai,
                    'tanggal_kontrak_selesai' => $request->tanggal_kontrak_selesai,
                    'nilai_kontrak_pbj' => $request->nilai_kontrak_pbj,
                    'nama_vendor' => $request->nama_vendor,
                ]);

                // Create BAPP if data is provided
                if ($request->filled('nomor_bapp')) {
                    TagihanBAPP::create([
                        'nomor_bapp' => $request->nomor_bapp,
                        'nomor_kontrak' => $request->nomor_kontrak,
                        'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                        'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                        'tanggal_bapp' => $request->tanggal_bapp,
                        'jumlah_bayar_termin_1_bapp' => $request->jumlah_bayar_termin_1_bapp,
                        'jangka_waktu_pemeliharaan_bapp' => $request->jangka_waktu_pemeliharaan_bapp,
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
                        'jangka_waktu_pemeliharaan_bastp' => $request->jangka_waktu_pemeliharaan_bastp,
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
                    ]);
                }

                DB::commit();

                return redirect()->route('berkas_pbj.index')
                    ->with('status', 'Berkas PBJ berhasil ditambahkan');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return view('page.admin.berkas_pbj.addBerkasPBJ');
    }

    public function update(Request $request)
    {
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
            ]);

            // Check if BAPP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bapp') || $request->filled('nomor_permohonan_bapp') ||
                $request->filled('tanggal_permohonan_bapp') || $request->filled('tanggal_bapp') ||
                $request->filled('jumlah_bayar_termin_1_bapp') ||
                $request->filled('jangka_waktu_pemeliharaan_bapp')
            ) {
                $bappRules = [
                    'nomor_bapp' => 'required',
                    'nomor_permohonan_bapp' => 'required',
                    'tanggal_permohonan_bapp' => 'required|date',
                    'tanggal_bapp' => 'required|date',
                    'jumlah_bayar_termin_1_bapp' => 'required|numeric|min:0',
                    'jangka_waktu_pemeliharaan_bapp' => 'required',
                ];

                $validator->addRules($bappRules);
            }

            // Check if BASTP section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_bastp') || $request->filled('nomor_permohonan_bastp') ||
                $request->filled('tanggal_permohonan_bastp') || $request->filled('tanggal_bastp') ||
                $request->filled('jumlah_bayar_termin_1_bastp') ||
                $request->filled('jangka_waktu_pemeliharaan_bastp')
            ) {
                $bastpRules = [
                    'nomor_bastp' => 'required',
                    'nomor_permohonan_bastp' => 'required',
                    'tanggal_permohonan_bastp' => 'required|date',
                    'tanggal_bastp' => 'required|date',
                    'jumlah_bayar_termin_1_bastp' => 'required|numeric|min:0',
                    'jangka_waktu_pemeliharaan_bastp' => 'required',
                ];

                $validator->addRules($bastpRules);
            }

            // Check if PHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_ba_pemeriksaan_pekerjaan_pho') || $request->filled('tanggal_ba_pemeriksaan_pekerjaan_pho') ||
                $request->filled('nomor_ba_serah_terima_pho') || $request->filled('tanggal_ba_serah_terima_pho')
            ) {
                $phoRules = [
                    'nomor_ba_pemeriksaan_pekerjaan_pho' => 'required',
                    'tanggal_ba_pemeriksaan_pekerjaan_pho' => 'required|date',
                    'nomor_ba_serah_terima_pho' => 'required',
                    'tanggal_ba_serah_terima_pho' => 'required|date',
                ];

                $validator->addRules($phoRules);
            }

            // Check if FHO section has any filled field, if yes then all fields are required
            if (
                $request->filled('nomor_surat_permohonan_fho_vendor') || $request->filled('tanggal_surat_permohonan_fho_vendor') ||
                $request->filled('nomor_surat_laporan_tindak_lanjut_fho') || $request->filled('tanggal_surat_laporan_tindak_lanjut_fho')
            ) {
                $fhoRules = [
                    'nomor_surat_permohonan_fho_vendor' => 'required',
                    'tanggal_surat_permohonan_fho_vendor' => 'required|date',
                    'nomor_surat_laporan_tindak_lanjut_fho' => 'required',
                    'tanggal_surat_laporan_tindak_lanjut_fho' => 'required|date',
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
                // Update BerkasPBJ
                $berkasPBJ = BerkasPBJ::where('nomor_kontrak', $id)->firstOrFail();
                $berkasPBJ->update([
                    'nomor_kontrak' => $request->nomor_kontrak,
                    'nama_kontrak' => $request->nama_kontrak,
                    'tanggal_kontrak_mulai' => $request->tanggal_kontrak_mulai,
                    'tanggal_kontrak_selesai' => $request->tanggal_kontrak_selesai,
                    'nilai_kontrak_pbj' => $request->nilai_kontrak_pbj,
                    'nama_vendor' => $request->nama_vendor,
                ]);

                // Handle related contract number update
                $oldNomorKontrak = $id;
                $newNomorKontrak = $request->nomor_kontrak;
                $isContractNumberChanged = $oldNomorKontrak !== $newNomorKontrak;

                // Update or create BAPP
                if ($request->filled('nomor_bapp')) {
                    $bapp = TagihanBAPP::where('nomor_kontrak', $oldNomorKontrak)->first();

                    if ($bapp) {
                        // Update existing record
                        $bapp->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bapp' => $request->nomor_bapp,
                            'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                            'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                            'tanggal_bapp' => $request->tanggal_bapp,
                            'jumlah_bayar_termin_1_bapp' => $request->jumlah_bayar_termin_1_bapp,
                            'jangka_waktu_pemeliharaan_bapp' => $request->jangka_waktu_pemeliharaan_bapp,
                        ]);
                    } else {
                        // Create new record
                        TagihanBAPP::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bapp' => $request->nomor_bapp,
                            'nomor_permohonan_bapp' => $request->nomor_permohonan_bapp,
                            'tanggal_permohonan_bapp' => $request->tanggal_permohonan_bapp,
                            'tanggal_bapp' => $request->tanggal_bapp,
                            'jumlah_bayar_termin_1_bapp' => $request->jumlah_bayar_termin_1_bapp,
                            'jangka_waktu_pemeliharaan_bapp' => $request->jangka_waktu_pemeliharaan_bapp,
                        ]);
                    }
                } else if ($isContractNumberChanged) {
                    // Delete if contract number changed but no data provided
                    TagihanBAPP::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                // Update or create BASTP
                if ($request->filled('nomor_bastp')) {
                    $bastp = TagihanBASTP::where('nomor_kontrak', $oldNomorKontrak)->first();

                    if ($bastp) {
                        // Update existing record
                        $bastp->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bastp' => $request->nomor_bastp,
                            'nomor_permohonan_bastp' => $request->nomor_permohonan_bastp,
                            'tanggal_permohonan_bastp' => $request->tanggal_permohonan_bastp,
                            'tanggal_bastp' => $request->tanggal_bastp,
                            'jumlah_bayar_termin_1_bastp' => $request->jumlah_bayar_termin_1_bastp,
                            'jangka_waktu_pemeliharaan_bastp' => $request->jangka_waktu_pemeliharaan_bastp,
                        ]);
                    } else {
                        // Create new record
                        TagihanBASTP::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_bastp' => $request->nomor_bastp,
                            'nomor_permohonan_bastp' => $request->nomor_permohonan_bastp,
                            'tanggal_permohonan_bastp' => $request->tanggal_permohonan_bastp,
                            'tanggal_bastp' => $request->tanggal_bastp,
                            'jumlah_bayar_termin_1_bastp' => $request->jumlah_bayar_termin_1_bastp,
                            'jangka_waktu_pemeliharaan_bastp' => $request->jangka_waktu_pemeliharaan_bastp,
                        ]);
                    }
                } else if ($isContractNumberChanged) {
                    // Delete if contract number changed but no data provided
                    TagihanBASTP::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                // Update or create PHO
                if ($request->filled('nomor_ba_pemeriksaan_pekerjaan_pho')) {
                    $pho = TagihanPHO::where('nomor_kontrak', $oldNomorKontrak)->first();

                    if ($pho) {
                        // Update existing record
                        $pho->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_ba_pemeriksaan_pekerjaan_pho' => $request->nomor_ba_pemeriksaan_pekerjaan_pho,
                            'tanggal_ba_pemeriksaan_pekerjaan_pho' => $request->tanggal_ba_pemeriksaan_pekerjaan_pho,
                            'nomor_ba_serah_terima_pho' => $request->nomor_ba_serah_terima_pho,
                            'tanggal_ba_serah_terima_pho' => $request->tanggal_ba_serah_terima_pho,
                        ]);
                    } else {
                        // Create new record
                        TagihanPHO::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_ba_pemeriksaan_pekerjaan_pho' => $request->nomor_ba_pemeriksaan_pekerjaan_pho,
                            'tanggal_ba_pemeriksaan_pekerjaan_pho' => $request->tanggal_ba_pemeriksaan_pekerjaan_pho,
                            'nomor_ba_serah_terima_pho' => $request->nomor_ba_serah_terima_pho,
                            'tanggal_ba_serah_terima_pho' => $request->tanggal_ba_serah_terima_pho,
                        ]);
                    }
                } else if ($isContractNumberChanged) {
                    // Delete if contract number changed but no data provided
                    TagihanPHO::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                // Update or create FHO
                if ($request->filled('nomor_surat_permohonan_fho_vendor')) {
                    $fho = TagihanFHO::where('nomor_kontrak', $oldNomorKontrak)->first();

                    if ($fho) {
                        // Update existing record
                        $fho->update([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_surat_permohonan_fho_vendor' => $request->nomor_surat_permohonan_fho_vendor,
                            'tanggal_surat_permohonan_fho_vendor' => $request->tanggal_surat_permohonan_fho_vendor,
                            'nomor_surat_laporan_tindak_lanjut_fho' => $request->nomor_surat_laporan_tindak_lanjut_fho,
                            'tanggal_surat_laporan_tindak_lanjut_fho' => $request->tanggal_surat_laporan_tindak_lanjut_fho,
                        ]);
                    } else {
                        // Create new record
                        TagihanFHO::create([
                            'nomor_kontrak' => $newNomorKontrak,
                            'nomor_surat_permohonan_fho_vendor' => $request->nomor_surat_permohonan_fho_vendor,
                            'tanggal_surat_permohonan_fho_vendor' => $request->tanggal_surat_permohonan_fho_vendor,
                            'nomor_surat_laporan_tindak_lanjut_fho' => $request->nomor_surat_laporan_tindak_lanjut_fho,
                            'tanggal_surat_laporan_tindak_lanjut_fho' => $request->tanggal_surat_laporan_tindak_lanjut_fho,
                        ]);
                    }
                } else if ($isContractNumberChanged) {
                    // Delete if contract number changed but no data provided
                    TagihanFHO::where('nomor_kontrak', $oldNomorKontrak)->delete();
                }

                DB::commit();

                return redirect()->route('berkas_pbj.index')
                    ->with('status', 'Berkas PBJ berhasil diperbarui');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                    ->withInput();
            }
        }

        // GET method - display the edit form with existing data
        // Modify your GET method section at the end of the function:
        $berkas_pbj = BerkasPBJ::where('nomor_kontrak', $id)->firstOrFail();
        $bapp = TagihanBAPP::where('nomor_kontrak', $id)->first() ?: new TagihanBAPP(); // Initialize empty object if null
        $bastp = TagihanBASTP::where('nomor_kontrak', $id)->first() ?: new TagihanBASTP(); // Initialize empty object if null
        $pho = TagihanPHO::where('nomor_kontrak', $id)->first() ?: new TagihanPHO(); // Initialize empty object if null
        $fho = TagihanFHO::where('nomor_kontrak', $id)->first() ?: new TagihanFHO(); // Initialize empty object if null

        return view('page.admin.berkas_pbj.updateBerkasPBJ', compact('berkas_pbj', 'bapp', 'bastp', 'pho', 'fho'));
    }
}
