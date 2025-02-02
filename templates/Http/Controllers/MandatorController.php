<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Helpers\Helper;
use App\Models\Mandator;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MandatorController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/mandator-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'info' => '',
                    'group_id' => ''
                ];
            }
            $optionsGroup = DbHelper::comboboxDataOfTable('groups', 'name', 'id', $fields['group_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('mandator.create', [
                'context' => $context,
                'optionsGroup' => $optionsGroup,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mandator $mandator, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/mandator-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => $mandator->name,
                    'info' => $mandator->info,
                    'group_id' => $mandator->group_id
                ];
            }
            $optionsGroup = DbHelper::comboboxDataOfTable('groups', 'name', 'id', $fields['group_id'], __('<Please select>'));
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('mandator-edit', 1, $mandator->id);
            $context = new ContextLaraKnife($request, null, $mandator);
            $rc = view('mandator.edit', [
                'context' => $context,
                'optionsGroup' => $optionsGroup,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mandator $mandator, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $mandator = $mandator->delete();
            Change::createFromModel($mandator, Change::$DELETE, 'notes');
        }
        return redirect('/mandator-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/mandator-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as `group`
FROM mandators t0
LEFT JOIN groups t1 ON t1.id=t0.group_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'text' => '',
                    '_sortParams' => 'name:asc;id:asc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionPattern($fields, $conditions, $parameters, 't0.name,t0.info', 'text');
            ViewHelper::addConditionFindInList($conditions, $parameters, 't1.members', strval(auth()->user()->id));
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $context = new ContextLaraKnife($request, $fields);
            return view('mandator.index', [
                'context' => $context,
                'records' => $records,
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
        $rc = [
            'name' => 'required',
            'info' => '',
            'group_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/mandator-index', [MandatorController::class, 'index'])->middleware('auth');
        Route::post('/mandator-index', [MandatorController::class, 'index'])->middleware('auth');
        Route::get('/mandator-create', [MandatorController::class, 'create'])->middleware('auth');
        Route::put('/mandator-store', [MandatorController::class, 'store'])->middleware('auth');
        Route::post('/mandator-edit/{mandator}', [MandatorController::class, 'edit'])->middleware('auth');
        Route::get('/mandator-edit/{mandator}', [MandatorController::class, 'edit'])->middleware('auth');
        Route::post('/mandator-update/{mandator}', [MandatorController::class, 'update'])->middleware('auth');
        Route::get('/mandator-show/{mandator}/delete', [MandatorController::class, 'show'])->middleware('auth');
        Route::delete('/mandator-show/{mandator}/delete', [MandatorController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Mandator $mandator, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/mandator-index')->middleware('auth');
        } else {
            $fields = $request->all();
            $optionsGroup = DbHelper::comboboxDataOfTable('groups', 'name', 'id', $fields['group_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $mandator);
            $rc = view('mandator.show', [
                'context' => $context,
                'optionsGroup' => $optionsGroup,
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
                $validated['info'] = strip_tags($validated['info']);
                $mandator = Mandator::create($validated);
                Change::createFromFields($validated, Change::$CREATE, 'Mandator', $mandator->id);
            }
        }
        if ($rc == null) {
            $rc = redirect('/mandator-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Mandator $mandator, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $mandator->update($validated);
                Change::createFromFields($validated, Change::$UPDATE, 'Mandator', $mandator->id);
            }
        }
        if ($rc == null) {
            $rc = redirect('/mandator-index');
        }
        return $rc;
    }
}
