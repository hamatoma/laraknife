<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::insertIfNotExists('Menuitem');
        Menuitem::insertIfNotExists('menuitems', 'bi bi-menu-up', 'Start Menu');
        Menuitem::insertIfNotExists('startpage', 'bi bi-menu-up', 'Article', '/page-showmenu/main');
        Menuitem::buildMinimalMenu();
    }
}
