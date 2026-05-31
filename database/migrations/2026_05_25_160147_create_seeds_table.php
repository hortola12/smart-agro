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
        Schema::create('seeds', function (Blueprint $table) {
            $table->id();
            // បង្កើត Foreign Key ភ្ជាប់ទៅកាន់តារាង categories (bigint)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name_kh');
            $table->string('name_en');
            $table->text('description_kh')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 8, 2); // តម្លៃគ្រាប់ពូជ (decimal)
            $table->integer('stock'); // ចំនួនក្នុងស្តុក (int)
            $table->string('image')->nullable(); // ឈ្មោះរូបភាព (varchar)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seeds');
    }
};
