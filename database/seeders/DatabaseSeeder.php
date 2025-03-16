<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BerkasPBJSeeder::class);
        $this->call(TagihanBAPPSeeder::class);
        $this->call(TagihanBASTPSeeder::class);
        $this->call(TagihanFHOSeeder::class);
        $this->call(TagihanPHOSeeder::class);
    }
}
