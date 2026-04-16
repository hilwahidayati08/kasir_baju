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
        Schema::create('stocks_request', function (Blueprint $table) {
            $table->increments('request_id');
            $table->unsignedInteger('variant_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->integer('stock');
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');
            $table->enum('pengiriman', ['Dikirim', 'Diterima'])->default('Dikirim');

            $table->foreign('branch_id')->references('branch_id')->on('branch')->onDelete('set null');
            $table->foreign('variant_id')->references('variant_id')->on('variants')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_request');
    }
};
