<?php

namespace App\Models;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menuitem extends Model
{
    use HasFactory;
    protected $table = 'menuitems';
    protected $fillable = [
        'name',
        'label',
        'icon',
        'section',
        'link'
    ];
    private function exists(int $id): bool
    {
        $rc = SProperty::find($id) != null;
        return $rc;
    }
    /**
     * Inserts a record of menuitems if it does not already exist.
     * @param string $name
     * @param string $icon the class name of the icon, see https://icons.getbootstrap.com
     * @param string $label
     * @param string $link
     */
    public static function insertIfNotExists(string $name, string $icon, string $label = null, string $link = null)
    {
        if (self::where(['name' => $name])->first() == null) {
            if ($label == null) {
                $label = StringHelper::toCapital($name);
            }
            if ($link == null) {
                $module = str_ends_with($name, 's') ? substr($name, 0, strlen($name) - 1) : $name;
                $link = "/$module-index";
            }
            DB::table('menuitems')->insert([
                'name' => $name,
                'icon' => $icon,
                'label' => $label,
                'section' => 'main',
                'link' => $link
            ]);
        }
    }
}
