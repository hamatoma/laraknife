<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('locations', 'bi bi-mailbox');
        Module::insertIfNotExists('Location');
    }
}
