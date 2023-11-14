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
    /**
     * Returns a list of all scopes: values of the column "scope" in the table sproproperties.
     */
    public static function scopes(): array
    {
        $rc = [];
        $records = DB::select('select distinct scope from sproperties order by scope');
        if (count($records) > 0) {
            foreach ($records as $record) {
                array_push($rc, $record->scope);
            }
        }
        return $rc;
    }
}

