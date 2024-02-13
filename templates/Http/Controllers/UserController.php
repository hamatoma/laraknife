<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
     * Shows the form for changing the password for the current user.
     */
    public function editCurrentUser(Request $request){
        $user = User::find(auth()->id());
        $rc = $this->editPassword($user, $request, '/menuitem-menu_main');
        return $rc;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function editPassword(User $user, Request $request, ?string $redirect=null)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect($redirect ?? '/menuitem-menu_main');
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
                    $rc = redirect($redirect ?? '/user-index');
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
    public function login(Request $request)
    {
        $rc = null;
        $fields = $request->all();
        if (count($fields) === 0) {
            $fields = ['email' => '', 'password' => ''];
        } else {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $rc = redirect('/menuitem-menu_main');
            } else {
                $rc = back()->withErrors([
                    'email' => __('The provided credentials do not match our records.'),
                ])->onlyInput('email');
            }
        }
        if ($rc == null) {
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('user.login', ['context' => $context]);
        }
        return $rc;
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/user-login');
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
        Route::get('/user-index', [UserController::class, 'index'])->middleware('auth');
        Route::post('/user-index', [UserController::class, 'index'])->middleware('auth');
        Route::get('/user-create', [UserController::class, 'create'])->middleware('auth');
        Route::post('/user-create', [UserController::class, 'create'])->middleware('auth');
        Route::put('/user-store', [UserController::class, 'store'])->middleware('auth');
        Route::get('/user-edit/{user}', [UserController::class, 'edit'])->middleware('auth');
        Route::post('/user-edit/{user}', [UserController::class, 'edit'])->middleware('auth');
        Route::get('/user-edit-current', [UserController::class, 'editCurrentUser'])->middleware('auth');
        Route::post('/user-edit-current', [UserController::class, 'editCurrentUser'])->middleware('auth');
        Route::post('/user-update/{user}', [UserController::class, 'update'])->middleware('auth');
        Route::get('/user-editpassword/{user}', [UserController::class, 'editPassword']);
        Route::post('/user-editpassword/{user}', [UserController::class, 'editPassword']);
        Route::get('/user-show/{user}/delete', [UserController::class, 'show'])->middleware('auth');
        Route::delete('/user-show/{user}/delete', [UserController::class, 'destroy'])->middleware('auth');
        Route::get('/user-login', [UserController::class, 'login']);
        Route::post('/user-login', [UserController::class, 'login']);
        Route::get('/login', [UserController::class, 'login'])->name('login');;
        Route::post('/login', [UserController::class, 'login'])->name('login');;
        Route::get('/user-logout', [UserController::class, 'logout']);
        Route::post('/user-logout', [UserController::class, 'logout']);
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
        if ($request->btnSubmit === 'btnSetPassword') {
            $rc = $rc = redirect('/user-editpassword/' . strval($user->id));
        } elseif ($request->btnSubmit === 'btnStore') {
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
