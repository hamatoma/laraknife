<?php

namespace App\Http\Controllers;

use App\Helpers\ContextLaraKnife;
use App\Models\User;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['name' => '', 'email' => '', 'password' => '', 'password_confirmation' => '', 'role_id' => ''];
            }
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('user.create', ['context' => $context, 'roleOptions' => $options]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/user-index');
        } elseif ($request->btnSubmit === 'btnSetPassword') {
            $rc = redirect('/user-editpassword/' . strval($user->id));
        } else {
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $user->role_id, '');
            $context = new ContextLaraKnife($request, null, $user);
            $rc = view('user.edit', ['context' => $context, 'roleOptions' => $options]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function editPassword(User $user, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $rc = null;
            $error = null;
            $fields = $request->all();
            if (count($fields) > 0) {
                try {
                    unset($_POST['name']);
                    $incomingFields = $request->validate([
                        'password' => 'required|confirmed',
                        'password_confirmation' => 'required'
                    ]);
                    $user->update($incomingFields);
                    $rc = redirect('/user-index');
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
            if ($rc == null) {
                $rc = view('user.changepw', ['user' => $user]);
            }
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $user->delete();
        }
        return redirect('/user-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            $rc = redirect('/user-create');
        } else {
            $sql = 'SELECT t0.*, t1.name as role FROM users t0 LEFT JOIN roles t1 on t0.role_id=t1.id ';
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['id' => '', 'text' => '', 'role' => '0', '_sortParams' => 'id:asc'];
            } else {
                $conditions = [];
                $parameters = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 't0.role_id', 'role');
                ViewHelper::addConditionComparism($conditions, $parameters, 'role_id');
                ViewHelper::addConditionPattern($conditions, $parameters, 't0.name,email', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role']);
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('user.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'roleOptions' => $options
            ]);
        }
        return $rc;
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreation = false, ?User $user = null): array
    {
        if ($isCreation) {
            $rc = [
                'name' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'role_id' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ];
        } else {
            $rc = [
                'name' => ['required', Rule::unique('users')->ignore($user)],
                'email' => 'required|unique:users,id,' . strval($user->id),
                'role_id' => 'required'
            ];
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/user-index', [UserController::class, 'index']);
        Route::post('/user-index', [UserController::class, 'index']);
        Route::get('/user-create', [UserController::class, 'create']);
        Route::post('/user-create', [UserController::class, 'create']);
        Route::put('/user-store', [UserController::class, 'store']);
        Route::get('/user-edit/{user}', [UserController::class, 'edit']);
        Route::post('/user-edit/{user}', [UserController::class, 'edit']);
        Route::post('/user-update/{user}', [UserController::class, 'update']);
        Route::get('/user-editpassword/{user}', [UserController::class, 'editPassword']);
        Route::post('/user-editpassword/{user}', [UserController::class, 'editPassword']);
        Route::get('/user-show/{user}/delete', [UserController::class, 'show']);
        Route::delete('/user-show/{user}/delete', [UserController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $user->role_id, '');
            $context = new ContextLaraKnife($request, null, $user);
            $rc = view('user.show', ['context' => $context, 'mode' => 'delete', 'roleOptions' => $options]);
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
                User::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/user-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false, $user));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $user->update($validator->validated());
            }
        }
        if ($rc == null) {
            $rc = redirect('/user-index');
        }
        return $rc;
    }
}
