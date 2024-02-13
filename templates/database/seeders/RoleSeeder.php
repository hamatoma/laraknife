<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'priority' => '10'
        ]);
        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Manager',
            'priority' => '20'
        ]);
        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'User',
            'priority' => '20'
        ]);
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'Guest',
            'priority' => '90'
        ]);
    }
}
