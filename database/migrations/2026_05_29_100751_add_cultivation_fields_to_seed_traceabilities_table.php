<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seed_traceabilities', function (Blueprint $table) {
            // 🟢 ចាក់ទម្លាយបន្ថែម ៤ ជួរឈរថ្មីចូលទៅក្នុងតារាង seed_traceabilities
            $table->string('farm_name', 255)->nullable()->after('expiry_date');
            $table->string('soil_ph', 50)->nullable()->after('farm_name');
            $table->string('watering_schedule', 255)->nullable()->after('soil_ph');
            $table->text('cultivation_guide')->nullable()->after('watering_schedule');
        });
    }

    public function down(): void
    {
        Schema::table('seed_traceabilities', function (Blueprint $table) {
            // 🔴 ដកជួរឈរទាំងនេះវិញករណី Rollback
            $table->dropColumn(['farm_name', 'soil_ph', 'watering_schedule', 'cultivation_guide']);
        });
    }
};
