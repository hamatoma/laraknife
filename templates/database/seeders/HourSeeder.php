<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SProperty::insertIfNotExists(1351, 'hourtype', 'standard', 10, 'S');
        SProperty::insertIfNotExists(1361, 'hourstate', 'entered', 10, 'E');
        SProperty::insertIfNotExists(1362, 'hourstate', 'closed', 20, 'C');
        SProperty::insertIfNotExists(1363, 'hourstate', 'accounted', 30, 'A');
        Menuitem::insertIfNotExists('hours', 'bi bi-clock', 'Time recording');
        Module::insertIfNotExists('Hours');
    }
}
