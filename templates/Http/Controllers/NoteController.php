<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Note;
use App\Models\Page;
use App\Models\User;
use App\Models\Group;
use App\Models\Change;
use App\Models\Module;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\FileHelper;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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
                    'owner_id' => strval(auth()->id())
                ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $fields['category_scope'], '-');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $fields['notestatus_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
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
                    'owner_id' => auth()->id()
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
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
                    'owner_id' => strval(auth()->id()),
                    'group_id' => ''
                ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $note->category_scope, '');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $note->notestatus_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->owner_id, __('<Please select>'));
            $optionsGroup = Group::combobox($note->group_id, __('<no group>'));
            $context = new ContextLaraKnife($request, null, $note);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('note-edit', 1, $note->id, $note->options, $note->reference_id);
            $rc = view('note.edit', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'optionsGroup' => $optionsGroup,
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
                    'owner_id' => ''
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
    public function editShift(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/note-index/$note->reference_id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'owner_id' => $note->owner_id,
                    'recipients' => '',
                ];
            }
            ViewHelper::adaptCheckbox($fields, 'withEmail');
            if ($request->btnSubmit === 'btnShift' && ($owner = $fields['owner_id']) != null) {
                $note->update(['owner_id' => $fields['owner_id']]);
                if ($fields['withEmail']) {
                    $this->sendEmail($note->owner_id, $note);
                }
            } elseif ($request->btnSubmit === 'btnCopy' && ($recipients = $fields['recipients']) != null) {
                if (($group = Group::find($recipients)) != null) {
                    $ids = explode(',', $group->members);
                    foreach ($ids as $id) {
                        if (($id = intval($id)) != 0 && $id != $note->owner_id) {
                            Note::create([
                                'title' => $note->title,
                                'body' => $note->body,
                                'category_scope' => $note->category_scope,
                                'visibility_scope' => $note->visibility_scope,
                                'notestatus_scope' => $note->notestatus_scope,
                                'owner_id' => $id
                            ]);
                            if ($fields['withEmail']) {
                                $this->sendEmail($id, $note);
                            }
                        }
                    }
                }
            }
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->owner_id, __('<Please select>'));
            $optionsRecipients = DbHelper::comboboxDataOfTable('groups', 'name', 'id', $fields['recipients'], __('<Please select>'));
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('note-edit', 3, $note->id, $note->options, $note->reference_id);
            $context = new ContextLaraKnife($request, null, $note);
            $rc = view('note.edit_shift', [
                'context' => $context,
                'optionsOwner' => $optionsOwner,
                'optionsRecipients' => $optionsRecipients,
                'navTabsInfo' => $navigationTabInfo
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
            if ($note->visibility_scope != 1092) {
                Change::createFromModel($note, Change::$DELETE, 'Note');
            }
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
SELECT t0.*, cast(t0.body AS VARCHAR(40)) as body_short, t1.name as category, t2.name as notestatus, 
  t3.name as owner, (select count(id) from files t5 where t5.reference_id=t0.id) as filecount 
FROM notes t0
LEFT JOIN sproperties t1 ON t1.id=t0.category_scope
LEFT JOIN sproperties t2 ON t2.id=t0.notestatus_scope
LEFT JOIN users t3 ON t3.id=t0.owner_id
LEFT JOIN sproperties t4 ON t4.id=t0.visibility_scope
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'category' => '1051',
                    'notestatus' => '1011',
                    'visibility' => '1091',
                    'owner' => null,
                    'filename' => '',
                    'body' => '',
                    '_sortParams' => 'id:asc;title:desc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'category_scope', 'category');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'notestatus_scope', 'notestatus');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'owner_id', 'user');
            ViewHelper::addConditionPattern($conditions, $parameters, 'title,body', 'text');
            ViewHelper::addConditionPattern($conditions, $parameters, 'title');
            ViewHelper::addConditionPattern($conditions, $parameters, 'body');
            ViewHelper::addConditionVisible($conditions, $fields['visibility']);
            if ( ($fn = $fields['filename']) != null && $fn !== ''){
                $fn = '%' . strip_tags($fn) . '%';
                $fn = str_replace('*', '%', $fn);
                $fn = str_replace('%%', '%', $fn);
                ViewHelper::addConditionRawSql($conditions,
                    "t0.id in (select reference_id from files t5 where t5.reference_id=t0.id and (t5.title like '$fn' or t5.description like '$fn' or t5.filename like '$fn'))",
                    []);
            }
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsCategory = SProperty::optionsByScope('category', $fields['category'], 'all');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $fields['notestatus'], 'all');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility'], 'all');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner']);
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
SELECT t0.*, t1.name as filegroup, t2.name as user_id 
FROM files t0
LEFT JOIN sproperties t1 ON t1.id=t0.filegroup_scope
LEFT JOIN sproperties t2 ON t2.id=t0.user_id
";
            $parameters = [];
            $conditions = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'owner' => auth()->id(),
                    'text' => '',
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
            $context = new ContextLaraKnife($request, $fields);
            $fileController = new FileController();
            $context->setCallback('buildAnchor', $fileController, 'buildAnchor');
            $navTabInfo = ViewHelperLocal::getNavigationTabInfo('note-edit', 2, $note->id, $note->options, $note->reference_id);
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
            'body' => '',
            'category_scope' => 'required',
            'notestatus_scope' => 'required',
            'visibility_scope' => 'required',
            'owner_id' => 'required'
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
        Route::post('/note-edit_shift/{note}', [NoteController::class, 'editShift'])->middleware('auth');
        Route::get('/note-edit_shift/{note}', [NoteController::class, 'editShift'])->middleware('auth');
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
        TaskController::routes();
    }
    private function sendEmail(int $userId, Note $note)
    {
        $user = User::find($userId);
        EmailHelper::sendMail('note.notification', $user->email, [
            'name' => $user->name,
            'title' => $note->title,
            'contents' => $note->body,
            'from' => auth()->user()->name,
            'link' => ViewHelper::buildLink("/note-edit/$note->id")
        ]);
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
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->owner_id);
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
                $note = Note::create($validated);
                if ($note->visibility_scope != 1092) {
                    Change::createFromFields($validated, Change::$CREATE, 'Note', $note->id);
                }
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
            ViewHelper::addFieldIfMissing($fields, 'owner_id', auth()->id());
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $error = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['body'] = strip_tags($fields['body']);
                $note->update($validated);
                if ($note->visibility_scope != 1092) {
                    Change::createFromFields($validated, Change::$UPDATE, 'Note', $note->id);
                }
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
                Change::createFromFields($fields2, Change::$UPDATE, 'File', $file->id);
            }
        }
        if ($rc == null) {
            $rc = redirect("/note-index_documents/$file->reference_id");
        }
        return $rc;
    }
}
