<?php
//namespace Hamatoma\Laraknife;
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Pagination
{
    public int $totalCount;
    public int $filteredCount;
    public int $pageIndex;
    public int $pageCount;
    public int $pageSize;
    public int $offset;
    public $records;
    public $defaultPageSize;
    public int $visiblePages;
    /**
     * Constructor.
     * @param string $sql the sql statement for selecting the record with the table rows
     * @param array $fields the input fields
     * @param NULL|string $sqlTotalCount a sql statement (or part of that) to get the total
     *  count of the records. If null: that SQL statement is derived from $sql
     *  starting with 'select': the full SQL statement
     *  Otherwise: the table name
     * @param int $visiblePages the number of entries in the pagination menu. Should be an odd number
     */
    public function __construct(string $sql, array $parameters, array $fields, ?string $sqlTotalCount = null,
        int $visiblePages = 9, int $defaultPageSize = 20)
    {
        $this->defaultPageSize = $defaultPageSize;
        $this->visiblePages = intval(max(5, $visiblePages) / 2) * 2 + 1;
        if ($sqlTotalCount == null) {
            $match = null;
            if (!preg_match('/from\s+(\S+)/i', $sql, $matches)) {
                throw new \InvalidArgumentException("Pagination::__construct(): sqlTotalCount: missing \"from <table>\": $sqlTotalCount");
            }
            $sqlTotalCount = $matches[1];
        }
        if (!str_starts_with($sqlTotalCount, 'select')) {
            $sqlTotalCount = "select count(*) as count from $sqlTotalCount;";
        }
        $rec = DB::select($sqlTotalCount)[0];
        $this->totalCount = $rec->count;
        if (($start = stripos($sql, "\nfrom ")) === false) {
            $start = stripos($sql, 'from ');
            if ($start === false) {
                throw new \InvalidArgumentException("Pagination::__construct(): missing \"from <table>\": $sql");
            }
        }
        $sqlCount = 'select count(*) as count ' . substr($sql, $start);
        if (($start = stripos($sqlCount, 'order by')) !== false) {
            $sqlCount = substr($sqlCount, 0, $start) . ';';
        }
        $rec = DB::select($sqlCount, $parameters)[0];
        $this->filteredCount = $rec->count;
        $this->pageIndex = array_key_exists('pageIndex', $fields) ? intval($fields['pageIndex']) : 0;
        $this->pageSize = array_key_exists('pageSize', $fields) ? intval($fields['pageSize']) : $this->defaultPageSize;
    }
    public function legendText(int $countCriteria = 2): string
    {
        $rc = '';
        if ($this->filteredCount !== $this->totalCount) {

            switch ($countCriteria) {
                case 0:
                    break;
                case 1:
                    $rc = sprintf(__('The search criterion selects %d out of %d data records.'), $this->filteredCount, $this->totalCount);
                    break;
                default:
                    $rc = sprintf(__('The search criteria select %d out of %d data records.'), $this->filteredCount, $this->totalCount);
                    break;
            }
        }
        return $rc;
    }
    public function listItems(): array
    {
        $rc = [];
        // Remove first and last page:
        $floatingRange = $this->visiblePages - 2;
        $preRange = $floatingRange / 2;
        $this->pageSize = max(1, $this->pageSize);
        $this->pageCount = intval(($this->filteredCount + $this->pageSize - 1) / $this->pageSize);
        if ($this->pageCount > 0) {
            if ($this->pageCount <= $this->visiblePages) {
                for ($ix = 0; $ix < $this->pageCount; $ix++) {
                    array_push($rc, ['no' => $ix + 1, 'active' => $ix == $this->pageIndex, 'isGap' => false]);
                }
            } else {
                //$rc = $this->oneLi(0, $this->pageIndex == 0);
                array_push($rc, ['no' => 1, 'active' => 0 == $this->pageIndex, 'isGap' => false]);
                $start = max(1, $this->pageIndex - $preRange);
                if ($start > 2) {
                    array_push($rc, ['no' => 0, 'active' => false, 'isGap' => true]);
                }
                $end = $start + $floatingRange;
                while ($start <= $end) {
                    //$rc .= $this->oneLi($start + 1, $start == $this->pageIndex);
                    array_push($rc, ['no' => $start + 1, 'active' => $start == $this->pageIndex, 'isGap' => false]);
                    $start++;
                }
                if ($end < $this->pageCount - 1) {
                    array_push($rc, ['no' => $end, 'active' => false, 'isGap' => true]);
                }
                //$rc .= $this->oneLi($this->pageCount, $this->pageIndex == $this->pageCount - 1);
                array_push($rc, ['no' => $this->pageCount, 'active' => $this->pageIndex == $this->pageCount - 1, 'isGap' => false]);
            }
        }
        return $rc;
    }
    public function pageSizeSelections()
    {
        $sizes = [10, 20, 50, 100, -1];
        if (!array_search($this->pageSize, $sizes)) {
            if (!array_search($this->defaultPageSize, $sizes)) {
                $this->defaultPageSize = $sizes[0];
            }
            $this->pageSize = $this->defaultPageSize;
        }
        $rc = '';
        foreach ($sizes as $size) {
            if (!empty($rc)) {
                $rc .= ',';
            }
            if ($size == $this->pageSize) {
                $rc .= ' selected="selected"';
            }
        }
    }
}