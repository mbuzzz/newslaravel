<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan UserSeeder DULUAN (Penting untuk Author)
        $this->call(UserSeeder::class);

        // 2. Jalankan PostSeeder (Artikel, Kategori, Tag)
        $this->call(PostSeeder::class);

        // 3. Halaman statis
        $this->call(PageSeeder::class);
    }
}
