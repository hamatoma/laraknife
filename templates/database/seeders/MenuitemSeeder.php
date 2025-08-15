<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
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
        SProperty::insertIfNotExists(1431, 'menusection', 'main', '10', 'M');
        SProperty::insertIfNotExists(1432, 'menusection', 'presentation', '20', 'PR');
        SProperty::insertIfNotExists(1433, 'menusection', 'public', '30', 'PU');
    }
}
