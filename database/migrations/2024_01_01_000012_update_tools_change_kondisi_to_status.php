<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom status baru
        Schema::table('tools', function (Blueprint $table) {
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak', 'perbaikan'])->default('tersedia')->after('stok');
        });

        // Migrate data dari kondisi ke status
        DB::statement("UPDATE tools SET status = CASE 
            WHEN kondisi = 'baik' AND stok > 0 THEN 'tersedia'
            WHEN kondisi = 'baik' AND stok = 0 THEN 'dipinjam'
            WHEN kondisi = 'rusak' THEN 'rusak'
            WHEN kondisi = 'perlu_perbaikan' THEN 'perbaikan'
            ELSE 'tersedia'
        END");

        // Hapus kolom kondisi (optional, kita bisa keep untuk kompatibilitas)
        // Schema::table('tools', function (Blueprint $table) {
        //     $table->dropColumn('kondisi');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};





