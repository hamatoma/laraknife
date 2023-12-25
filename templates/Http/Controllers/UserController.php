<?php

namespace App\Http\Controllers;

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
            $sql = 'SELECT * FROM users';
            $parameters = [];
            if (count($_POST) == 0) {
                $fields = ['id' => '', 'text' => '', '_sortParams' => 'id:asc'];
            } else {
                $fields = $_POST;
                $conditions = [];
                $parameters = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'id');
                ViewHelper::addConditionPattern($conditions, $parameters, 'name,email', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $records = DB::select($sql, $parameters);
            $pagination = new Pagination($sql, $parameters, $fields);
            return view('user.index', [
                'records' => $records,
                'fields' => $fields,
                'pagination' => $pagination,
                'legend' => $pagination->legendText()
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
