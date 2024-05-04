<?php
namespace App\Helpers;
/**
 * Manages a key value storage.
 * The string format is: a amount of lines.
 * Each line has the format: <key>=<value>
 */
class KeyValueStorage{
    private $map;
    private $keys;
    public $hasChanged;
    public function __construct(?string $data=null){
        $this->map = [];
        $this->hasChanged = false;
        $this->keys = [];
        if ($data != null){
            $this->fromString($data);
        }
    }
    public function fromString(string &$data){
        $start = 0;
        while (true){
            if ( ($ix = strpos($data, '=', $start)) === false){
                break;
            } else {
                $ix++;
                $key = substr($data, $start, $ix - $start - 1);
                array_push($this->keys, $key);
                $value = ($ixEnd = strpos($data, "\n", $ix)) === false ? substr($data, $ix) : substr($data, $ix, $ixEnd - $ix);
                $this->map[$key] = $value;
                if ($ixEnd === false){
                    break;
                }
                $start = $ixEnd + 1;
            }
        }
    }
    public function get(string $key): ?string{
        $rc = null;
        if (in_array($key, $this->keys)){
            $rc = $this->map[$key];
        }
        return $rc;
    }
    public function put(string $key, string $value){
        $hasKey = in_array($key, $this->keys);
        if (! $hasKey || $this->map[$key] !== $value){
            $this->map[$key] = $value;
            $this->hasChanged = true;
            if (! $hasKey){
                array_push($this->keys, $key);
            }
        }
    }
    public function toString(): string{
        $rc = '';
        foreach ($this->keys as $key){
            $value = $key . '=' . $this->map[$key];
            if ($rc === ''){
                $rc .= $value;
            } else {
                $rc .= "\n$value";
            }
        }
        return $rc;
    }
}
