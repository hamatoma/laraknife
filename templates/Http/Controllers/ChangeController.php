<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Change;
use App\Models\SProperty;
use App\Helpers\ContextLaraKnife;
use App\Helpers\ViewHelper;
use App\Helpers\DbHelper;
use App\Helpers\Helper;
use App\Helpers\Pagination;

class ChangeController extends Controller
{
    public function delete(Change $change, Request $request)
    {
        $rc = $this->showInternal($change, 'delete', $request);
        return $rc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Change $change, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $change->delete();
        }
        return redirect('/change-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/change-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as changetype,
  t2.name as module,
  t3.name as user
FROM changes t0
LEFT JOIN sproperties t1 ON t1.id=t0.changetype_scope
LEFT JOIN modules t2 ON t2.id=t0.module_id
LEFT JOIN users t3 ON t3.id=t0.user_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'changetype' => '',
                    'module' => '',
                    'reference' => '',
                    'user' => '',
                    'text' => '',
                    '_sortParams' => 'created_at:desc;changetype_scope:asc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'changetype_scope', 'changetype');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'module_id', 'module');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'reference_id', 'reference');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'user_id', 'user');
            ViewHelper::addConditionPattern($conditions, $parameters, 'description,current', 'text');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsChangetype = SProperty::optionsByScope('changetype', $fields['changetype'], 'all');
            $optionsModule = DbHelper::comboboxDataOfTable('modules', 'name', 'id', $fields['module'], __('all'));
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['user'], __('all'));
            $context = new ContextLaraKnife($request, $fields);
            return view('change.index', [
                'context' => $context,
                'records' => $records,
                'optionsChangetype' => $optionsChangetype,
                'optionsModule' => $optionsModule,
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
        $rc = [
            'changetype_scope' => 'required',
            'module_id' => 'required',
            'reference_id' => 'required',
            'description' => 'required',
            'current' => 'required',
            'link' => 'required',
            'user_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/change-index', [ChangeController::class, 'index'])->middleware('auth');
        Route::post('/change-index', [ChangeController::class, 'index'])->middleware('auth');
        Route::get('/change-edit/{change}', [ChangeController::class, 'show'])->middleware('auth');
        Route::post('/change-edit/{change}', [ChangeController::class, 'show'])->middleware('auth');
        Route::get('/change-edit/{change}/{mode}', [ChangeController::class, 'showInternal'])->middleware('auth');
        Route::post('/change-edit/{change}/{mode}', [ChangeController::class, 'showInternal'])->middleware('auth');
        Route::get('/change-show/{change}/delete', [ChangeController::class, 'delete'])->middleware('auth');
        Route::delete('/change-show/{change}/delete', [ChangeController::class, 'destroy'])->middleware('auth');
        Route::get('/change-show/{change}/show', [ChangeController::class, 'show'])->middleware('auth');
        Route::post('/change-show/{change}/show', [ChangeController::class, 'show'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Change $change, Request $request)
    {
        $rc = $this->showInternal($change, 'show', $request);
        return $rc;
    }

    /**
     * Display the specified resource.
     */
    public function showInternal(Change $change, string $mode, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/change-index');
        } else {
            $optionsChangetype = SProperty::optionsByScope('changetype', $change->changetype_scope, '');
            $optionsModule = DbHelper::comboboxDataOfTable('modules', 'name', 'id', $change->module_id, __('<Please select>'));
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $change->user_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $change);
            $rc = view('change.show', [
                'context' => $context,
                'optionsChangetype' => $optionsChangetype,
                'optionsModule' => $optionsModule,
                'optionsUser' => $optionsUser,
                'mode' => $mode
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
                $validated['current'] = strip_tags($validated['current']);
                Change::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/change-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Change $change, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['current'] = strip_tags($validated['current']);
                $change->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/change-index');
        }
        return $rc;
    }
}
