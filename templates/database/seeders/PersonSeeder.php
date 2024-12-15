<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SProperty::insertIfNotExists(1161, 'gender', 'female', 10, 'F');
        SProperty::insertIfNotExists(1162, 'gender', 'male', 20, 'M');
        SProperty::insertIfNotExists(1163, 'gender', 'diverse', 30, 'D');
        SProperty::insertIfNotExists(1171, 'persongroup', 'other', 10, 'O');
        SProperty::insertIfNotExists(1172, 'persongroup', 'user', 20, 'U');
        SProperty::insertIfNotExists(1173, 'persongroup', 'member', 30, 'M');
        SProperty::insertIfNotExists(1174, 'persongroup', 'head office', 40, 'H');
        SProperty::insertIfNotExists(1175, 'persongroup', 'press', 50, 'P');
        SProperty::insertIfNotExists(2011, 'persongroup', 'core', 60, 'C');
        SProperty::insertIfNotExists(2012, 'persongroup', 'Z', 70, 'F');
        Menuitem::insertIfNotExists('persons', 'bi bi-person-circle');
        Module::insertIfNotExists('Person');
    }
}
