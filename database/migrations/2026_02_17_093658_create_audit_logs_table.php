<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Siapa yang melakukan aksi
            $table->string('event'); // created, updated, deleted, login, logout, panic
            $table->string('auditable_type')->nullable(); // Model apa: App\Models\User
            $table->unsignedBigInteger('auditable_id')->nullable(); // ID model: 1
            $table->text('old_values')->nullable(); // Data lama (JSON)
            $table->text('new_values')->nullable(); // Data baru (JSON)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
