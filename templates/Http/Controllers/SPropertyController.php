<?php

namespace App\Http\Controllers;

use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class SPropertyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rc = null;
        $error = null;
        $fields = $request->all();
        if (count($fields) === 0) {
            $fields = [
                'id' => '',
                'scope' => '',
                'name' => '',
                'shortname' => '',
                'order' => '10',
                'value' => '',
                'info' => ''
            ];
        }
        $context = new ContextLaraKnife($request, $fields);
        $rc = view('sproperty.create', ['context' => $context]);
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SProperty $sproperty, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/sproperty-index');
        } else {
            $context = new ContextLaraKnife($request, null, $sproperty);
            $rc = view('sproperty.edit', ['context' => $context]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SProperty $sproperty, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $sproperty->delete();
        }
        return redirect('/sproperty-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            $rc = redirect('/sproperty-create');
        } else {
            $sql = 'SELECT * FROM sproperties';
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['scope' => '', 'text' => '', '_sortParams' => 'scope:asc;order:asc;name:asc'];
            } else {
                $conditions = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'scope');
                ViewHelper::addConditionPattern($conditions, $parameters, 'scope,name,shortname,value,info', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $scopes = SProperty::scopes();
            $scopeOptions = ViewHelper::buildEntriesOfCombobox(
                $scopes,
                null,
                isset($fields['scope']) ? $fields['scope'] : '',
                '<All>',
                true
            );
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('sproperty.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'options' => $scopeOptions
            ]);
        }
        return $rc;
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate = false, ?SProperty $sproperty = null): array
    {
        $rc = [
            'scope' => 'required|alpha_num',
            'name' => 'required|alpha_num',
            'order' => 'integer|min:1|max:9999',
            'shortname' => 'required|alpha_num',
            'value' => 'nullable',
            'info' => 'nullable'
        ];
        if ($isCreate) {
            $rc['id'] = ['required', 'min:1', 'max:100000', Rule::unique('sproperties')->ignore($sproperty)];
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/sproperty-index', [SPropertyController::class, 'index']);
        Route::post('/sproperty-index', [SPropertyController::class, 'index']);
        Route::get('/sproperty-create', [SPropertyController::class, 'create']);
        Route::put('/sproperty-store', [SPropertyController::class, 'store']);
        Route::get('/sproperty-edit/{sproperty}', [SPropertyController::class, 'edit']);
        Route::put('/sproperty-store', [SPropertyController::class, 'store']);
        Route::post('/sproperty-update/{sproperty}', [SPropertyController::class, 'update']);
        Route::get('/sproperty-show/{sproperty}/delete', [SPropertyController::class, 'show']);
        Route::delete('/sproperty-show/{sproperty}/delete', [SPropertyController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(SProperty $sproperty, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/sproperty-index');
        } else {
            $rc = view('sproperty.show', ['sproperty' => $sproperty, 'mode' => 'delete']);
        }
        return $rc;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                SProperty::create($validated);
            }
        }
        return redirect('/sproperty-index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(SProperty $sproperty, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false, $sproperty));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $sproperty->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/sproperty-index');
        }
        return $rc;
    }
}
