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
    Schema::create('rosters', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel Users
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        // Relasi ke tabel Shifts
        $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
        $table->date('date'); // Tanggal jadwal (2026-01-20)
        
        // Opsional: Untuk mencatat apakah jadwal ini diubah manual?
        $table->text('notes')->nullable(); 
        
        // Mencegah duplikasi: 1 Pegawai tidak boleh punya 2 shift di tanggal yang sama
        $table->unique(['user_id', 'date']); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rosters');
    }
};
