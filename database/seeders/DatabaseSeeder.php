<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
{
    \App\Models\User::create([
        'name' => 'Administrator Utama',
        'email' => 'admin@gmail.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'admin',
    ]);
}

        // Opsional: Buat 1 Dosen dummy untuk testing
        $dosen = User::create([
            'name' => 'Dosen Contoh, S.Kom',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);
        
        \App\Models\Dosen::create([
            'user_id' => $dosen->id,
            'nip' => '123456789',
            'program_studi' => 'Teknik Informatika'
        ]);
    }
}