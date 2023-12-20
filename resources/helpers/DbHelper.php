<?php
//namespace Hamatoma\Laraknife;
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbHelper
{
    public $table;
    public function __construct(string $table)
    {
        $this->table = $table;
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
     * Handles the pagination of a query to fill a data table.
     * 
     * The query should only return a part of the result set. This is called a "page".
     * The first page has the offset 0 and the page number 1.
     * The 2nd page has the offset $pageSize and the page number 2.
     * @param string $sql defines the query for the table
     * @param array $incoming the field values (from $_POST or $_GET)
     * @param int $pageNo OUT: the number of the current page. Starts with 1.
     * @param int $pageCount OUT: the number of all pages
     * @param int $fieldOffset the name of the field containing the current offset
     * @param int $fieldPageSize maximal number of rows in the result
     * @param string $whereUnfiltered the condition for an unfiltered query
     * @param string $fieldOffset the name of the hidden field storing the current offset
     * 
     */
    public function pagination(string $sql, array $incoming, int &$pageNo, int &$pageCount,
        ?string $whereUnfiltered = '1', string $fieldOffset = '_dbOffset',
        string $fieldPageSize = '_dbPageSize')
    {
        if ($whereUnfiltered == null) {
            $whereUnfiltered = '1';
        }
        $sqlCount = "select count(*) from $this->table where $whereUnfiltered";
        //$countRecords = DB::select($sqlCount)->first();
        $offset = intval($incoming[$fieldOffset]);
        $pageSize = intval($incoming[$fieldPageSize]);
        $rc = $sql . " offset $offset limit $;";
        return $rc;
    }
}