<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->integer('jumlah_kembali')->default(0); // Jumlah barang yang dikembalikan dalam kondisi bagus
            $table->integer('jumlah_rusak')->default(0); // Jumlah barang yang dikembalikan dalam kondisi rusak
            $table->decimal('persen_kerusakan', 5, 2)->default(0); // Persen kerusakan (0-100)
            $table->decimal('denda_kerusakan_item', 12, 2)->default(0); // Denda kerusakan untuk item ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
