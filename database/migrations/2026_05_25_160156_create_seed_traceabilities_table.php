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
        Schema::create('seed_traceabilities', function (Blueprint $table) {
            $table->id();
            // បង្កើត Foreign Key ភ្ជាប់ទៅកាន់តារាង seeds
            $table->foreignId('seed_id')->constrained('seeds')->onDelete('cascade');
            $table->string('batch_number'); // លេខកូដសម្គាល់វគ្គផលិតកម្ម (varchar)
            $table->string('origin_kh'); // ប្រភពកសិដ្ឋានជាភាសាខ្មែរ (varchar)
            $table->string('origin_en'); // ប្រភពកសិដ្ឋានជាភាសាអង់គ្លេស (varchar)
            $table->decimal('germination_rate', 5, 2); // អត្រាដុះលូតលាស់ (decimal)
            $table->date('harvest_date'); // កាលបរិច្ឆេទប្រមូលផល (date)
            $table->date('expiry_date'); // កាលបរិច្ឆេទផុតកំណត់ (date)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed_traceabilities');
    }
};
