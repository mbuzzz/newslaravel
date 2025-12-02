<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan Role super_admin ada
        // Filament Shield biasanya membuat ini, tapi kita buat manual jaga-jaga
        // agar seeder tidak error jika dijalankan di mesin lain.
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // 2. Buat User Super Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@pelajarmu.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'bio' => 'Administrator utama PelajarMu.com',
            ]
        );

        // 3. Assign Role ke User
        $user->assignRole($role);
    }
}