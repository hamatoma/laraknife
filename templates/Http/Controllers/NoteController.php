<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Note;
use App\Models\SProperty;
use App\Helpers\ContextLaraKnife;
use App\Helpers\ViewHelper;
use App\Helpers\DbHelper;
use App\Helpers\Helper;
use App\Helpers\Pagination;

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
                    'user_id' => strval(auth()->id())
                ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $fields['category_scope'], '-');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $fields['notestatus_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);     
            $rc = view('note.create', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsUser' => $optionsUser,
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
                    'user_id' => strval(auth()->id())
                    ];
            }
            $optionsCategory = SProperty::optionsByScope('category', $note->category_scope, '');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $note->notestatus_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->user_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $note);
            $rc = view('note.edit', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
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
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/note-create');
        } else {
            $sql = 'SELECT t0.*, cast(t0.body AS VARCHAR(40)) as body_short, t1.name as category_scope, t2.name as notestatus_scope, t3.name as user_id '
                . ' FROM notes t0'
                . ' LEFT JOIN sproperties t1 ON t1.id=t0.category_scope'
                . ' LEFT JOIN sproperties t2 ON t2.id=t0.notestatus_scope'
                . ' LEFT JOIN sproperties t3 ON t3.id=t0.user_id'
                ;
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                'category' => '1051',
                'notestatus' => '1011',
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
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user']);
            $context = new ContextLaraKnife($request, $fields);
            return view('note.index', [
                'context' => $context,
                'records' => $records,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
                'optionsUser' => $optionsUser,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate=false): array
    {
        $rc = [
            'title' => 'required',
            'body' => 'required',
            'category_scope' => 'required',
            'notestatus_scope' => 'required',
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
        Route::post('/note-update/{note}', [NoteController::class, 'update'])->middleware('auth');
        Route::get('/note-show/{note}/delete', [NoteController::class, 'show'])->middleware('auth');
        Route::delete('/note-show/{note}/delete', [NoteController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Note $note, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/note-index')->middleware('auth');
        } else {
            $optionsCategory = SProperty::optionsByScope('category', $note->category_scope, '');
            $optionsNotestatus = SProperty::optionsByScope('notestatus', $note->notestatus_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $note->user_id);
            $context = new ContextLaraKnife($request, null, $note);
            $rc = view('note.show', [
                'context' => $context,
                'optionsCategory' => $optionsCategory,
                'optionsNotestatus' => $optionsNotestatus,
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
            $validated['body'] = strip_tags($validated['body']);
                Note::create($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/note-index');
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
                $validated['body'] = strip_tags($validated['body']);
                $note->update($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/note-index');
        }
        return $rc;
    }
}
