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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->foreignId('kategori_id')->constrained('categories')->onDelete('cascade');
            $table->integer('stok')->default(0);
            $table->enum('kondisi', ['baik', 'rusak', 'perlu_perbaikan'])->default('baik');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};





