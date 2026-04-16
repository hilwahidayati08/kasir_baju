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
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->unsignedInteger('sales_id'); // Transaksi terkait
            $table->unsignedInteger('user_id'); // Kasir atau admin yang memproses pembayaran
            $table->decimal('amount', 10, 2); // Jumlah pembayaran
            $table->decimal('change', 15, 2)->nullable(); // Kembalian
            $table->string('payment_method'); // Metode pembayaran (misalnya: tunai, kartu)
            $table->date('payment_date'); // Tanggal pembayaran
            $table->timestamps();

            // Foreign key
            $table->foreign('sales_id')->references('sales_id')->
            on('sales')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('user')->
            onDelete('restrict');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
