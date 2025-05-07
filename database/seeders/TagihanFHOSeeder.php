<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TagihanFhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tagihan_fho')->insert([
            [
                'nomor_kontrak' => '001/SPK/UNTAN/III/2024',
                'nomor_surat_permohonan_fho_vendor' => 'FHO-001',
                'tanggal_surat_permohonan_fho_vendor' => Carbon::parse('2024-04-10'),
                'nomor_surat_laporan_tindak_lanjut_fho' => 'LT-001',
                'tanggal_surat_laporan_tindak_lanjut_fho' => Carbon::parse('2024-04-20'),
                'nomor_bapp_pada_fho' => 'BAPP-001',
                'tanggal_bapp_pada_fho' => Carbon::parse('2024-02-25'),
                'nomor_bastp_pada_fho' => 'BASTP-001',
                'tanggal_bastp_pada_fho' => Carbon::parse('2024-03-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_kontrak' => '002/SPK/UNTAN/III/2024',
                'nomor_surat_permohonan_fho_vendor' => 'FHO-002',
                'tanggal_surat_permohonan_fho_vendor' => Carbon::parse('2024-05-05'),
                'nomor_surat_laporan_tindak_lanjut_fho' => 'LT-002',
                'tanggal_surat_laporan_tindak_lanjut_fho' => Carbon::parse('2024-05-15'),
                'nomor_bapp_pada_fho' => 'BAPP-002',
                'tanggal_bapp_pada_fho' => Carbon::parse('2024-02-25'),
                'nomor_bastp_pada_fho' => 'BASTP-002',
                'tanggal_bastp_pada_fho' => Carbon::parse('2024-03-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
