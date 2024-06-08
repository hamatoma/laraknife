<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Module;
use App\Models\Menuitem;
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
        Menuitem::insertIfNotExists('roles', 'bi-person-check-fill');
        Module::insertIfNotExists('Role');

        Role::insertIfNotExists('Administrator', 10, 1);
        Role::insertIfNotExists('Manager', 20, 2);
        Role::insertIfNotExists('User', 50, 3);
        Role::insertIfNotExists('Guest', 99, 4);
    }
}
