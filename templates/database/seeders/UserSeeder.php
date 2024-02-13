<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'administrator@example.com',
            'password' => Hash::make('TopSecret'),
            'role_id' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Guest',
            'email' => 'administrator@example.com',
            'password' => Hash::make('TopSecret'),
            'role_id' => 4
        ]);
    }
}
