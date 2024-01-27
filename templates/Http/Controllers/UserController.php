<?php

namespace App\Http\Controllers;

use App\Models\SProperty;
use App\Models\User;
use App\Helpers\ViewHelper;
use App\Helpers\DbHelper;
use App\Helpers\Pagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $rc = null;
            $error = null;
            if (count($_POST) > 0) {
                $fields = $_POST;
                try {
                    $incomingFields = $request->validate($this->rules(true));
                    $rc = $this->store($request);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                $fields = ['name' => '', 'email' => '', 'password' => '', 'password_confirmation' => '', 'role_id' => ''];
            }
            if ($rc == null) {
                $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role_id'], __('<Please select>'));
                $rc = view('user.create', ['fields' => $fields, 'roleOptions' => $options, 'error' => $error]);
            }
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Request $request)
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnCancel') {
            $rc = redirect('/user-index');
        } elseif (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnSetPassword') {
            $rc = redirect('/user-editpassword/' . strval($user->id));
        } else {
            $rc = null;
            $error = null;
            if (count($_POST) > 0) {
                $fields = $_POST;
                try {
                    $incomingFields = $request->validate($this->rules(false));
                    $rc = $this->update($user, $request);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
            if ($rc == null) {
                $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $$user->role_id ?? '', '');
                $rc = view('user.edit', ['user' => $user, 'roleOptions' => $options, 'error' => $error]);
            }
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function editPassword(User $user, Request $request)
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $rc = null;
            $error = null;
            if (count($_POST) > 0) {
                $fields = $_POST;
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
                $rc = view('user.changepw', ['user' => $user, 'error' => $error]);
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
    public function index()
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnNew') {
            $rc = redirect('/user-create');
        } else {
            $sql = 'SELECT t0.*, t1.name as role FROM users t0 LEFT JOIN roles t1 on t0.role_id=t1.id ';
            $parameters = [];
            if (count($_POST) == 0) {
                $fields = ['id' => '', 'text' => '', 'role' => '0', '_sortParams' => 'id:asc'];
            } else {
                $fields = $_POST;
                $conditions = [];
                $parameters = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 't0.role_id', 'role');
                ViewHelper::addConditionComparism($conditions, $parameters, 'role_id');
                ViewHelper::addConditionPattern($conditions, $parameters, 'name,email', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role']);
            $rc = view('user.index', [
                'records' => $records,
                'fields' => $fields,
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
    private function rules(bool $isCreation = false): array
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
                'name' => 'required',
                'email' => 'required|email',
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
        Route::put('/user-create', [UserController::class, 'store']);
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
    public function show(User $user)
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] === 'btnCancel') {
            $rc = redirect('/user-index');
        } else {
            $options = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $user->role_id, '');
            $rc = view('user.show', ['user' => $user, 'mode' => 'delete', 'roleOptions' => $options]);
        }
        return $rc;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->btnSubmit === 'btnStore') {
            $incomingFields = $request->validate($this->rules(true));
            User::create($incomingFields);
        }
        return redirect('/user-index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            try {
                $incomingFields = $request->validate($this->rules(false));
                $user->update($incomingFields);
            } catch (\Exception $exc) {
                $msg = $exc->getMessage();
                $rc = back();
            }
        }
        if ($rc == null) {
            $rc = redirect('/user-index');
        }
        return $rc;
    }
}
