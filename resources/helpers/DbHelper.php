<?php
namespace App\Helpers;

use App\Helpers\ViewHelper;
use Illuminate\Support\Facades\DB;

class DbHelper
{
    public $table;
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    /**
     * Adds some where conditions given in an array at the end of the given SQL statement.
     * @param string $sql the SQL statement to extend
     * @param array $conditions a list of conditions
     * @return string the $sql with an added where condition
     */
    public static function addConditions(string $sql, array $conditions): string{
        if (count($conditions) > 0) {
            $condition = count($conditions) == 1 ? $conditions[0] : implode(' AND ', $conditions);
            $sql .= " WHERE $condition";
        }
        return $sql;
    }
    /**
     * Appends the "order by" part of an SQL driven by a hidden field to a given SQL statement.
     * That hidden field is managed by JavaScript: 
     * If the user clicks on a table header that value is updated.
     * The hidden field stores the inverted history of the sort ids of that table headers
     * followed by the sort direction "asc" or "desc".
     * The last clicked header is listed at the top of $orderData.
     * 
     * @param string $sql that SQL statement will be expanded by the "order by" clause
     * @param string $orderData the contents of the hidden field. Example: "id:asc;name:desc"
     * @param string $defaultOrder defines the order if the $orderData is empty. Examples: "id" "id desc"
     * @return string the order-by string, e.g. "order by id asc, name desc"
     */
    public static function addOrderBy(string $sql, string $orderData, string $defaultOrder = ''): string
    {

        $order = '';
        if (empty($orderData)) {
            $order .= $defaultOrder;
        } else {
            $list = explode(';', $orderData);
            foreach ($list as $item) {
                $parts = explode(':', $item);
                if (!empty($order)) {
                    $order .= ',';
                }
                if (strpos($first = $parts[0], '`') !== false){
                    $order .= $first;
                } elseif (strpos($first, '.') === false){
                    $order .= "`$first`";
                } else {
                    $parts2 = explode('.', $first);
                    $order .= $parts2[0] . ".`$parts2[1]`";
                }
                if (count($parts) > 0 && $parts[1] === 'desc') {
                    $order .= ' desc';
                }
            }
        }
        if (! empty($order)){
            $sql .= " order by $order";
        }
        return $sql;
    }
    /**
     * Builds the sum of a given $column from all $records.
     * @param array $records the record to inspect
     * @param string $column the name of the column to inspect
     * @param string $format the format like in sprintf()
     * @return string the sum as string
     */
    public static function buildSum(array $records, string $column, string $format="%0.2f"): string{
        $rc = 0;
        foreach($records as &$record){
            $rc += $record->$column;
        }
        return sprintf($format, $rc);
    }
    /**
     * Returns one column from a record given by the primary key.
     * @param mixed $primaryKey specifies the record
     * @param string $column specifies the column
     * @return NULL|mixed NULL: no record found. Otherwise: the $column of the record
     */
    public function columnOf($primaryKey, string $column)
    {
        $rc = DB::table($this->table)->find($primaryKey);
        return $rc ? $rc->$column : null;
    }
    /**
     * Returns the data used for constructing a combobox with <x-laraknife.combobox>.
     * @param string $table the table with the combox data
     * @param string $titleField name of the table column used for $titles, normally "name"
     * @param string $valueField name of the table column used for $value, normally "id"
     * @param string $selected current value of the field. Defines the selected entry
     * @param string $undefinedText '' (no "undefined" entry), '-' or 'all'
     * @param string $where the '' or where condition, e.g. "where id > 20"
     * @param int $limit maximal number of entries
     * @return array a list of arrays ['value' => value, 'text' => text, 'active' => isActive]
     */
    public static function comboboxDataOfTable(
        string $table,
        string $titleField,
        string $valueField,
        ?string $selected,
        string $undefinedText = 'all',
        string $where = '',
        string $orderBy = '',
        int $limit = 100
    ): array {
        $selected ??= '';
        if ($undefinedText == null || $undefinedText === ''){
            $rc = [];
        } else {
            if ($undefinedText == 'all'){
                $undefinedText = __('<All>');
            } elseif ($undefinedText == '-'){
                $undefinedText = __('<Please select>');
            }
            $rc = [['text' => $undefinedText, 'value' => '', 'active' => $selected === '']];
        }
        $sql = "SELECT $titleField, $valueField from $table $where";
        if ($orderBy == null){
            $orderBy = $titleField;
        }
        if ($orderBy !== ''){
            $sql .= ' order by ' . $orderBy;
        }
        $sql .= " limit $limit;";
        $recs = DB::select($sql);
        foreach ($recs as &$rec) {
            $value = strval($rec->$valueField);
            array_push($rc, ['text' => $rec->$titleField, 'value' => $value, 'active' => $value === $selected]);
        }
        return $rc;
    }
    /**
     * Returns the selected value of a item list created by comboboxDataOfTable().
     * @param array $items  the items of the combobox
     * @return string the value of the first "active" item in the list or the value of the first item.
     */
    public static function findCurrentSelectedInCombobox(array &$items): string{
        $rc = strval($items[0]['value']);
        foreach ($items as &$item) {
            if ($item['active']){
                $rc = strval($item['value']);
                break;
            }
        }
        return $rc;
    }
    /**
     * Brings the $records in the order of $ids (using column 'id').
     * @param $records the database records
     * @param $ids a list of ids (integer)
     * @return array all entries of $records with the order given by ids
     */
    public static function resortById(array $records, array $ids): array{
        $rc = [];
        foreach($ids as &$id){
            foreach ($records as &$rec){
                if ($rec->id == $id){
                    array_push($rc, $rec);
                    break;
                }
            }
        }
        return $rc;
    }
}