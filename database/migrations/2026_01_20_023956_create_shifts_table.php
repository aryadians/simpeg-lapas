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
    Schema::create('shifts', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: "Pagi", "Malam", "Staff"
        $table->time('start_time'); // 07:00
        $table->time('end_time');   // 13:00
        // Kolom krusial untuk Shift Malam (19.00 - 07.00 esoknya)
        $table->boolean('is_overnight')->default(false); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
