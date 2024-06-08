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
        return view('export.index', [
            'context' => $context,
            'records' => $files,
        ]);
    }
    public static function routes()
    {
        Route::get('/export-index', [ExportController::class, 'index'])->middleware('auth');
        Route::post('/export-index', [ExportController::class, 'index'])->middleware('auth');
        Route::get('/export-rm/{file}', [ExportController::class, 'removeFile'])->middleware('auth');
    }
    public function removeFile(String $nodeEncoded, Request $request){
        $node = FileHelper::decodeUrl($nodeEncoded);
        $full = $_SERVER['DOCUMENT_ROOT'] . "/export/$node";
        if (file_exists($full)){
            unlink($full);
        }
        $rc = redirect('/export-index');
        return $rc;
    }
}

