<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Note;
use App\Models\Page;
use App\Models\Group;
use App\Models\Module;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\FileHelper;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnImport') {
            $rc = redirect('/export-import');
        } else {
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'patterns' => '',
                ];
            }
            $path = $_SERVER['DOCUMENT_ROOT'] . "/export";
            $patterns = str_replace(['*', '?'], ['.*', '.'], $fields['patterns']);
            $patterns = '/^' . auth()->user()->name . "\\.$patterns/";
            $files = FileHelper::fileInfoList($path, $patterns);
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('export.index', [
                'context' => $context,
                'records' => $files,
            ]);
        }
        return $rc;
    }
    public function import(Request $request)
    {
        $fields = $request->all();
        if (count($fields) == 0) {
            $fields = [
                'filename' => '',
            ];
        }
        $path = $_SERVER['DOCUMENT_ROOT'] . "/temp";
        if ($request->btnSubmit === 'btnUpload') {
            if (($file = $request->file('file')) != null) {
                $relativePath = basename($path);
                $filename = $fields['filename'] = auth()->user()->name . '.import.' . strval(time() % 86400);
                $filePath = $request->file('file')->storeAs($relativePath, $filename, 'public');
                $full = "$path/$filename";
                $fields['info'] = $this->infoOfImportFile($full);
                if (str_starts_with($fields['info'], '+++')) {
                    $fields['filename'] = '';
                }
            }
        } elseif ($request->btnSubmit === 'btnStore' && ($filename = $fields['filename']) !== '') {
            $full = "$path/$filename";
            $fields['info'] = $this->importFile($full);
        }
        $context = new ContextLaraKnife($request, $fields);
        $rc = view('export.import', [
            'context' => $context,
        ]);
        return $rc;
    }
    protected function importFile(string $filename): string
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
                                    $this->importToDatabase($metadata, $columns, $rc);
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
        $this->importToDatabase($metadata, $columns, $rc);
        $count = $metadata['count'];
        $rc .= __('stored: ') . " $count " . trans_choice('record', $count);
        return $rc;
    }
    protected function importToDatabase(array &$metadata, array &$columns, string &$error)
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
    protected function infoOfImportFile(string $filename): string
    {
        $rc = '';
        $lines = explode("\n", file_get_contents($filename));
        if (!str_starts_with($lines[0], ':LaraKnife-Export')) {
            $rc = __('+++ Not an import file: missing MAGIC');
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
                    $rc .= __(substr($parts[0], 1)) . ": $parts[1]";
                }
            }
            $rc .= "\n" . trans_choice('table', count($tables) == 1) . " $tables[0]";
            $rc .= "\n" . trans_choice('action:', count($actions)) . " $actions[0]";
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/export-index', [ExportController::class, 'index'])->middleware('auth');
        Route::post('/export-index', [ExportController::class, 'index'])->middleware('auth');
        Route::get('/export-rm/{file}', [ExportController::class, 'removeFile'])->middleware('auth');
        Route::get('/export-import', [ExportController::class, 'import'])->middleware('auth');
        Route::post('/export-import', [ExportController::class, 'import'])->middleware('auth');
    }
    public function removeFile(string $nodeEncoded, Request $request)
    {
        $node = FileHelper::decodeUrl($nodeEncoded);
        $full = $_SERVER['DOCUMENT_ROOT'] . "/export/$node";
        if (file_exists($full)) {
            unlink($full);
        }
        $rc = redirect('/export-index');
        return $rc;
    }
}

