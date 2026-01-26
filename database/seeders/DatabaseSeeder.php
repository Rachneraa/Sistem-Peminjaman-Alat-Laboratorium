<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Membuat user default dan kategori standar
     */
    public function run(): void
    {
        // Create default users untuk testing
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        User::create([
            'name' => 'Peminjam',
            'email' => 'peminjam@example.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        // Create kategori standar
        $categories = [
            'Perangkat Komputer',
            'Peralatan Jaringan',
            'Peralatan Multimedia',
            'Inventaris Umum',
        ];

        foreach ($categories as $nama_kategori) {
            Category::firstOrCreate([
                'nama_kategori' => $nama_kategori,
            ]);
        }
    }
}
