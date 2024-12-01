<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Term;
use App\Models\SProperty;
use App\Helpers\ContextLaraKnife;
use App\Helpers\ViewHelper;
use App\Helpers\DbHelper;
use App\Helpers\Helper;
use App\Helpers\Pagination;

class TermController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/term-index');
        } else {
            $fields = $request->all();
            ViewHelper::adaptFieldValues($fields, ['term' => 'datetime']);
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'term' => (new \DateTime())->format('Y-m-d H:00'),
                    'duration' => '0',
                    'description' => '',
                    'visibility_scope' => '1091',
                    'owner_id' => auth()->id()
                ];
            }
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);     
            $rc = view('term.create', [
                'context' => $context,
                'optionsVisibility' => $optionsVisibility,
                'optionsOwner' => $optionsOwner,
                ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/term-index');
        } else {
            $fields = $request->all();
            ViewHelper::adaptFieldValues($fields, ['term' => 'datetime']);
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'term' => (new \DateTime())->format('Y-m-d H:i'),
                    'duration' => '0',
                    'description' => '',
                    'visibility_scope' => '',
                    'owner_id' => ''
                    ];
            }
            $optionsVisibility = SProperty::optionsByScope('visibility', $term->visibility_scope, '');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $term->owner_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $term);
            $rc = view('term.edit', [
                'context' => $context,
                'optionsVisibility' => $optionsVisibility,
                'optionsOwner' => $optionsOwner,
                ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $term->delete();
        }
        return redirect('/term-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/term-create');
        } else {
            ViewHelper::adaptFieldValues($_POST, ['from' => 'datetime', 'to' => 'datetime']);
            $sql = 'SELECT t0.*,'
                . 't1.name as visibility, '
                . 't2.name as owner '
                . ' FROM terms t0'
                . ' LEFT JOIN sproperties t1 ON t1.id=t0.visibility_scope'
                . ' LEFT JOIN users t2 ON t2.id=t0.owner_id'
                ;
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                'visibility' => '',
                'owner' => '',
                'title' => '',
                'from' => '',
                'to' => '',
                'text' => '',
                '_sortParams' => 'term:desc;duration:desc;title:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'visibility_scope', 'visibility');
                ViewHelper::addConditionComparism($conditions, $parameters, 'owner_id', 'owner');
                ViewHelper::addConditionPattern($conditions, $parameters, 'title,description', 'text');
                ViewHelper::addConditionDateTimeRange($conditions, $parameters, 'from', 'to', 'term');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility'], 'all');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner']);
            $context = new ContextLaraKnife($request, $fields);
            return view('term.index', [
                'context' => $context,
                'records' => $records,
                'optionsVisibility' => $optionsVisibility,
                'optionsOwner' => $optionsOwner,
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
            'term' => 'required',
            'duration' => 'required|integer|min:0|max:1440',
            'description' => '',
            'visibility_scope' => 'required',
            'owner_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/term-index', [TermController::class, 'index'])->middleware('auth');
        Route::post('/term-index', [TermController::class, 'index'])->middleware('auth');
        Route::get('/term-create', [TermController::class, 'create'])->middleware('auth');
        Route::put('/term-store', [TermController::class, 'store'])->middleware('auth');
        Route::post('/term-edit/{term}', [TermController::class, 'edit'])->middleware('auth');
        Route::get('/term-edit/{term}', [TermController::class, 'edit'])->middleware('auth');
        Route::post('/term-update/{term}', [TermController::class, 'update'])->middleware('auth');
        Route::get('/term-show/{term}/delete', [TermController::class, 'show'])->middleware('auth');
        Route::delete('/term-show/{term}/delete', [TermController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Term $term, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/term-index')->middleware('auth');
        } else {
            $optionsVisibility = SProperty::optionsByScope('visibility', $term->visibility_scope, '');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $term->owner_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $term);
            $rc = view('term.show', [
                'context' => $context,
                'optionsVisibility' => $optionsVisibility,
                'optionsOwner' => $optionsOwner,
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
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
            $validated['description'] = strip_tags($validated['description']);
                Term::create($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/term-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Term $term, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['description'] = strip_tags($validated['description']);
                $term->update($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/term-index');
        }
        return $rc;
    }
}
