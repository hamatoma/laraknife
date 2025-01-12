<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/role-index');
        } else {
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['name' => '', 'priority' => ''];
            }
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('role.create', ['context' => $context]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/role-index');
        } else {
            $context = new ContextLaraKnife($request, null, $role);
            $rc = view('role.edit', ['context' => $context]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $role->delete();
        }
        return redirect('/role-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            $rc = redirect('/role-create');
        } else {
            $sql = 'SELECT * FROM roles';
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'name' => '',
                    'priority' => '',
                    '_sortParams' => 'name:asc;priority:asc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionPattern($fields, $conditions, $parameters, 'name', 'name');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('role.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination
            ]);
        }
        return $rc;
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreation = false): array
    {
        $rc = [
            'name' => 'required|alpha_num',
            'priority' => 'integer|min:1|max:9999',
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/role-index', [RoleController::class, 'index'])->middleware('auth');
        Route::post('/role-index', [RoleController::class, 'index'])->middleware('auth');
        Route::get('/role-create', [RoleController::class, 'create'])->middleware('auth');
        Route::post('/role-create', [RoleController::class, 'create'])->middleware('auth');
        Route::put('/role-store', [RoleController::class, 'store'])->middleware('auth');
        Route::get('/role-edit/{role}', [RoleController::class, 'edit'])->middleware('auth');
        Route::post('/role-update/{role}', [RoleController::class, 'update'])->middleware('auth');
        Route::get('/role-show/{role}/delete', [RoleController::class, 'show'])->middleware('auth');
        Route::delete('/role-show/{role}/delete', [RoleController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Role $role, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/role-index');
        } else {
            $context = new ContextLaraKnife($request, null, $role);
            $rc = view('role.show', ['context' => $context, 'mode' => 'delete']);
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
                // Retrieve the validated input...
                $validated = $validator->validated();
                Role::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/role-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules());
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $role->update($validator->validated());
            }
        }
        if ($rc == null) {
            $rc = redirect('/role-index');
        }
        return $rc;
    }
}
