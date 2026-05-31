<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            // 🟢 ថែម Column ថ្មីៗសម្រាប់រក្សាទុកព័ត៌មានដាំដុះ និងប្រភពដើម
            $table->text('cultivation_guide')->nullable()->after('batch_number');  // វិធីសាប
            $table->string('watering_schedule', 255)->nullable()->after('cultivation_guide'); // របបទឹក
            $table->string('soil_ph', 50)->nullable()->after('watering_schedule'); // pH ដី
            $table->string('harvest_date', 100)->nullable()->after('soil_ph'); // ថ្ងៃប្រមូលផល
            $table->string('farm_name', 255)->nullable()->after('harvest_date'); // ឈ្មោះកសិដ្ឋាន
        });
    }

    public function down(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            // 🔴 លុប Column ទាំងនេះវិញករណី Rollback
            $table->dropColumn(['cultivation_guide', 'watering_schedule', 'soil_ph', 'harvest_date', 'farm_name']);
        });
    }
};
