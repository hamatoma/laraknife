<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    /**
     * Handles the link sent by email (forgotten password).
     * @param Request $request the request data
     */
    public function answer(Request $request)
    {
        $rc = null;
        $token = $request->get('token');
        if ($token !== null) {
            if (($user = User::where('remember_token', $token)->first()) != null) {
                $updated = $user->updated_at;
                $diff = (new Carbon())->diffInHours($updated);
                if ($diff <= 6) {
                    auth()->login($user);
                    $role = Role::find($user->role_id);
                    $request->session()->regenerate();
                    session(['role' => $role->priority, 'userName' => $user->name]);
                    $date = (new \DateTime())->format('y-m-d H:i');
                    DB::update(
                        "UPDATE users SET remember_token=NULL, updated_at='$date' WHERE id=?",
                        [$user->id]
                    );
                    $rc = $rc = redirect('/user-edit-current');
                }
            }
        }
        if ($rc == null) {
            $rc = redirect('/user-login');
        }
        return $rc;
    }

    protected function buildForgottenLink(string $email): ?string
    {
        $rc = null;
        if (($user = User::where('email', $email)->first()) != null) {
            $hash = Hash::make($email . strval(time()) . strval(rand(0, 0x7fffffff)));
            $user->update(['remember_token' => $hash]);
            $date = (new \DateTime())->format('y-m-d H:i');
            DB::update(
                "UPDATE users SET remember_token=?, updated_at='$date' WHERE id=?",
                [$hash, $user->id]
            );
            $rc = env('APP_URL', '') . "/user-answer?token=$hash";
        }
        return $rc;
    }
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
                $fields = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'password_confirmation' => '',
                    'role_id' => '',
                    'localization' => 'en_GB'
                ];
            }
            $roleOptions = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role_id'], __('<Please select>'));
            $localizationOptions = SProperty::optionsByScope('localization', $fields['localization'], '', 'name', 'shortname');

            $context = new ContextLaraKnife($request, $fields);
            $rc = view('user.create', [
                'context' => $context,
                'roleOptions' => $roleOptions,
                'localizationOptions' => $localizationOptions
            ]);
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
            $roleOptions = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $user->role_id, '');
            $localizationOptions = SProperty::optionsByScope('localization', $user->localization, '', 'name', 'shortname');
            $context = new ContextLaraKnife($request, null, $user);
            $rc = view('user.edit', [
                'context' => $context,
                'roleOptions' => $roleOptions,
                'localizationOptions' => $localizationOptions
            ]);
        }
        return $rc;
    }
    /**
     * Shows the form for changing the password for the current user.
     */
    public function editCurrentUser(Request $request)
    {
        $user = User::find(auth()->id());
        $rc = $this->editPassword($user, $request, '/menuitem-menu_main');
        return $rc;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function editPassword(User $user, Request $request, ?string $redirect = null)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect($redirect ?? '/menuitem-menu_main');
        } else {
            $rc = null;
            $error = null;
            $fields = $request->all();
            if (count($fields) > 0) {
                unset($_POST['name']);
                $validator = Validator::make($fields, [
                    'password' => 'required|confirmed',
                    'password_confirmation' => 'required'
                ]);
                if ($validator->failed()) {
                    $rc = back()->withErrors($validator)->withInput();
                } else {
                    $email = strtolower($user->email);
                    $pw = $fields['password'];
                    $hash = UserController::hash($email, $pw);
                    $user->update(['email' => $email, 'password' => $hash]);
                    $rc = redirect($redirect ?? '/user-index');
                }
            }
            if ($rc == null) {
                $examples = StringHelper::createPassword() . "<br/>\n" . StringHelper::createPassword()
                    . "<br>\n" . StringHelper::createPassword();
                $rc = view('user.changepw', ['user' => $user, 'examples' => $examples]);
            }
        }
        return $rc;
    }
    public function forgotten(Request $request)
    {
        $rc = null;
        $message = null;
        $fields = $request->all();
        if ($request->btnSubmit === 'btnSend') {
            $validator = Validator::make($fields, [
                'email' => 'required|email'
            ]);
            if ($validator->failed()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $email = $fields['email'];
                if (($link = $this->buildForgottenLink($email)) != null) {
                    EmailHelper::sendMail('user.forgotten', $email, ['link' => $link]);
                }
                $message = __('Email has been sent if the email address is known.');
            }
        } elseif (count($fields)) {
            $fields = [
                'email' => ''
            ];
        }
        if ($rc == null) {
            $context = new ContextLaraKnife($request, $fields);
            if ($message != null) {
                $context->setSnippet('msg', $message);
            }
            $rc = view('user.forgotten', ['context' => $context]);
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
    public static function hash(string $email, string $password)
    {
        $email = strtolower($email);
        $rc = Hash::make("$email\t$password");
        return $rc;
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
        if (($rc = $this->loginAutomatic($request)) == null) {
            $rc = null;
            $userId = null;
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = ['email' => '', 'password' => ''];
            } else {
                $forceLogin = env('DEBUG_FORCE_LOGIN') === 'true';
                $email = strtolower($fields['email']);
                $pw = $fields['password'];
                $records = DB::select('SELECT id,password FROM users WHERE email=?', [$email]);
                $ok = count($records) === 1;
                if ($ok) {
                    $pw2 = $records[0]->password;
                    $userId = $records[0]->id;
                    $ok = Hash::check("$email\t$pw", $pw2);
                }
                $validator = Validator::make($fields, ['email' => ['required', 'email'], 'password' => ['required']]);
                if (!$validator->failed() && !$ok) {
                    if (!$forceLogin) {
                        $validator->errors()->add(
                            'password',
                            'The provided credentials do not match our records.'
                        );
                    }
                }
                if (!$forceLogin && ($validator->failed() || !$ok)) {
                    $rc = back()->withErrors([
                        'email' => __('The provided credentials do not match our records.'),
                    ])->onlyInput('email');
                } else {
                    if ($forceLogin) {
                        $userId = 1;
                    }
                    $rc = $this->loginUser($request, $userId);
                    if ($rc != null && $request->has('autologin')){
                        $key = StringHelper::createPassword();
                        $minutes = 60*24*30;
                        $endTime = \time() + 60*$minutes;
                        $end = new \DateTime("@$endTime");
                        $rc->withCookie(cookie('autologin', $key, $minutes));
                        $this->loginStoreInDb($userId, $key, $end);
                    }
                }
            }
        }
        if ($rc == null) {
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('user.login', ['context' => $context]);
        }
        return $rc;
    }
    private function loginAutomatic(Request $request)
    {
        $rc = null;
        if (($key = $request->cookie('autologin')) != null && $key !== '') {
            $hash = Hash::make($key);
            $now = (new \DateTime())->format('Y-m-d H:i');
            $sql = "SELECT id FROM users WHERE autologin='$hash' and endautologin > '$now'";
            $records = DB::select($sql);
            if ($records != null && count($records[0]) == 1) {
                $id = $records[0]['email'];
                $rc = $this->loginUser($request, $id);
            }
        }
        return $rc;
    }
    private function loginUser(Request $request, int $userId)
    {
        $rc = null;
        $user = User::find($userId);
        if ($user != null) {
            auth()->login($user);
            $role = Role::find($user->role_id);
            $request->session()->regenerate();
            session(['userName' => $user->name]);
            App::setLocale($user->localization);
            // $data = $request->session()->all();
            $rc = redirect('/menuitem-menu_main');
        }
        return $rc;
    }
    public function loginStoreInDb(int $userId, ?string $key, ?\DateTime $end){
        if ($key == null){
            $hash = '';
            $end = new \DateTime();
        } else {
            $hash = Hash::make($key);
        }
        $end2 = $end->format('Y-m-d h:i');
        $sql = "UPDATE users SET autologin='$hash', endautologin='$end2' WHERE id=$userId;";
        DB::update($sql);
    }
    public function logout(Request $request)
    {
        if ( ($id = Auth::id()) != null){
            $this->loginStoreInDb($id ?? 0, null, null);
        }
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
                'localization' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            ];
        } else {
            $rc = [
                'name' => ['required', Rule::unique('users')->ignore($user)],
                'email' => 'required|unique:users,id,' . strval($user->id),
                'role_id' => 'required',
                'localization' => 'required'
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
        Route::get('/login', [UserController::class, 'login'])->name('login');
        Route::post('/login', [UserController::class, 'login']);
        Route::get('/user-logout', [UserController::class, 'logout']);
        Route::post('/user-logout', [UserController::class, 'logout']);
        Route::get('/user-forgotten', [UserController::class, 'forgotten']);
        Route::post('/user-forgotten', [UserController::class, 'forgotten']);
        Route::get('/user-answer', [UserController::class, 'answer']);
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
            $email = strtolower($fields['email']);
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                // Retrieve the validated input...
                $validated = $validator->validated();
                if ($fields['localization'] !== App::getLocale()) {
                    App::setLocale($fields['localization']);
                }
                $validated['password'] = UserController::hash($email, $fields['password']);
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
            $email = strtolower($fields['email']);
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
