<?php
namespace App\Helpers;

define('DOUBLE_SEP', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);

class OsHelper
{
    /**
     * Creates a directory if it does not exist.
     * @param string $directory the directory to inspect
     * @return bool true: the directory exists
     */
    public static function ensureDirectory(string $directory): bool{
        if (! file_exists($directory)){
            mkdir($directory, 0777, true);
        }
        return is_dir($directory);
    }
    /**
     * Join two parts of a full filename independent of the platform.
     * @param string|NULL $directory if NULL it is ignored otherwise: the first part of the result
     * @param string $file the second part of the result
     * @return string the combination of $directory and $file
     */
    public static function joinPath(?string $directory, string $file): string
    {
        $directory ??= '.';
        if ($directory === '' || $directory === '.') {
            $rc = $file;
        } else {
            $rc = $directory . DIRECTORY_SEPARATOR . $file;
            if (DIRECTORY_SEPARATOR !== '/' && str_contains($rc, '/')) {
                $rc = str_replace('/', DIRECTORY_SEPARATOR, $rc);
            }
        }
        return $rc;
    }
/**
     * Join many parts of a full filename independent of the platform.
     * @param string|NULL $directory if NULL it is ignored otherwise: the first part of the result
     * @param array $node a list of parts of the result
     * @return string|NULL an optional additional part
     * @return string the combinations of the $nodes and $file
     */
    public static function joinPaths(array $nodes, ?string $file = null): string
    {
        if ($file != null) {
            array_push($nodes, $file);
        }
        $rc = implode(DIRECTORY_SEPARATOR, $nodes);
        if (DIRECTORY_SEPARATOR !== '/' && str_contains($rc, '/')) {
            $rc = str_replace('/', DIRECTORY_SEPARATOR, $rc);
        }
        while (str_contains($rc, DOUBLE_SEP)) {
            $rc = str_replace(DOUBLE_SEP, DIRECTORY_SEPARATOR, $rc);
        }
        return $rc;
    }
}