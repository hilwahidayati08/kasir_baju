<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'user_name' => 'Admin Utama',
                'user_email' => 'pusat@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Admin',
                'branch_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Kasir Jakarta',
                'user_email' => 'kasir.jkt@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Cashier',
                'branch_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Cabang Jakarta',
                'user_email' => 'cabang.jkt@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Cabang',
                'branch_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Kasir Bandung',
                'user_email' => 'kasir.bdg@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Cashier',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_name' => 'Cabang Bandung',
                'user_email' => 'cabang.bdg@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'Cabang',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
