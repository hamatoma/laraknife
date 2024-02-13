<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sproperties')->insert([
            'id' => 1001,
            'scope' => 'status',
            'name' => 'active',
            'order' => '10',
            'shortname' => 'A'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1002,
            'scope' => 'status',
            'name' => 'inactive',
            'order' => '20',
            'shortname' => 'I'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1011,
            'scope' => 'notestatus',
            'name' => 'open',
            'order' => '10',
            'shortname' => 'O'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1012,
            'scope' => 'notestatus',
            'name' => 'closed',
            'order' => '20',
            'shortname' => 'C'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1051,
            'scope' => 'category',
            'name' => 'standard',
            'order' => '10',
            'shortname' => 'Std'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1052,
            'scope' => 'category',
            'name' => 'private',
            'order' => '20',
            'shortname' => 'P'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1053,
            'scope' => 'category',
            'name' => 'work',
            'order' => '30',
            'shortname' => 'W'
        ]);
 }
}
