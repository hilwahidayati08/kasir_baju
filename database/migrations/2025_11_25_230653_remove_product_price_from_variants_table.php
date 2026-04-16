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
    Schema::table('variants', function (Blueprint $table) {
        if (Schema::hasColumn('variants', 'product_price')) {
            $table->dropColumn('product_price');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('variants', function (Blueprint $table) {
        $table->integer('product_price')->nullable();
    });
    }
};
