<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete(); // Yang minta tukar
            $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete(); // Yang diajak tukar
            
            // Jadwal si Requester yang mau dilepas
            $table->foreignId('roster_id_from')->constrained('rosters')->cascadeOnDelete();
            
            // Jadwal si Target yang mau diambil (bisa null jika hanya memberi)
            $table->foreignId('roster_id_to')->nullable()->constrained('rosters')->cascadeOnDelete();
            
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved_by_target', 'approved_by_admin', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_exchanges');
    }
};
