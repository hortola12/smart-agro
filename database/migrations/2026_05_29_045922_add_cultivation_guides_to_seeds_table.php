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
            if (! Schema::hasColumn('seeds', 'cultivation_guide')) {
                $table->text('cultivation_guide')->nullable(); // ណែនាំវិធីដាំដុះ
            }
            if (! Schema::hasColumn('seeds', 'soil_ph')) {
                $table->string('soil_ph')->nullable();         // កម្រិត pH ដីដែលសមស្រប
            }
            if (! Schema::hasColumn('seeds', 'watering_schedule')) {
                $table->string('watering_schedule')->nullable(); // របបស្រោចទឹក
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            $columns = array_filter(['cultivation_guide', 'soil_ph', 'watering_schedule'], fn ($c) => Schema::hasColumn('seeds', $c));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
