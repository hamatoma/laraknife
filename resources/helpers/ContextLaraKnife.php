<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * Helpers for views
 * 
 * This class contains only static methods.
 */
class ContextLaraKnife {
    public ?array $fields;
    public Request $request;
    public ?Model $model;
    public int $currentNo;
    public function __construct(Request $request, ?array $fields, ?Model $model = null){
        $this->fields = $fields;
        $this->request = $request;
        $this->model = $model;
        $this->currentNo = 0;
    }
    public function valueOf(string $name){
        if ($this->model != null){
            $rc = old($name, $this->model[$name]);
        } else {
            if ($this->fields == null){
                $this->fields = $this->request->all();
            }
            $rc = old($name, $this->fields[$name]);
        }
        return $rc;
    }
    public function currentNo(): int{
        return ++$this->currentNo;
    }
}
