<?php

namespace App\Models;

use App\Helpers\StringHelper;
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
    /**
     * Inserts a record of modules if it does not already exist.
     * @param string $name
     * @param string $tablename
     */
    public static function insertIfNotExists(string $name, string $tablename=null)
    {
        $name = StringHelper::toCapital($name);
        if (self::where(['name' => $name])->first() == null) {
            if ($tablename == null) {
                $tablename = strtolower($name) . 's';
            }
            DB::table('modules')->insert([
                'name' => $name,
                'tablename' => $tablename,
            ]);
        }
    }
    public static function moduleOfTable(string $table): ?string {
        $rc = null;
        if ( ($item = Module::where('tablename', $table)->first()) != null){
            $rc = $item->name;
        }
        return $rc;
    }
}
