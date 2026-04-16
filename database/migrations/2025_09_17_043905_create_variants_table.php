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
        Schema::create('variants', function (Blueprint $table) {
            $table->increments('variant_id');
            $table->unsignedInteger('product_id');
            $table->string('warna'); // wajib diisi dan tidak boleh duplikat
            $table->enum('ukuran', ['M', 'L', 'XL', 'XXL'])->default('L')->unique();
            $table->decimal('product_price', 10,2);
            $table->decimal('product_sale', 10,2);
            $table->string('barcode')->unique(); // wajib diisi dan tidak boleh duplikat
            $table->string('photo')->nullable(); // Kolom untuk menyimpan nama file foto

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
