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
    public function __construct(string $sql, array $fields, ?string $sqlTotalCount = null,
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
        $rec = DB::select($sqlCount)[0];
        $this->filteredCount = $rec->count;
        $this->pageIndex = array_key_exists('pageIndex', $fields) ? $fields['pageIndex'] : 0;
        $this->pageSize = array_key_exists('pageSize', $fields) ? $fields['pageSize'] : $this->defaultPageSize;
    }
    private function oneLi(int $pageNo, bool $isActive = false, bool $isGap = false)
    {
        $active = $isActive ? ' active' : '';
        $ix = $pageNo - 1;
        if ($isGap) {
            $rc = "   <li class=\"page-item\">..</li>\n";
        } else {
            $rc = "   <li class=\"page-item$active\"><a data-paging-index=\"$ix\" class=\"page-link\" href=\"#$pageNo\">$pageNo</a></li>\n";
        }
        return $rc;
    }
    public function pagingHtml(): string{
        $listItems = $this->listItems();
        $filtered = __('Filtered');
        $from = __('from');
        $linesPerPage = __('Lines per Page');
        $rc = <<<EOS
 <div class="lkn-paging-block">
  <input type="hidden" name="pageIndex" id="_pageIndex" value="$this->pageIndex" />
  <div class="lkn-float-right lkn-paging-text">$filtered: $this->filteredCount $from $this->totalCount &nbsp;
   <select id="_pageSize" name="pageSize" class="lkn-autoupdate">
EOS;
        $sizes = [10, 20, 50, 100];
        foreach($sizes as $size){
            $selected = $this->pageSize == $size ? ' selected="selected"' : '';
            $rc .= "    <option value=\"$size\"$selected>$size</option>\n";
        }
        $selected = $this->pageSize == -1 ? ' selected="selected"' : '';
        $rc .= "    <option value=\"-1\"$selected>" . __("All") . "</option>\n";
        $rc .= <<<EOS
   </select>&nbsp;$linesPerPage
  </div>
  <div class="float-left">
   <ul class="pagination lkn-compact-block">
$listItems		
   </ul>
  </div>
  <div class="clear-both"></div>
 </div>
EOS;
        return $rc;
    }
    public function listItems(): string
    {
        // <!--li class="page-item active"><a data-paging-index="0" class="page-link" href="#">1</a></li-->
        $rc = '';
        // Remove first and last page:
        $floatingRange = $this->visiblePages - 2;
        $preRange = $floatingRange / 2;
        $this->pageCount = intval(($this->filteredCount + $this->pageSize - 1) / $this->pageSize);
        if ($this->pageCount > 0) {
            if ($this->pageCount <= $this->visiblePages) {
                for ($ix = 0; $ix < $this->pageCount; $ix++) {
                    $rc .= $this->oneLi($ix + 1, $ix == $this->pageIndex);
                }
            } else {
                $rc = $this->oneLi(0, $this->pageIndex == 0);
                $start = max(1, $this->pageIndex - $preRange);
                if ($start > 2) {
                    $this->oneLi(0, false, true);
                }
                $end = $start + $floatingRange;
                while ($start <= $end) {
                    $rc .= $this->oneLi($start + 1, $start == $this->pageIndex);
                    $start++;
                }
                if ($end < $this->pageCount - 1) {
                    $rc .= $this->oneLi(0, false, true);
                }
                $rc .= $this->oneLi($this->pageCount, $this->pageIndex == $this->pageCount - 1);
            }
        }
        return $rc;
    }
    public function pageSizeSelections(){
        $sizes = [10, 20, 50, 100, -1];
        if (! array_search($this->pageSize, $sizes)){
            if (! array_search($this->defaultPageSize, $sizes)){
                $this->defaultPageSize = $sizes[0];
            }
            $this->pageSize = $this->defaultPageSize;
        }
        $rc = '';
        foreach($sizes as $size){
            if (! empty($rc)){
                $rc .= ',';
            }
            if ($size == $this->pageSize){
                $rc .= ' selected="selected"';
            }
        }
    }
}