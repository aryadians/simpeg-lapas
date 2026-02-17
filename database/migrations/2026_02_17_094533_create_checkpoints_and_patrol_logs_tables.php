<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lokasi: Blok A, Menara 1
            $table->string('location_code')->unique(); // Kode unik untuk QR: BLOK-A-001
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('patrol_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checkpoint_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable(); // Opsional: Foto bukti patroli
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_logs');
        Schema::dropIfExists('checkpoints');
    }
};
