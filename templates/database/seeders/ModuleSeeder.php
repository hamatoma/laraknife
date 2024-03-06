<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('modules')->insert([
            'name' => 'SProperty',
            'tablename' => 'sproperties'
        ]);
        DB::table('modules')->insert([
            'name' => 'Role',
            'tablename' => 'roles'
        ]);
        DB::table('modules')->insert([
            'name' => 'User',
            'tablename' => 'users'
        ]);
        DB::table('modules')->insert([
            'name' => 'Note',
            'tablename' => 'notes'
        ]);
        DB::table('modules')->insert([
            'name' => 'Menuitem',
            'tablename' => 'menuitems'
        ]);
        DB::table('modules')->insert([
            'name' => 'File',
            'tablename' => 'files'
        ]);
        DB::table('modules')->insert([
            'name' => 'Module',
            'tablename' => 'modules'
        ]);
    }
}
