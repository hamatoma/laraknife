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
 }
}
