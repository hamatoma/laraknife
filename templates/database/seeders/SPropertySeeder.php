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
            'id' => 1201,
            'scope' => 'localization',
            'name' => 'English (Britisch)',
            'order' => '10',
            'shortname' => 'en_GR'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1202,
            'scope' => 'localization',
            'name' => 'German (Germany)',
            'order' => '20',
            'shortname' => 'de_DE'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1203,
            'scope' => 'localization',
            'name' => 'French (France)',
            'order' => '30',
            'shortname' => 'fr_FR'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1204,
            'scope' => 'localization',
            'name' => 'Italian (Italy)',
            'order' => '40',
            'shortname' => 'it_IT'
        ]);
 }
}
