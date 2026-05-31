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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // លេខសម្គាល់អ្នកប្រើប្រាស់ (អាចទុកទទេបានបើមិនទាន់ Login)
            $table->string('customer_name'); // ឈ្មោះអ្នកទិញ
            $table->string('customer_phone'); // លេខទូរស័ព្ទ
            $table->text('delivery_address'); // អាសយដ្ឋានដឹកជញ្ជូន (text)
            // $table->decimal('total_amount', 10, 2); // ទឹកប្រាក់សរុបរួម
            $table->decimal('total_price', 8, 2); // សម្រាប់ផ្ទុកទឹកប្រាក់សរុបនៃការកម្ម៉ង់
            $table->string('status')->default('pending'); // ស្ថានភាពនៃការកម្ម៉ង់ (លំនាំដើមគឺ pending)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
