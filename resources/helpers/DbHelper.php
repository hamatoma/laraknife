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
                $order .= "`$parts[0]`";
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
        string $selected,
        string $undefinedText = 'all',
        string $where = '',
        string $orderBy = '',
        int $limit = 100
    ): array {
        if ($undefinedText === ''){
            $rc = [];
        } else {
            if ($undefinedText === 'all'){
                $undefinedText = __('<All>');
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
}