<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TagihanPhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tagihan_pho')->insert([
            [
                'nomor_kontrak' => '001/SPK/UNTAN/III/2024',
                'nomor_ba_pemeriksaan_pekerjaan_pho' => 'BA-001',
                'tanggal_ba_pemeriksaan_pekerjaan_pho' => Carbon::parse('2024-02-15'),
                'nomor_ba_serah_terima_pho' => 'BA-ST-001',
                'tanggal_ba_serah_terima_pho' => Carbon::parse('2024-02-20'),
                'nomor_bapp_pada_pho' => 'BAPP-001',
                'tanggal_bapp_pada_pho' => Carbon::parse('2024-02-25'),
                'nomor_bastp_pada_pho' => 'BASTP-001',
                'tanggal_bastp_pada_pho' => Carbon::parse('2024-03-01'),
                'nomor_permohonan_pho_vendor' => 'VN-01',
                'tanggal_permohonan_pho_vendor' => Carbon::parse('2024-03-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_kontrak' => '002/SPK/UNTAN/III/2024',
                'nomor_ba_pemeriksaan_pekerjaan_pho' => 'BA-002',
                'tanggal_ba_pemeriksaan_pekerjaan_pho' => Carbon::parse('2024-03-10'),
                'nomor_ba_serah_terima_pho' => 'BA-ST-002',
                'tanggal_ba_serah_terima_pho' => Carbon::parse('2024-03-15'),
                'nomor_bapp_pada_pho' => 'BAPP-002',
                'tanggal_bapp_pada_pho' => Carbon::parse('2024-02-25'),
                'nomor_bastp_pada_pho' => 'BASTP-002',
                'tanggal_bastp_pada_pho' => Carbon::parse('2024-03-01'),
                'nomor_permohonan_pho_vendor' => 'VN-02',
                'tanggal_permohonan_pho_vendor' => Carbon::parse('2024-03-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
