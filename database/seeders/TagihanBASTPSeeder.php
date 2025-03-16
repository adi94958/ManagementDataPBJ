<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TagihanBASTPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nomor_bastp' => 'BASTP/2024/001',
                'nomor_kontrak' => '001/SPK/UNTAN/III/2024',
                'nomor_permohonan_bastp' => 'PERM/BASTP/2024/001',
                'tanggal_permohonan_bastp' => '2023-02-15',
                'tanggal_bastp' => '2023-02-20',
                'jumlah_bayar_termin_1_bastp' => 45000000, // 30% dari nilai kontrak
                'jangka_waktu_pemeliharaan_bastp' => '30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_bastp' => 'BASTP/2024/002',
                'nomor_kontrak' => '002/SPK/UNTAN/III/2024',
                'nomor_permohonan_bastp' => 'PERM/BASTP/2024/002',
                'tanggal_permohonan_bastp' => '2024-02-16',
                'tanggal_bastp' => '2024-02-21',
                'jumlah_bayar_termin_1_bastp' => 60000000, // 30% dari nilai kontrak
                'jangka_waktu_pemeliharaan_bastp' => '40',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_bastp' => 'BASTP/2024/003',
                'nomor_kontrak' => '003/SPK/UNTAN/III/2024',
                'nomor_permohonan_bastp' => 'PERM/BASTP/2024/003',
                'tanggal_permohonan_bastp' => '2024-02-17',
                'tanggal_bastp' => '2024-02-22',
                'jumlah_bayar_termin_1_bastp' => 90000000, // 30% dari nilai kontrak
                'jangka_waktu_pemeliharaan_bastp' => '50',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel tagihan_bapp
        DB::table('tagihan_bastp')->insert($data);
    }
}
