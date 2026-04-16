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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name', 100);
            $table->string('user_email')->unique();
            $table->string('password');
            $table->enum('role', ['Admin', 'Pengguna'])->default('Pengguna');
            $table->unsignedInteger('branch_id')->nullable();

            $table->foreign(columns: 'branch_id')->references('branch_id')->on('branch')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
