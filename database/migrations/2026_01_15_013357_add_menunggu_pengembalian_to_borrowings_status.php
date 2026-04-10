<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Mengubah ENUM status untuk menambahkan 'menunggu_pengembalian'
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'dikembalikan', 'menunggu_pengembalian') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Kembalikan ke ENUM tanpa 'menunggu_pengembalian'
        // Update data yang menggunakan 'menunggu_pengembalian' menjadi 'disetujui' terlebih dahulu
        DB::statement("UPDATE borrowings SET status = 'disetujui' WHERE status = 'menunggu_pengembalian'");
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'dikembalikan') NOT NULL DEFAULT 'menunggu'");
    }
};
