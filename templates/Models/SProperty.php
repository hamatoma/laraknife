<?php

namespace App\Models;
use App\Helpers\DbHelper;
use App\Helpers\ViewHelper;

use Illuminate\Database\Eloquent\Collection;
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
    public static function byScope(string $scope): Collection
    {
        $rc = SProperty::where('scope', $scope)->orderBy('order')->get();
        return $rc;
    }
    /**
     * Returns the id of the record with a given $scope and $name.
     */
    public static function byScopeAndName(string $scope, string $name): ?int
    {
        $record = SProperty::where('scope', $scope)->where('name', $name)->first();
        $rc = $record == null ? null : $record->id;
        return $rc;
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
    /**
     * Builds the HTML selection options as string from all entries by a given scope.
     * @param string $scope defines the database records to use
     * @param string $selected the current field value (defines the selected entry)
     * cted the value which marks the selected entry
     * @param NULL|string $titleUndefined  if not null the first entry has that title and the value ''
     * @param string $titleField the titles are taken from that column
     * @param string $valueField the value are taken from that column
     * @param bool $translate true: the title will be translated
     * @return string the HTML text of the options
     */
    public static function optionsByScope(string $scope, string $selected, 
        ?string $titleUndefined = null, string $titleField = 'name', 
        string $valueField = 'id', bool $translate=true): array
    {
        if ($titleUndefined === '' or $titleUndefined == null){
            $rc = [];
        } else {
            if ($titleUndefined == 'all'){
                $titleUndefined = __('<All>');
            } elseif ($titleUndefined == '-'){
                $titleUndefined = __('<Please select>');
            }
            $rc = [['text' => $titleUndefined, 'value' => '', 'active' => $selected === '']];
        }
        if (in_array($titleField, self::$fields) && in_array($valueField, self::$fields)) {
            $recs = SProperty::byScope($scope);
            foreach ($recs as &$rec) {
                $value = strval($rec[$valueField]);
                $title = $rec[$titleField];
                if ($translate){
                    $title = __($title);
                }
                array_push($rc, ['text' => $title, 'value' => $value, 
                    'active' => $value === $selected]);
            }
        }
        return $rc;
    }
}

