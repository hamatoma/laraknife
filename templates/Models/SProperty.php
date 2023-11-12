<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SProperty extends Model
{
    use HasFactory;
    protected $table = 'sproperties';
    // editable fields:
    protected static $fields = ['id', 'scope', 'name', 'order', 'shortname', 'value', 'info'];
    protected $fillable = ['id', 'scope', 'name', 'order', 'shortname', 'value', 'info'];
    /**
     * Returns all records from a given $scope.
     */
    public static function byScope(string $scope): array
    {
        $rc = SProperty::where('scope', $scope)->orderBy('order')->get();
        return $rc;
    }
    /**
     * Returns the arrays used for combobox titles and values.
     * @param string $scope Only record matching this scope will be used
     * @param array $titles OUT: contains the text entries of the combobox
     * @param array $value OUT: contains the values of the combobox
     * @param string $titleField name of the table column used for $titles, normally "name"
     * @param string $valueField name of the table column used for $value, normally "id"
     */
    public static function combobox(
        string $scope,
        array &$titles,
        array &$values,
        string $titleField = 'name',
        string $valueField = 'id'
    ) {
        if (in_array($titleField, self::$fields) && in_array($valueField, self::$fields)) {
            $recs = SProperty::byScope($scope);
            foreach ($recs as &$rec) {
                array_push($titles, $rec[$titleField]);
                array_push($titles, $rec[$valueField]);
            }
        }
    }
    public static function scopes(bool $undef = false): array
    {
        $texts = [];
        $values = [];
        if ($undef) {
            array_push( $texts, '-');
            array_push( $values, '-');
        }
        $records = DB::select('select scope from sproperties group by scope order by scope');
        if (count($records) > 0) {
            foreach ($records as $record) {
                array_push($texts, $record->scope);
                array_push($values, $record->scope);
            }
        }
        return [$texts, $values];
    }
    public static function comboDataAsString(array $list, string $selected = ''): string
    {
        $rc = '';
        $texts = $list[0];
        $values = $list[1];
        for ($ix = 0; $ix < count($texts); $ix++) {
            $text = $texts[$ix];
            $value = $values[$ix];
            $sel = $value === $selected ? 'selected' : '';
            $rc .= "\n<option $sel value=\"$value\">$text</option>";
        }
        return $rc;
    }
    public static function toComboData(string $string)
    {
        $items = explode("\n", $string);
        $rc = [];
        foreach ($items as $item) {
            array_push($rc, explode("\n", $item));
        }
        return $rc;
    }
}

