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
    Schema::table('seeds', function (Blueprint $table) {
        // ថែម Column សម្រាប់រក្សាទុកបច្ចេកទេសដាំដុះ និងកម្រិត pH ដី
        $table->text('cultivation_guide')->nullable(); // ណែនាំវិធីដាំដុះ
        $table->string('soil_ph')->nullable();         // កម្រិត pH ដីដែលសមស្រប
        $table->string('watering_schedule')->nullable(); // របបស្រោចទឹក
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            //
        });
    }
};
