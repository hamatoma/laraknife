<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Note;
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

class NoteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/note-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'body' => '',
                    'category_scope' => '1051',
                    'notestatus_scope' => '1011',
                    'visibility_scope' => '1091',
                    'user_id' => strval(auth()->id())
                ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $fields['category_scope'], '-');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $fields['notestatus_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('note.create', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
            ]);
        }
        return $rc;
    }
    public function createDocument(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/note-index_documents/$note->id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'filename' => '',
                    'filegroup_scope' => '1101',
                    'visibility_scope' => '1091',
                    'user_id' => auth()->id()
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('note.create_document', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'note' => $note
            ]);
        }
        return $rc;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/note-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'body' => '',
                    'category_scope' => '1051',
                    'notestatus_scope' => '1011',
                    'visibility_scope' => '1091',
                    'user_id' => strval(auth()->id())
                ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $note->category_scope, '');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $note->notestatus_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->user_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $note);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('note-edit', 0, $note->id);
            $rc = view('note.edit', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    public function editDocument(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/note-index_documents/$file->reference_id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'filename' => '',
                    'filegroup_scope' => '',
                    'visibility_scope' => '',
                    'user_id' => ''
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $file->filegroup_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $file->visibility_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $file->user_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $file);
            $rc = view('note.edit_document', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
            ]);
        }
        return $rc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $note->delete();
        }
        return redirect('/note-index');
    }
    public function destroyDocument(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $file->delete();
            FileHelper::deleteUploadedFile($file->filename, $file->created_at);
        }
        return redirect("/note-index_documents/$file->reference_id");
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/note-create');
        } else {
            $sql = "
SELECT t0.*, cast(t0.body AS VARCHAR(40)) as body_short, t1.name as category_scope, t2.name as notestatus_scope, t3.name as user_id 
FROM notes t0
LEFT JOIN sproperties t1 ON t1.id=t0.category_scope
LEFT JOIN sproperties t2 ON t2.id=t0.notestatus_scope
LEFT JOIN sproperties t3 ON t3.id=t0.user_id
LEFT JOIN sproperties t4 ON t4.id=t0.visibility_scope
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'category' => '1051',
                    'notestatus' => '1011',
                    'visibility' => '1091',
                    'user' => strval(auth()->id()),
                    'title' => '',
                    'body' => '',
                    '_sortParams' => 'id:asc'
                        . ';title:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'category_scope', 'category');
                ViewHelper::addConditionComparism($conditions, $parameters, 'notestatus_scope', 'notestatus');
                ViewHelper::addConditionComparism($conditions, $parameters, 'visibility_scope', 'visibility');
                ViewHelper::addConditionComparism($conditions, $parameters, 'user_id', 'user');
                ViewHelper::addConditionPattern($conditions, $parameters, 'title,body', 'text');
                ViewHelper::addConditionPattern($conditions, $parameters, 'title');
                ViewHelper::addConditionPattern($conditions, $parameters, 'body');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsCategory = SProperty::optionsByScope('category', $fields['category'], 'all');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $fields['notestatus'], 'all');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility'], 'all');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user']);
            $context = new ContextLaraKnife($request, $fields);
            return view('note.index', [
                'context' => $context,
                'records' => $records,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'pagination' => $pagination
            ]);
        }
    }
    public function indexDocuments(Note $note, Request $request)
    {
        $moduleId = Module::idOfModule('Note');
        if ($request->btnSubmit === 'btnNew') {
            return redirect("/note-create_document/$note->id");
        } else {
            $sql = "
SELECT t0.*, t1.name as filegroup_scope, t2.name as user_id 
FROM files t0
LEFT JOIN sproperties t1 ON t1.id=t0.filegroup_scope
LEFT JOIN sproperties t2 ON t2.id=t0.user_id
";
            $parameters = [];
            $conditions = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'filegroup' => '',
                    'user' => auth()->id(),
                    'text' => '',
                    'filegroup_scope' => '1101',
                    '_sortParams' => 'id:desc',
                ];
            } else {
                ViewHelper::addConditionPattern($conditions, $parameters, 'title,description,filename', 'text');
            }
            ViewHelper::addConditionConstComparison($conditions, $parameters, 'module_id', $moduleId);
            ViewHelper::addConditionConstComparison($conditions, $parameters, 'reference_id', $note->id);
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup'], 'all');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user']);
            $context = new ContextLaraKnife($request, $fields);
            $fileController = new FileController();
            $context->setCallback('buildAnchor', $fileController, 'buildAnchor');
            $navTabInfo = ViewHelperLocal::getNavigationTabInfo('note-edit', 1, $note->id);
            return view('note.index_documents', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'navigationTabs' => $navTabInfo,
                'note' => $note,
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate = false): array
    {
        $rc = [
            'title' => 'required',
            'category_scope' => 'required',
            'notestatus_scope' => 'required',
            'visibility_scope' => 'required',
            'user_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/note-index', [NoteController::class, 'index'])->middleware('auth');
        Route::post('/note-index', [NoteController::class, 'index'])->middleware('auth');
        Route::get('/note-create', [NoteController::class, 'create'])->middleware('auth');
        Route::put('/note-store', [NoteController::class, 'store'])->middleware('auth');
        Route::post('/note-edit/{note}', [NoteController::class, 'edit'])->middleware('auth');
        Route::get('/note-edit/{note}', [NoteController::class, 'edit'])->middleware('auth');
        Route::post('/note-index_documents/{note}', [NoteController::class, 'indexDocuments'])->middleware('auth');
        Route::get('/note-index_documents/{note}', [NoteController::class, 'indexDocuments'])->middleware('auth');
        Route::post('/note-update/{note}', [NoteController::class, 'update'])->middleware('auth');
        Route::get('/note-show/{note}/delete', [NoteController::class, 'show'])->middleware('auth');
        Route::delete('/note-show/{note}/delete', [NoteController::class, 'destroy'])->middleware('auth');
        Route::get('/note-create_document/{note}', [NoteController::class, 'createDocument'])->middleware('auth');
        Route::put('/note-store_document/{note}', [NoteController::class, 'storeDocument'])->middleware('auth');
        Route::get('/note-edit_document/{file}', [NoteController::class, 'editDocument'])->middleware('auth');
        Route::post('/note-edit_document/{file}', [NoteController::class, 'editDocument'])->middleware('auth');
        Route::post('/note-update_document/{file}', [NoteController::class, 'updateDocument'])->middleware('auth');
        Route::get('/note-show_document/{file}/delete', [NoteController::class, 'showDocument'])->middleware('auth');
        Route::delete('/note-show_document/{file}/delete', [NoteController::class, 'destroyDocument'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/note-index');
        } else {
            $optionsCategory = SProperty::optionsByScope('category', $note->category_scope, '');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $note->notestatus_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $note->visibility_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->user_id);
            $context = new ContextLaraKnife($request, null, $note);
            $rc = view('note.show', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'mode' => 'delete'
            ]);
        }
        return $rc;
    }
    public function showDocument(File $file, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/note-index_documents/$file->reference_id");
        } else {
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $file->filegroup_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $file->visibility_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $file->user_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $file);
            $rc = view('note.show_document', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
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
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                array_push($validated, strip_tags($fields['body'] ?? ''));
                Note::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/note-index');
        }
        return $rc;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeDocument(Note $note, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, ['title' => 'required', 'filegroup_scope' => 'required', 'visibility_scope' => 'required']);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $controller = new FileController();
                $fields['module_id'] = Module::idOfModule('Note');
                $fields['reference_id'] = $note->id;
                $fields['description'] = strip_tags($fields['description']);
                $controller->storeFile($request, $fields);
            }
        }
        if ($rc == null) {
            $rc = redirect("/note-index_documents/$note->id");
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Note $note, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            ViewHelper::addFieldIfMissing($fields, 'user_id', auth()->id());
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $error = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['body'] = strip_tags($fields['body']);
                $note->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/note-index');
        }
        return $rc;
    }
    public function updateDocument(File $file, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $fields['description'] = strip_tags($fields['description']);
            $validator = Validator::make($fields, ['title' => 'required']);
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $fields2 = $request->only(['title', 'description', 'filegroup_scope']);
                $file->update($fields2);
            }
        }
        if ($rc == null) {
            $rc = redirect("/note-index_documents/$file->reference_id");
        }
        return $rc;
    }
}
