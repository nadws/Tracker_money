<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed kategori default UMKM
        $this->call(CategorySeeder::class);
    }
}
