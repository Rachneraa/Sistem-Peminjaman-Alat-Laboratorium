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
            'Perangkat Komputer' => [
                ['nama' => 'Laptop Dell Latitude', 'stok' => 10, 'harga' => 8000000, 'denda' => 50000],
                ['nama' => 'Laptop HP ProBook', 'stok' => 8, 'harga' => 7500000, 'denda' => 45000],
                ['nama' => 'Monitor LED 24 inch', 'stok' => 15, 'harga' => 2000000, 'denda' => 20000],
                ['nama' => 'Keyboard Mechanical', 'stok' => 20, 'harga' => 500000, 'denda' => 5000],
                ['nama' => 'Mouse Wireless', 'stok' => 25, 'harga' => 200000, 'denda' => 3000],
            ],
            'Peralatan Jaringan' => [
                ['nama' => 'Router Cisco', 'stok' => 5, 'harga' => 3000000, 'denda' => 30000],
                ['nama' => 'Switch 24 Port', 'stok' => 8, 'harga' => 2500000, 'denda' => 25000],
                ['nama' => 'Access Point', 'stok' => 12, 'harga' => 1500000, 'denda' => 15000],
                ['nama' => 'Kabel UTP Cat6 (Roll)', 'stok' => 20, 'harga' => 500000, 'denda' => 5000],
                ['nama' => 'Crimping Tool', 'stok' => 10, 'harga' => 300000, 'denda' => 3000],
            ],
            'Peralatan Multimedia' => [
                ['nama' => 'Proyektor Epson', 'stok' => 6, 'harga' => 5000000, 'denda' => 40000],
                ['nama' => 'Kamera DSLR Canon', 'stok' => 4, 'harga' => 12000000, 'denda' => 80000],
                ['nama' => 'Microphone Condenser', 'stok' => 8, 'harga' => 1500000, 'denda' => 15000],
                ['nama' => 'Speaker Aktif', 'stok' => 10, 'harga' => 2000000, 'denda' => 20000],
                ['nama' => 'Tripod Professional', 'stok' => 12, 'harga' => 800000, 'denda' => 8000],
            ],
            'Inventaris Umum' => [
                ['nama' => 'Meja Lipat', 'stok' => 30, 'harga' => 500000, 'denda' => 5000],
                ['nama' => 'Kursi Lipat', 'stok' => 50, 'harga' => 300000, 'denda' => 3000],
                ['nama' => 'Whiteboard Portable', 'stok' => 8, 'harga' => 1000000, 'denda' => 10000],
                ['nama' => 'Extension Cable 10m', 'stok' => 15, 'harga' => 200000, 'denda' => 2000],
                ['nama' => 'Lampu LED Portable', 'stok' => 20, 'harga' => 400000, 'denda' => 4000],
            ],
        ];

        foreach ($categories as $nama_kategori => $tools) {
            $category = Category::firstOrCreate([
                'nama_kategori' => $nama_kategori,
            ]);

            // Create tools untuk kategori ini
            foreach ($tools as $tool) {
                \App\Models\Tool::create([
                    'nama_alat' => $tool['nama'],
                    'kategori_id' => $category->id,
                    'stok' => $tool['stok'],
                    'stok_rusak' => 0,
                    'stok_perbaikan' => 0,
                    'harga_asli' => $tool['harga'],
                    'denda_per_hari' => $tool['denda'],
                    'status' => 'tersedia',
                    'kondisi' => 'baik',
                    'deskripsi' => 'Alat dalam kondisi baik dan siap digunakan untuk kegiatan praktikum atau penelitian.',
                ]);
            }
        }

        $this->command->info('✅ Seeder berhasil! Data users, categories, dan tools telah dibuat.');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Admin: admin@example.com / password');
        $this->command->info('   Petugas: petugas@example.com / password');
        $this->command->info('   Peminjam: peminjam@example.com / password');
    }
}
