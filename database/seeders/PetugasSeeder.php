<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\petugas;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        // Only create admin if not exists
        if (!petugas::where('email', 'admin@gmail.com')->exists()) {
            petugas::create([
                'nama' => 'Atma Admin',
                'email' => 'admin@gmail.com',
                'kata_sandi' => Hash::make('admin12345'),
                'jabatan' => 'Administrator',
                'role' => 'admin',
                'tgl_bergabung' => now()->toDateString(),
            ]);
        }
    }
}
