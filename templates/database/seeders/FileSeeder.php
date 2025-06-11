<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('files', 'bi bi-file-pdf');
        Module::insertIfNotExists('File');

        SProperty::insertIfNotExists(1101, 'filegroup', 'public', '10', 'PUB');
        SProperty::insertIfNotExists(1102, 'filegroup', 'private', '20', 'PRIV');
        SProperty::insertIfNotExists(1103, 'filegroup', 'audio file', '30', 'AUDIO');
        SProperty::insertIfNotExists(1104, 'filegroup', 'video file', '40', 'VIDEO');
        SProperty::insertIfNotExists(1105, 'filegroup', 'image file', '50', 'IMG');
        SProperty::insertIfNotExists(1106, 'filegroup', 'document file', '60', 'IMG');
    }
}
