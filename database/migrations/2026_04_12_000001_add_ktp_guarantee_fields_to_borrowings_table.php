<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('jaminan_tipe')->default('ktp')->after('status');
            $table->timestamp('ktp_diterima_at')->nullable()->after('jaminan_tipe');
            $table->foreignId('ktp_diterima_oleh')->nullable()->after('ktp_diterima_at')->constrained('users')->nullOnDelete();
            $table->timestamp('ktp_dikembalikan_at')->nullable()->after('ktp_diterima_oleh');
            $table->foreignId('ktp_dikembalikan_oleh')->nullable()->after('ktp_dikembalikan_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['ktp_diterima_oleh']);
            $table->dropForeign(['ktp_dikembalikan_oleh']);
            $table->dropColumn([
                'jaminan_tipe',
                'ktp_diterima_at',
                'ktp_diterima_oleh',
                'ktp_dikembalikan_at',
                'ktp_dikembalikan_oleh',
            ]);
        });
    }
};
