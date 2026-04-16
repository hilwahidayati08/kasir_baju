<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branch')->insert([
            [
                'nama_cabang' => 'Cabang Jakarta',
                'alamat' => 'Jl. Merdeka No. 10',
                'kontak' => '081234567890',
                'province_id' => 31,
                'city_id' => 3171,
                'district_id' => 3171010,
                'village_id' => 3171010001,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_cabang' => 'Cabang Bandung',
                'alamat' => 'Jl. Asia Afrika No. 20',
                'kontak' => '082233445566',
                'province_id' => 32,
                'city_id' => 3273,
                'district_id' => 3273020,
                'village_id' => 3273020002,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
