<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    protected $table = 'groups';
    protected $fillable = [
        'name',
        'info',
        'members'
    ];
    /**
     * Returns the settings of a combobox for selecting one group of a given member.
     * @param NULL|string $selected the id of the selected group
     * @param NULL|string $undefinedText the text of the first entry representing "undefined": 
     *  if null: no undefined entry
     * @param NULL|int $member only groups having that member will be shown. If null the login user is used
     * @return array a list of arrays, 
     *   e.g. [ ['text' => 'users', 'value' => '24', 'active' => true], ['text' => 'admins', 'value' => '3', 'active' => false]]
     */
    public static function combobox(?string $selected, ?string $undefinedText = null, ?int $member = null): array
    {
        $selected ??= '';
        $member ??= auth()->user()->id;
        if ($undefinedText == null || $undefinedText === '') {
            $rc = [];
        } else {
            if ($undefinedText == 'all') {
                $undefinedText = __('<All>');
            } elseif ($undefinedText == '-') {
                $undefinedText = __('<Please select>');
            }
            $rc = [['text' => $undefinedText, 'value' => '', 'active' => $selected === '']];
        }
        $sql = "SELECT id, name from groups where $member like '%,$member,%' order by name";
        $recs = DB::select($sql);
        foreach ($recs as &$rec) {
            $value = strval($rec->id);
            array_push($rc, ['text' => $rec->name, 'value' => $value, 'active' => $value === $selected]);
        }
        return $rc;
    }
}
