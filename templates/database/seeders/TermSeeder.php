<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Module;
use App\Models\Menuitem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('terms', 'bi bi-calendar-date');
        Module::insertIfNotExists('Term');
    }
}
