<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        SProperty::insertIfNotExists(1143, 'pagetype', 'info', 30, 'I');
        SProperty::insertIfNotExists(1144, 'pagetype', 'wiki', 40, 'W');

        DB::table('pages')->insert([
            'title' => 'Startmenu',
            'name' => 'main',
            'contents' => "== Menu ==\n---- %col%\n== Hilfe ==\n: [[/page-showhelp/wikisyntax|Wiki-Syntax]]<br>\n:: [[/page-showhelp/wiki-absaetze|Wiki-Absätze]]<br>\n:: [[/page-showpretty/15|Wiki-Tabelle]]",
            'pagetype_scope' => 1141,
            'markup_scope' => 1122,
            'order' => 1,
            'language_scope' => 1202,
            'owner_id' => 1,
        ]);
    }
}

