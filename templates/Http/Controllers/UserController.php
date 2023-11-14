<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Hamatoma\Laraknife as LKN;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', ['user' => $user]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/user-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] == 'btnNew') {
            return redirect('/user-create');
        } else {
            $records = null;
            if (count($_POST) == 0) {
                $fields = ['id' => '', 'text' => ''];
            } else {
                $fields = $_POST;
                $conditions = [];
                $parameters = [];
                LKN\ViewHelpers::addConditionComparism($conditions, $parameters, 'id');
                LKN\ViewHelpers::addConditionPattern($conditions, $parameters, 'name,email', 'text');
                if (count($conditions) > 0) {
                    $condition = count($conditions) == 1 ? $conditions[0] : implode(' AND ', $conditions);
                    $records = DB::select("select * from users where $condition order by id", $parameters);
                }
            }
            if ($records === null) {
                $records = User::orderBy('id')->get();
            }
            return view('user.index', [
                'records' => $records,
                'fields' => $fields,
                'legend' => ''
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
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'repetition' => 'required'
        ];
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
        Route::post('/user-update/{user}', [UserController::class, 'update']);
        Route::get('/user-show/{user}/delete', [UserController::class, 'show']);
        Route::delete('/user-show/{user}/delete', [UserController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.show', ['user' => $user, 'mode' => 'delete']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->btnSubmit == 'btnStore') {
            $incomingFields = $request->validate($this->rules());
            unset($incomingFields['repetition']);
            User::create($incomingFields);
        }
        return redirect('/user-index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, Request $request)
    {
        if ($request->btnSubmit == 'btnStore') {
            $incomingFields = $request->validate($this->rules());
            unset($incomingFields['repetition']);
            $user->update($incomingFields);
        }
        return redirect('/user-index');
    }
}
