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
            if (! Schema::hasColumn('seed_traceabilities', 'farm_name')) {
                $table->string('farm_name', 255)->nullable()->after('expiry_date');
            }
            if (! Schema::hasColumn('seed_traceabilities', 'soil_ph')) {
                $table->string('soil_ph', 50)->nullable()->after('farm_name');
            }
            if (! Schema::hasColumn('seed_traceabilities', 'watering_schedule')) {
                $table->string('watering_schedule', 255)->nullable()->after('soil_ph');
            }
            if (! Schema::hasColumn('seed_traceabilities', 'cultivation_guide')) {
                $table->text('cultivation_guide')->nullable()->after('watering_schedule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seed_traceabilities', function (Blueprint $table) {
            // 🔴 ដកជួរឈរទាំងនេះវិញករណី Rollback
            $columns = array_filter(['farm_name', 'soil_ph', 'watering_schedule', 'cultivation_guide'], fn ($c) => Schema::hasColumn('seed_traceabilities', $c));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
