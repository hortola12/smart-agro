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
            // 🟢 ថែមជួរឈរ batch_number ទៅក្នុងតារាង seeds (ដាក់ឱ្យ nullable ដើម្បីកុំឱ្យប៉ះពាល់ទិន្នន័យចាស់)
            $table->string('batch_number', 100)->nullable()->after('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeds', function (Blueprint $table) {
            // 🔴 ករណី Rollback វិញ ឱ្យលុបជួរឈរនេះចេញ
            $table->dropColumn('batch_number');
        });
    }
};
