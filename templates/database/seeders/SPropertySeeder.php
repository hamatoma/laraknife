<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
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
        Menuitem::insertIfNotExists('sproperties', 'bi bi-card-list');
        Module::insertIfNotExists('SProperty', 'sproperties');

        SProperty::insertIfNotExists(1001, 'status', 'active', '10', 'A');
        SProperty::insertIfNotExists(1002, 'status', 'inactive', '20', 'I');

        SProperty::insertIfNotExists(1201, 'localization', 'English (Britisch)', '10', 'en_GR');
        SProperty::insertIfNotExists(1202, 'localization', 'German (Germany)', '20', 'de_DE');
        SProperty::insertIfNotExists(1203, 'localization', 'French (France)', '30', 'fr_FR');
        SProperty::insertIfNotExists(1204, 'localization', 'Italian (Italy)', '40', 'it_IT');
    }
}
