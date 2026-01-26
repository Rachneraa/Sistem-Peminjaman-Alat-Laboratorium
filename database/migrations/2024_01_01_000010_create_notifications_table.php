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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tipe'); // 'peminjaman_disetujui', 'peminjaman_ditolak', 'jatuh_tempo'
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('status_baca')->default(false);
            $table->unsignedBigInteger('related_id')->nullable(); // ID peminjaman atau yang terkait
            $table->string('related_type')->nullable(); // Model type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};





