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
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siapa yang lapor
            $table->date('date'); // Tanggal lapor
            $table->string('shift_name'); // Shift apa (Pagi/Siang/Malam)
            $table->integer('wbp_count'); // Jumlah Napi
            $table->text('description'); // Laporan situasi (Aman/Kondusif)
            $table->boolean('is_urgent')->default(false); // Ada insiden gawat?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
