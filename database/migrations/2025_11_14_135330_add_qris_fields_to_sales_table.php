<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->text('qris_data')->nullable()->after('status'); // Menyimpan data QRIS string
            $table->string('qris_code')->nullable()->after('qris_data'); // Kode referensi QRIS
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['qris_data', 'qris_code']);
        });
    }
};