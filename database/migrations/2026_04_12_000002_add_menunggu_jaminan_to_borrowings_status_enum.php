<?php

use Illuminate\Database\Migrations\Migration;
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

        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('menunggu', 'menunggu_jaminan', 'disetujui', 'ditolak', 'dikembalikan', 'menunggu_pengembalian') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("UPDATE borrowings SET status = 'menunggu' WHERE status = 'menunggu_jaminan'");
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('menunggu', 'disetujui', 'ditolak', 'dikembalikan', 'menunggu_pengembalian') NOT NULL DEFAULT 'menunggu'");
    }
};
