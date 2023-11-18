<?php
namespace Hamatoma\Laraknife;

class DbHelper{
    public $table;
    public function __construct(string $table){
        $this->table = $table;
    }
    public function columnOf($primaryKey, string $column){
        $rc = DB::table($this->table)->find($primaryKey);
        return $rc ? $rc->column : null;
    }
}