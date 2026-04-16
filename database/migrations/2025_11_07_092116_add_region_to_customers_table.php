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
    Schema::table('customers', function (Blueprint $table) {
        $table->unsignedBigInteger('province_id')->nullable()->after('address');
        $table->unsignedBigInteger('city_id')->nullable()->after('province_id');
        $table->unsignedBigInteger('district_id')->nullable()->after('city_id');
        $table->unsignedBigInteger('village_id')->nullable()->after('district_id');
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn(['province_id', 'city_id', 'district_id', 'village_id']);
    });
}
};
