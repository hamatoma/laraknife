<?php
namespace Hamatoma\Laraknife;

class Helper{
    /**
     * Converts the $string into a capital string: the first character will be uppercase.
     */
    public static function toCapital($string): string{
        if (empty($string)){
            $rc = '';
        } else {
            $rc = strtoupper(substr($string, 0, 1)) . substr($string, 1);
        }
        return $rc;
    }

}