<?php

namespace Database\Seeders;

use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // int $id, string $scope, string $name, int $order, string $shortname
        SProperty::insertIfNotExists(1011, 'notestatus', 'open', '10', 'O');
        SProperty::insertIfNotExists( 1012, 'notestatus', 'closed', '20', 'C' );

        SProperty::insertIfNotExists( 1051, 'category', 'standard', '10', 'Std' );
        SProperty::insertIfNotExists( 1052, 'category', 'private', '20', 'P' );
        SProperty::insertIfNotExists( 1053, 'category', 'work', '30', 'W' );
        SProperty::insertIfNotExists( 1054, 'category', 'task', '40', 'T' );
        
        SProperty::insertIfNotExists( 1091, 'visibility', 'public', '10', 'PU' );
        SProperty::insertIfNotExists( 1092, 'visibility', 'private', '20', 'PV' );

        SProperty::insertIfNotExists(1301, 'task', 'description', '10', 'D');
        SProperty::insertIfNotExists(1302, 'task', 'snaketext', '20', 'S');
        SProperty::insertIfNotExists(1303, 'task', 'cloze', '30', 'C');
    }
}
