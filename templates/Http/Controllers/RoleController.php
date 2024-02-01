<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Hamatoma\Laraknife\ViewHelpers;
use App\Models\Role;
use App\Models\SProperty;
use App\Helpers\DbHelper;
use App\Helpers\ViewHelper;
use App\Helpers\Pagination;


class RoleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rc = null;
        $error = null;
        $fields = $request->all();
        if (count($fields) > 0) {
            try {
                $incomingFields = $request->validate($this->rules());
                $rc = $this->store($request);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $fields = ['name' => '', 'priority' => ''];
        }
        if ($rc == null) {
            $rc = view('role.create', ['context' => $context, 'error' => $error]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('role.edit', ['role' => $role]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect('/role-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/role-create');
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
            } else {
                $conditions = [];
                ViewHelper::addConditionPattern($conditions, $parameters, 'name', 'name');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            return view('role.index', [
                'records' => $records,
                'context' => $context,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(): array
    {
        $rc = [
            'name' => 'required|alpha_num',
            'priority' => 'integer|min:1|max:9999',
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/role-index', [RoleController::class, 'index']);
        Route::post('/role-index', [RoleController::class, 'index']);
        Route::get('/role-create', [RoleController::class, 'create']);
        Route::post('/role-create', [RoleController::class, 'create']);
        Route::put('/role-create', [RoleController::class, 'store']);
        Route::get('/role-edit/{role}', [RoleController::class, 'edit']);
        Route::post('/role-update/{role}', [RoleController::class, 'update']);
        Route::get('/role-show/{role}/delete', [RoleController::class, 'show']);
        Route::delete('/role-show/{role}/delete', [RoleController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('role.show', ['role' => $role, 'mode' => 'delete']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->btnSubmit === 'btnStore') {
            $incomingFields = $request->validate($this->rules());
            Role::create($incomingFields);
        }
        return redirect('/role-index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, Request $request)
    {
        if ($request->btnSubmit === 'btnStore') {
            $incomingFields = $request->validate($this->rules());
            $role->update($incomingFields);
        }
        return redirect('/role-index');
    }
}
