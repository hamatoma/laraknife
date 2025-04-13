<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Change;
use App\Models\Module;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\FileHelper;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    /** Returns a HTML anchor for a given file
     * @param File $file the file object
     */
    public function buildAnchor($file)
    {
        $name = $file->filename;
        if (($ix = strpos($name, '_')) != false) {
            $name = substr($name, $ix + 1);
        }
        $rc = '<a href="' . strip_tags(FileHelper::buildFileLink($file->filename, $file->created_at)) . '" target="_blank">'
            . strip_tags($name) . '</a>';
        return $rc;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/file-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'filename' => '',
                    'filegroup_scope' => old('filegroup_scope', '1101'),
                    'visibility_scope' => old('visibility_scope', '1091'),
                    'user_id' => old('user_id', auth()->id())
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user_id'], '-');
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('file.create', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/file-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'fullname' => '',
                    'filegroup_scope' => '',
                    'user_id' => ''
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $file->filegroup_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $file->user_id, '-');
            $fields['fullname'] = FileHelper::buildFileStoragePath($file->created_at) . "/$file->filename";
            $context = new ContextLaraKnife($request, $fields, $file);
            $rc = view('file.edit', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsUser' => $optionsUser,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for exchanging a file.
     */
    public function exchange(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/file-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => $file->title,
                    'description' => $file->description,
                ];
            }
            $context = new ContextLaraKnife($request, null, $file);
            $rc = view('file.exchange', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $file->delete();
            FileHelper::deleteUploadedFile($file->filename, $file->created_at);
        }
        return redirect('/file-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/file-create');
        } else {
            $sql = "
SELECT t0.*, t1.name as filegroup, t2.name as user, t3.name as visibility 
FROM files t0
LEFT JOIN sproperties t1 ON t1.id=t0.filegroup_scope
LEFT JOIN users t2 ON t2.id=t0.user_id
LEFT JOIN sproperties t3 ON t3.id=t0.visibility_scope
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'filegroup' => '',
                    'visibility' => '1091',
                    'user' => '',
                    'text' => '',
                    'filegroup_scope' => '1101',
                    '_sortParams' => 'id:desc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'filegroup_scope', 'filegroup');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'visibility_scope', 'visibility');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'user_id', 'user');
            ViewHelper::addConditionPattern($fields, $conditions, $parameters, 'title,description,filename', 'text');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup'], 'all');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility'], 'all');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user']);
            $context = new ContextLaraKnife($request, $fields);
            $context->setCallback('buildAnchor', $this, 'buildAnchor');
            return view('file.index', [
                'context' => $context,
                'records' => $records,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate = false): array
    {
        if ($isCreate) {
            $rc = [
                'title' => 'required',
                'filegroup_scope' => 'required',
                //'file' => 'required',
                'user_id' => 'required'
            ];
        } else {
            $rc = [
                'title' => 'required',
                'filegroup_scope' => 'required',
            ];
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/file-index', [FileController::class, 'index'])->middleware('auth');
        Route::post('/file-index', [FileController::class, 'index'])->middleware('auth');
        Route::get('/file-create', [FileController::class, 'create'])->middleware('auth');
        Route::put('/file-store', [FileController::class, 'store'])->middleware('auth');
        Route::post('/file-edit/{file}', [FileController::class, 'edit'])->middleware('auth');
        Route::get('/file-edit/{file}', [FileController::class, 'edit'])->middleware('auth');
        Route::get('/file-exchange/{file}', [FileController::class, 'exchange'])->middleware('auth');
        Route::post('/file-exchange/{file}', [FileController::class, 'exchange'])->middleware('auth');
        Route::put('/file-updatefile/{file}', [FileController::class, 'updateFile'])->middleware('auth');
        Route::post('/file-update/{file}', [FileController::class, 'update'])->middleware('auth');
        Route::get('/file-show/{file}/delete', [FileController::class, 'show'])->middleware('auth');
        Route::delete('/file-show/{file}/delete', [FileController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/file-index');
        } else {
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $file->filegroup_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $file->user_id, '-');
            $context = new ContextLaraKnife($request, null, $file);
            $rc = view('file.show', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsUser' => $optionsUser,
                'mode' => 'delete'
            ]);
        }
        return $rc;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            if ($fields['title'] == null){
                $file = $request->file('file');
                $name = empty($fields['filename']) ? $file->getClientOriginalName() : $fields['filename'];
                $fields['title'] = File::filenameToText($name);
            }
             $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $fields['description'] = strip_tags($fields['description']);
                $fields['module_id'] = Module::idOfModule('File');
                $this->storeFile($request, $fields);
            }
        }
        if ($rc == null) {
            $rc = redirect('/file-index');
        }
        return $rc;
    }
    public function storeFile(Request $request, array $fields): bool
    {
        $rc = false;
        $file = $request->file('file');
        if ($file != null) {
            $name = empty($fields['filename']) ? $file->getClientOriginalName() : $fields['filename'];
            $ext = FileHelper::extensionOf($name);
            if (empty($ext)) {
                $name .= FileHelper::extensionOf($file->getClientOriginalName());
            }
            $filename = session('userName') . '_' . strval(time()) . '!' . $name;
            ViewHelper::addFieldIfMissing($fields, 'module_id', null);
            ViewHelper::addFieldIfMissing($fields, 'reference_id', null);
            $attributes = [
                'title' => $fields['title'],
                'description' => $fields['description'],
                'filename' => $filename,
                'filegroup_scope' => $fields['filegroup_scope'],
                'visibility_scope' => $fields['visibility_scope'],
                'user_id' => auth()->id(),
                'size' => $file->getSize() / 1E6,
                'module_id' => $fields['module_id'],
                'reference_id' => $fields['reference_id'],
            ];
            $file2 = new File($attributes);
            $filePath = FileHelper::storeFile($request, 'file', $filename);
            $file2->save();
            $id = $file2->id;
            Change::createFromFields($attributes, Change::$CREATE, 'File', $id);
            $filename2 = strval($id) . '_' . $name;
            FileHelper::renameUploadedFile($filename, $filename2, $file2->created_at);
            $file2->update(['filename' => $filename2]);
            $rc = true;
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(File $file, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            if ($fields['title'] == null || $fields['title'] === '') {
                $name = $file->filename;
                if ( ($ix = strpos($name, '_')) !== false) {
                    $name = substr($name, $ix + 1);
                }
                $fields['title'] = File::filenameToText($name);
            }
            $fields['description'] = strip_tags($fields['description']);
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $fields2 = ['title' => $fields['title'], 'description' => $fields['description'], 
                'filegroup_scope' => $fields['filegroup_scope']];
                $file->update($fields2);
            }
        }
        if ($rc == null) {
            $rc = redirect('/file-index');
        }
        return $rc;
    }
    public function updateFile(int $fileid, Request $request)
    {
        $rc = null;
        $file = File::find($fileid);
        $fieldname = 'file';
        $upload = $request->file($fieldname);
        if ($upload != null) {
            FileHelper::replaceUploadedFile($request, $fieldname, $file->filename, $file->created_at);
            $file->update(['size' => $upload->getSize() / 1E6]);
        }
        $rc = redirect('/file-index');
        return $rc;
    }
}
