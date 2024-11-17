<?php

namespace App\Http\Controllers;

use App\Helpers\StandardTranslatorHelper;
use App\Models\File;
use App\Models\Note;
use App\Models\Page;
use App\Models\Group;
use App\Models\Module;
use App\Models\Export;
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
                $fields['info'] = Export::infoOfImportFile($full, new StandardTranslatorHelper());
                if (str_starts_with($fields['info'], '+++')) {
                    $fields['filename'] = '';
                }
            }
        } elseif ($request->btnSubmit === 'btnStore' && ($filename = $fields['filename']) !== '') {
            $full = "$path/$filename";
            $fields['info'] = Export::importFile($full, new StandardTranslatorHelper());
        }
        $context = new ContextLaraKnife($request, $fields);
        $rc = view('export.import', [
            'context' => $context,
        ]);
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

