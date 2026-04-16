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
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sales_id');
            $table->unsignedInteger('user_id'); // Kasir atau admin
            $table->unsignedInteger('branch_id'); // Kasir atau admin
            $table->unsignedInteger('customer_id')->nullable(); // Pelanggan (optional, bisa null)
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2); // Total jumlah transaksi
            $table->string('status'); // Status transaksi (misalnya: completed, pending)
            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')->references('user_id')->on('user')
            ->onDelete('restrict');
            $table->foreign('branch_id')->references('branch_id')->on('branch')
            ->onDelete('restrict');
            $table->foreign('customer_id')->references('customer_id')->on('customers')
            ->onDelete('set null');        
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
