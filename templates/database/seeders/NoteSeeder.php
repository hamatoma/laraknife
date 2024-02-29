<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::delete('DELETE FROM sproperties WHERE id>=1011 and id < 1100');
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
        DB::table('sproperties')->insert([
            'id' => 1091,
            'scope' => 'visibility',
            'name' => 'public',
            'order' => '10',
            'shortname' => 'PU'
        ]);
        DB::table('sproperties')->insert([
            'id' => 1092,
            'scope' => 'visibility',
            'name' => 'private',
            'order' => '20',
            'shortname' => 'PV'
        ]);
    }
}
