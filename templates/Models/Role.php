<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'priority',
    ];
    /**
     * Inserts a record of modules if it does not already exist.
     * @param string $name
     * @param int $priority a lower values have more rights
     */
    public static function insertIfNotExists(string $name, int $priority, int $id = null)
    {
        if (self::where(['name' => $name])->first() == null) {
            $attributes = [
                'name' => $name,
                'priority' => $priority,
            ];
            if ($id != null){
                $attributes['id'] = $id;
            }
            DB::table('roles')->insert($attributes);
        }
    }
}
