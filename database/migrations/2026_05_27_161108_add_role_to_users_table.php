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
        Schema::table('users', function (Blueprint $col) {
            // បន្ថែម Column 'role' ដោយកំណត់តម្លៃលំនាំដើម (Default) ជា 'customer'
            // និងដាក់ឱ្យវាស្ថិតនៅខាងក្រោយ Column 'password' ឱ្យមានរបៀប
            $col->string('role')->default('customer')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $col) {
            // លុប Column 'role' វិញប្រសិនបើទាញការងារថយក្រោយ (Rollback)
            $col->dropColumn('role');
        });
    }
};
