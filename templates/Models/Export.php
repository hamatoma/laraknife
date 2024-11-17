<?php

namespace App\Models;

use App\Models\Page;
use App\Models\Module;
use App\Helpers\TranslatorHelper;

class Export{
    public static function importFile(string $filename, TranslatorHelper $translator): string
    {
        $rc = '';
        $storeId = false;
        $lines = explode("\n", file_get_contents($filename));
        $columns = [];
        $metadata = ['isRestore' => false, 'action' => '?', 'table' => '', 'count' => 0, 'separator' => '~%'];
        $openColumn = null;
        $match = null;
        $lineNo = 0;
        $action = null;
        $table = null;
        foreach ($lines as &$line) {
            if ($lineNo++ == 0) {
                continue;
            }
            if ($openColumn != null) {
                if ($line === $metadata['separator']) {
                    $openColumn = null;
                } else {
                    $columns[$openColumn] .= "\n$line";
                }
            } else {
                if (!preg_match('/^([!~:]?)(\w+)=(.*)/', $line, $match)) {
                    if (trim($line) != '' && strlen($rc) < 200) {
                        $rc .= "+++ [$lineNo]: ???: $line\n";
                        continue;
                    }
                } else {
                    $prefix = $match[1];
                    $keyword = $match[2];
                    $value = $match[3];
                    switch ($prefix) {
                        case ':':
                            switch ($keyword) {
                                case 'host':
                                    $metadata[$keyword] = $value;
                                    $metadata['isRestore'] = $value === env('APP_URL');
                                    break;
                                case 'action':
                                    self::importToDatabase($metadata, $columns, $rc);
                                    $columns = [];
                                    $metadata[$keyword] = $value;
                                    break;
                                default:
                                    $metadata[$keyword] = $value;
                                    break;
                            }
                            break;
                        case '~':
                            $columns[$openColumn = $keyword] = $value;
                            break;
                        case '!':
                            if ($metadata['isRestore']) {
                                if ($storeId || $keyword !== 'id') {
                                    $columns[$keyword] = $value;
                                }
                            }
                            break;
                        default:
                            $columns[$keyword] = $value;
                            break;
                    }
                }
            }
        }
        self::importToDatabase($metadata, $columns, $rc);
        $count = $metadata['count'];
        $rc .= $translator->trans('stored: ') . " $count " . $translator->trans_choice('record', $count);
        return $rc;
    }
    protected static function importToDatabase(array &$metadata, array &$columns, string &$error)
    {
        if (($table = $metadata['table']) === '') {
            if (count($columns) > 0) {
                $error .= "+++ missing table\n";
            }
        } elseif (count($columns) > 0 && ($module = Module::moduleOfTable($table)) == null) {
            $error .= "+++ unknown table: $table\n";
        } else {
            switch ($action = $metadata['action']) {
                case 'insert':
                    switch($module){
                        case 'Page':
                            if (! array_key_exists('owner_id', $columns)){
                                $columns['owner_id'] = auth()->user()->id;
                            }
                            $ok = Page::insert($columns);
                            break;
                        default:
                            $ok = false;
                        break;
                    }
                    if ($ok) {
                        $metadata['count']++;
                    }
                    break;
                default:
                    $error .= "+++ unknown action: $action\n";
                    break;
            }
        }
    }
    public static function infoOfImportFile(string $filename, TranslatorHelper $translator): string
    {
        $rc = '';
        $lines = explode("\n", file_get_contents($filename));
        if (!str_starts_with($lines[0], ':LaraKnife-Export')) {
            $rc = $translator->trans('+++ Not an import file: missing MAGIC');
        } else {
            $tables = [];
            $actions = [];
            foreach ($lines as &$line) {
                if (str_starts_with($line, ':table=')) {
                    $table = substr($line, 7);
                    if (!in_array($table, $tables)) {
                        array_push($tables, $table);
                    }
                } elseif (str_starts_with($line, ':action=')) {
                    $action = substr($line, 8);
                    if (!in_array($action, $actions)) {
                        array_push($actions, $action);
                    }
                } elseif (preg_match('/^:(exported|host|records)=/', $line)) {
                    $parts = explode('=', $line);
                    if ($rc !== '') {
                        $rc .= "\n";
                    }
                    $rc .= $translator->trans(substr($parts[0], 1)) . ": $parts[1]";
                }
            }
            $rc .= "\n" . $translator->trans_choice('table', count($tables) == 1) . " $tables[0]";
            $rc .= "\n" . $translator->trans_choice('action:', count($actions)) . " $actions[0]";
        }
        return $rc;
    }
}
