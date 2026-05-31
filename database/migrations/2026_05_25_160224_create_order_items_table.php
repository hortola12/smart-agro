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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // ភ្ជាប់ទៅតារាង orders និង seeds ដើរតួជាតារាងកណ្ដាល Many-to-Many
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('seed_id')->constrained('seeds')->onDelete('cascade');
            $table->integer('quantity'); // ចំនួនថង់គ្រាប់ពូជដែលបានទិញ (int)
            $table->decimal('price', 8, 2); // តម្លៃគ្រាប់ពូជក្នុងមួយឯកតា
            $table->decimal('subtotal', 10, 2); // ទឹកប្រាក់សរុបក្នុងជួរនីមួយៗ (quantity * price)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
