<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variants', function (Blueprint $table) {

            // 1. Hapus unique di kolom 'ukuran'
            $table->dropUnique(['ukuran']); // kalau error, aku bantu cek nama index-nya

            // 2. Tambahkan unique baru gabungan
            $table->unique(['product_id', 'ukuran']);
        });
    }

    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {

            // rollback
            $table->dropUnique(['product_id', 'ukuran']);
            $table->unique('ukuran');
        });
    }
};
