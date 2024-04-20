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
            'shortname' => 'PUB'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1102,
            'scope' => 'filegroup',
            'name' => 'private',
            'order' => '20',
            'shortname' => 'PRIV'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1103, 'scope' => 'filegroup', 'name' => 'Audio file', 'order' => '30', 'shortname' => 'AUDIO'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1104, 'scope' => 'filegroup', 'name' => 'Video file', 'order' => '40', 'shortname' => 'VIDEO'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1105, 'scope' => 'filegroup', 'name' => 'Image file', 'order' => '50', 'shortname' => 'IMG'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1106, 'scope' => 'filegroup', 'name' => 'Document file', 'order' => '60', 'shortname' => 'DOC'
        ]);
    }
}
