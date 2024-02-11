<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menu-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'label' => '',
                    'icon' => '',
                    'section' => 'main',
                    'link' => ''
                ];
            }
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('menu.create', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menu-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'label' => '',
                    'icon' => '',
                    'section' => '',
                    'link' => ''
                ];
            }
            $context = new ContextLaraKnife($request, null, $menu);
            $rc = view('menu.edit', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $menu->delete();
        }
        return redirect('/menu-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/menu-create');
        } elseif ($request->btnSubmit === 'btnAssign') {
            return redirect('/menu-order');
        } else {
            $sql = 'SELECT t0.*'
                . ' FROM menus t0'
            ;
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'text' => '',
                    '_sortParams' => 'id:asc'
                        . ';name:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionPattern($conditions, $parameters, 'name,label,icon,link', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $context = new ContextLaraKnife($request, $fields);
            return view('menu.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination
            ]);
        }
    }
    public function menu(Request $request, string $scope='main'){
        if (Auth::check()){
            $userId = Auth::user()->id;
            $role = $userId == null ? 1 : User::get($userId)->role_id;
        } else {
            $role = 3;
        }
        $records = DB::select("SELECT DISTINCT t0.id AS role FROM menus t0 
        LEFT JOIN menus_roles t1 on t1.menu_id=t0.id
        LEFT JOIN roles t2 on t2.id=t1.role_id
        WHERE t1.role_id=$role ORDER BY t1.`order`");
        $fields = $request->all();
        $context = new ContextLaraKnife($request, $fields);
        $cols = 4;
        $rows = intval((count($records) + $cols - 1) / $cols);
        $rc = view('menu.menu', [
            'context' => $context,
            'records' => $records,
            'rows' => $rows,
            'cols' => $cols,
        ]);
        return $rc;
    }
    public function order(Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menu-index');
        } else {
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['role' => '', 'position' => '1', 'selectedMenus' => '', 'lastRole' => ''];
            }
            if ($fields['role'] != $fields['lastRole']){
                $fields['selectedMenus'] = '';
            }
            $ids = empty($fields['selectedMenus']) ? [] : explode(',', $fields['selectedMenus']);
            if ($request->btnSubmit === 'btnStore') {
                $role = $fields['role'];
                DB::delete("DELETE FROM menus_roles where role_id=$role");
                for ($ix = 0; $ix < count($ids); $ix++){
                    $order = ($ix+1)*10;
                    $menu = $ids[$ix];
                    DB::insert("INSERT INTO menus_roles (`order`, menu_id, role_id) VALUES ($order, $menu, $role)"); 
                }
                $rc = redirect('/menu-index');
            } elseif (($no = DbHelper::numberOfButton($fields, 'insert')) != null) {
                $position = intval($fields['position']);
                if ($position <= 0 || $position > count($ids)) {
                    $position = $fields['position'] = 1;
                }
                array_splice($ids, $position - 1, 0, $no);
            } elseif (($no = DbHelper::numberOfButton($fields, 'delete')) != null) {
                $ix = array_search(strval($no), $ids);
                array_splice($ids, $ix, 1);
            } elseif (($no = DbHelper::numberOfButton($fields, 'up')) != null) {
                $no2 = strval($no);
                $ix = array_search($no2, $ids);
                if ($ix > 0) {
                    array_splice($ids, $ix, 1);
                    array_splice($ids, $ix - 1, 0, $no2);
                }
            } elseif (($no = DbHelper::numberOfButton($fields, 'down')) != null) {
                $no2 = strval($no);
                $ix = array_search($no2, $ids);
                if ($ix < count($ids) - 1) {
                    array_splice($ids, $ix, 1);
                    array_splice($ids, $ix + 1, 0, $no2);
                }
            }
            if ($rc == null) {
                $fields['selectedMenus'] = implode(',', $ids);

                $roleOptions = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role'], '');
                $role = intval(DbHelper::findCurrentSelectedInCombobox($roleOptions));
                if (empty($fields['selectedMenus'])) {
                    $records = DB::select("SELECT DISTINCT t0.id AS role FROM menus t0 
                LEFT JOIN menus_roles t1 on t1.menu_id=t0.id
                LEFT JOIN roles t2 on t2.id=t1.role_id
                WHERE t1.role_id=$role ORDER BY t1.`order`");
                    $ids2 = [];
                    foreach ($records as &$rec) {
                        array_push($ids2, $rec->role);
                    }
                    $fields['selectedMenus'] = implode(',', $ids2);
                }
                $ids3 = $fields['selectedMenus'];
                $records = empty($ids3) ? [] : DB::select("SELECT * FROM menus WHERE id in ($ids3)");
                $records = DbHelper::resortById($records, explode(',', $ids3));
                $where = empty($ids3) ? '' : " WHERE id NOT IN ($ids3)";
                $records2 = DB::select("SELECT * FROM menus$where");
                $fields['lastRole'] = $role;
                $context = new ContextLaraKnife($request, $fields);
                $rc = view('menu.order', [
                    'context' => $context,
                    'records' => $records,
                    'records2' => $records2,
                    'roleOptions' => $roleOptions,
                ]);
            }
        }
        return $rc;
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate = false): array
    {
        $rc = [
            'name' => 'required',
            'label' => 'required',
            'icon' => 'required',
            'section' => 'required',
            'link' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/menu-index', [MenuController::class, 'index']);
        Route::post('/menu-index', [MenuController::class, 'index']);
        Route::get('/menu-create', [MenuController::class, 'create']);
        Route::put('/menu-store', [MenuController::class, 'store']);
        Route::post('/menu-edit/{menu}', [MenuController::class, 'edit']);
        Route::get('/menu-edit/{menu}', [MenuController::class, 'edit']);
        Route::post('/menu-update/{menu}', [MenuController::class, 'update']);
        Route::get('/menu-show/{menu}/delete', [MenuController::class, 'show']);
        Route::delete('/menu-show/{menu}/delete', [MenuController::class, 'destroy']);
        Route::get('/menu-order', [MenuController::class, 'order']);
        Route::post('/menu-order', [MenuController::class, 'order']);
        Route::get('/menu-menu', [MenuController::class, 'menu']);
        Route::post('/menu-menu', [MenuController::class, 'menu']);
    }
    /**
     * Display the specified resource.
     */
    public function show(Menu $menu, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menu-index');
        } else {
            $context = new ContextLaraKnife($request, null, $menu);
            $rc = view('menu.show', [
                'context' => $context,
                'mode' => 'delete'
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
                Menu::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/menu-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Menu $menu, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $menu->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/menu-index');
        }
        return $rc;
    }
}
