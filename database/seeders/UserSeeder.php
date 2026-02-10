<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Admin',
            'kelas'     => null,
            'jurusan'   => null,
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
        ]);

        User::create([
            'name'      => 'Petugas Perpus',
            'kelas'     => null,
            'jurusan'   => null,
            'email'     => 'petugas@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'petugas',
        ]);

        User::create([
            'name'      => 'Siswa',
            'kelas'     => '12',      
            'jurusan'   => 'PPLG', 
            'email'     => 'siswa@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'siswa',
        ]);
    }
}