<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sproperties')->insert([
            'id' => 1101,
            'scope' => 'filegroup',
            'name' => 'public',
            'order' => '10',
            'shortname' => 'P'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1102,
            'scope' => 'filegroup',
            'name' => 'private',
            'order' => '20',
            'shortname' => 'P'
        ]);
    }
}
