<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SProperty::insertIfNotExists(1321, 'addresstype', 'Email', 10, 'E');
        SProperty::insertIfNotExists(1322, 'addresstype', 'Phone', 20, 'P');
        Menuitem::insertIfNotExists('addresses', 'bi bi-at');
        Module::insertIfNotExists('Address');    }
}
