<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SProperty::insertIfNotExists(1341, 'changetype', 'insert', 10, 'I');
        SProperty::insertIfNotExists(1342, 'changetype', 'update', 20, 'U');
        SProperty::insertIfNotExists(1343, 'changetype', 'delete', 30, 'D');
        Menuitem::insertIfNotExists('changes', 'bi bi-clock-history');
        Module::insertIfNotExists('Change');
    }
}
