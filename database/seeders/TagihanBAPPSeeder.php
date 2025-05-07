<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagihanBAPPSeeder extends Seeder
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
                'nomor_bapp' => 'BAPP/2024/001',
                'nomor_kontrak' => '001/SPK/UNTAN/III/2024',
                'nomor_permohonan_bapp' => 'PERM/BAPP/2024/001',
                'tanggal_permohonan_bapp' => '2024-01-15',
                'tanggal_bapp' => '2024-01-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_bapp' => 'BAPP/2024/002',
                'nomor_kontrak' => '002/SPK/UNTAN/III/2024',
                'nomor_permohonan_bapp' => 'PERM/BAPP/2024/002',
                'tanggal_permohonan_bapp' => '2024-01-16',
                'tanggal_bapp' => '2024-01-21',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_bapp' => 'BAPP/2024/003',
                'nomor_kontrak' => '003/SPK/UNTAN/III/2024',
                'nomor_permohonan_bapp' => 'PERM/BAPP/2024/003',
                'tanggal_permohonan_bapp' => '2024-01-17',
                'tanggal_bapp' => '2024-01-22',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data ke tabel tagihan_bapp
        DB::table('tagihan_bapp')->insert($data);
    }
}
