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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude_check_in', 10, 8)->nullable()->after('status');
            $table->decimal('longitude_check_in', 11, 8)->nullable()->after('latitude_check_in');
            $table->decimal('latitude_check_out', 10, 8)->nullable()->after('selfie_check_out');
            $table->decimal('longitude_check_out', 11, 8)->nullable()->after('latitude_check_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_check_in',
                'longitude_check_in',
                'latitude_check_out',
                'longitude_check_out'
            ]);
        });
    }
};
