<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Spatie\Permission\Models\Role;

    class ReaderRoleSeeder extends Seeder
    {
        public function run(): void
        {
            // Buat Role Reader jika belum ada
            Role::firstOrCreate(['name' => 'reader', 'guard_name' => 'web']);
            
            // Buat Role Author (Penulis) juga sekalian
            Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);
        }
    }