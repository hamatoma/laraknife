<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('pages', 'bi bi-journals');
        Module::insertIfNotExists('Page');
        SProperty::insertIfNotExists(1121, 'markup', 'plain text', 10, 'PT');
        SProperty::insertIfNotExists(1122, 'markup', 'mediawiki', 20, 'MW');
        SProperty::insertIfNotExists(1123, 'markup', 'HTML', 30, 'HL');

        SProperty::insertIfNotExists(1141, 'pagetype', 'menu', 10, 'MN');
        SProperty::insertIfNotExists(1142, 'pagetype', 'help', 20, 'HP');
    }
}

