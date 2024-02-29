<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';
    protected $fillable = [
        'name',
        'tablename',
    ];
    public static function tableOfModule(string $module): ?string {
        $rc = null;
        if ( ($item = Module::where('name', $module)->first()) != null){
            $rc = $item->tablename;
        }
        return $rc;
    }
    public static function idOfModule(string $module): ?string {
        $rc = null;
        if ( ($item = Module::where('name', $module)->first()) != null){
            $rc = $item->id;
        }
        return $rc;
    }
    public static function moduleOfTable(string $table): ?string {
        $rc = null;
        if ( ($item = Module::where('tablename', $table)->first()) != null){
            $rc = $item->name;
        }
        return $rc;
    }
}
