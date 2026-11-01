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
            if (! Schema::hasColumn('seeds', 'cultivation_guide')) {
                $table->text('cultivation_guide')->nullable()->after('batch_number');  // វិធីសាប
            }
            if (! Schema::hasColumn('seeds', 'watering_schedule')) {
                $table->string('watering_schedule', 255)->nullable()->after('cultivation_guide'); // របបទឹក
            }
            if (! Schema::hasColumn('seeds', 'soil_ph')) {
                $table->string('soil_ph', 50)->nullable()->after('watering_schedule'); // pH ដី
            }
            if (! Schema::hasColumn('seeds', 'harvest_date')) {
                $table->string('harvest_date', 100)->nullable()->after('soil_ph'); // ថ្ងៃប្រមូលផល
            }
            if (! Schema::hasColumn('seeds', 'farm_name')) {
                $table->string('farm_name', 255)->nullable()->after('harvest_date'); // ឈ្មោះកសិដ្ឋាន
            }
        });
    }

    public function down(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            // 🔴 លុប Column ទាំងនេះវិញករណី Rollback
            $columns = array_filter(['cultivation_guide', 'watering_schedule', 'soil_ph', 'harvest_date', 'farm_name'], fn ($c) => Schema::hasColumn('seeds', $c));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
