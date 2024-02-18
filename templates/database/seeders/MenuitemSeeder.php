<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MenuitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menuitems')->insert([
            'name' => 'menuitems',
            'label' => 'Start Menu',
            'icon' => 'bi bi-menu-up',
            'section' => 'main',
            'link' => '/menuitem-index'
        ]);
        DB::table('menuitems')->insert([
            'name' => 'users',
            'label' => 'Users',
            'icon' => 'bi bi-people-fill',
            'section' => 'main',
            'link' => '/user-index'
        ]);
        DB::table('menuitems')->insert([
            'name' => 'roles',
            'label' => 'Roles',
            'icon' => 'bi bi-person-check-fill',
            'section' => 'main',
            'link' => '/role-index'
        ]);
        DB::table('menuitems')->insert([
            'name' => 'sproperties',
            'label' => 'Properties',
            'icon' => 'bi bi-card-list',
            'section' => 'main',
            'link' => '/sproperty-index'
        ]);
        DB::table('menuitems')->insert([
            'name' => 'notes',
            'label' => 'Notes',
            'icon' => 'bi bi-card-checklist',
            'section' => 'main',
            'link' => '/note-index'
        ]);
        DB::table('menuitems')->insert([
            'name' => 'files',
            'label' => 'Files',
            'icon' => 'bi bi-file-pdf',
            'section' => 'main',
            'link' => '/file-index'
        ]);
        for ($no = 1; $no <= 6; $no++){
            DB::table('menuitems_roles')->insert([
                'order' => '10',
                'menuitem_id' => strval($no),
                'role_id' => '1'
            ]);
        }
     }
}
