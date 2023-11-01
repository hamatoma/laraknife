<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SProperty extends Model
{
    use HasFactory;
    // editable fields:
    protected static $fields = ['scope', 'name', 'order', 'shortname', 'value', 'info'];
    protected $fillable = SProperty::fields;

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
        if (in_array($titleField, self::$fillable) && in_array($valueField, self::$fillable)) {
            $recs = SProperty::byScope($scope);
            foreach ($recs as &$rec) {
                array_push($titles, $rec[$titleField]);
                array_push($titles, $rec[$valueField]);
            }
        }
    }
}

