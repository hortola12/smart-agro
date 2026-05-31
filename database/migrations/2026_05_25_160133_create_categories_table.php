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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // នេះជា Primary Key (bigint) ដែលប្រព័ន្ធបង្កើតឱ្យស្រាប់
            $table->string('name_kh'); // ឈ្មោះប្រភេទជាភាសាខ្មែរ (varchar)
            $table->string('name_en'); // ឈ្មោះប្រភេទជាភាសាអង់គ្លេស (varchar)
            $table->text('description')->nullable(); // ការពិពណ៌នា (text) អាចទុកទទេបាន
            $table->timestamps(); // បង្កើត created_at និង updated_at ដោយស្វ័យប្រវត្តិ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
