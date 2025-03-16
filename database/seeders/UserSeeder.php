<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'password' => Hash::make('12345678'), // Ganti dengan password yang diinginkan
            'role' => '1'
        ]);

        User::create([
            'name' => 'user',
            'email' => 'user1@user',
            'password' => Hash::make('12345678'), // Ganti dengan password yang diinginkan
            'role' => '2'
        ]);
    }
}
