<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MandatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('mandators', 'bi bi-safe');
        Module::insertIfNotExists('Mandator');
    }
}
