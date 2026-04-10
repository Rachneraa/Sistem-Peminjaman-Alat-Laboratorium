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
        Schema::table('returns', function (Blueprint $table) {
            $table->decimal('denda_keterlambatan_awal', 10, 2)->default(0)->after('denda');
            $table->boolean('denda_diabaikan')->default(false)->after('denda_kerusakan');
            $table->text('alasan_abaikan_denda')->nullable()->after('denda_diabaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn([
                'denda_keterlambatan_awal',
                'denda_diabaikan',
                'alasan_abaikan_denda',
            ]);
        });
    }
};
