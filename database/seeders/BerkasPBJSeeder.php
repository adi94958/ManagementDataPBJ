<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BerkasPBJSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('berkas_pbj')->insert([
            [
                'nomor_kontrak' => '001/SPK/UNTAN/III/2024',
                'nama_kontrak' => 'Pembangunan Gedung A',
                'tanggal_kontrak_mulai' => '2023-01-15',
                'tanggal_kontrak_selesai' => '2023-06-15',
                'nilai_kontrak_pbj' => 500000000,
                'nama_vendor' => 'PT Konstruksi Maju',
            ],
            [
                'nomor_kontrak' => '002/SPK/UNTAN/III/2024',
                'nama_kontrak' => 'Renovasi Jalan Raya',
                'tanggal_kontrak_mulai' => '2023-02-01',
                'tanggal_kontrak_selesai' => '2026-07-01',
                'nilai_kontrak_pbj' => 300000000,
                'nama_vendor' => 'PT Jalan Lurus',
            ],
            [
                'nomor_kontrak' => '003/SPK/UNTAN/III/2024',
                'nama_kontrak' => 'Pemasangan Jaringan Listrik',
                'tanggal_kontrak_mulai' => '2023-03-10',
                'tanggal_kontrak_selesai' => '2023-08-10',
                'nilai_kontrak_pbj' => 150000000,
                'nama_vendor' => 'PT Cahaya Elektrik',
            ],
        ]);
    }
}
