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
        Schema::create('sales_items', function (Blueprint $table) {
            $table->increments('sales_items_id');
            $table->unsignedInteger('sales_id'); // Transaksi terkait
            $table->unsignedInteger('variant_id'); // Barang yang dibeli
            $table->integer('quantity'); // Jumlah barang
            $table->decimal('price', 10, 2); // Harga barang
            $table->decimal('total', 15,2);
            $table->timestamps();

            // Foreign key
            $table->foreign('sales_id')->references('sales_id')->on('sales')->onDelete('cascade');
            $table->foreign('variant_id')->references('variant_id')->on('variants')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_items');
    }
};
