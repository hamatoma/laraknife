<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Menuitem;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MenuitemController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menuitem-index');
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
            $rc = view('menuitem.create', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menuitem $menuitem, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menuitem-index');
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
            $context = new ContextLaraKnife($request, null, $menuitem);
            $rc = view('menuitem.edit', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menuitem $menuitem, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $menuitem->delete();
        }
        return redirect('/menuitem-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/menuitem-create');
        } elseif ($request->btnSubmit === 'btnAssign') {
            return redirect('/menuitem-order');
        } else {
            $sql = 'SELECT t0.*'
                . ' FROM menuitems t0'
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
            return view('menuitem.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination
            ]);
        }
    }
    public function menu(Request $request, string $section = 'main')
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $role = $userId == null ? 1 : User::find($userId)->role_id;
        } else {
            $role = 3;
        }
        $records = DB::select("SELECT DISTINCT t0.* FROM menuitems t0 
        LEFT JOIN menuitems_roles t1 on t1.menuitem_id=t0.id
        LEFT JOIN roles t2 on t2.id=t1.role_id
        WHERE t1.role_id=$role AND t0.section='$section' ORDER BY t1.`order`");
        $fields = $request->all();
        $context = new ContextLaraKnife($request, $fields);
        $cols = 4;
        $rows = intval((count($records) + $cols - 1) / $cols);
        $name = "menuitem.menu_$section";
        $rc = view($name, [
            'context' => $context,
            'records' => $records,
            'rows' => $rows,
            'cols' => $cols,
        ]);
        return $rc;
    }
    public function menuMain(Request $request)
    {
        $rc = $this->menu($request, 'main');
        return $rc;
    }
    public function order(Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menuitem-index');
        } else {
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = ['role' => '', 'position' => '1', 'selectedMenuItems' => '', 'lastRole' => ''];
            }
            if ($fields['role'] != $fields['lastRole']) {
                $fields['selectedMenuItems'] = '';
            }
            $ids = empty($fields['selectedMenuItems']) ? [] : explode(',', $fields['selectedMenuItems']);
            if ($request->btnSubmit === 'btnStore') {
                $role = $fields['role'];
                DB::delete("DELETE FROM menuitems_roles where role_id=$role");
                for ($ix = 0; $ix < count($ids); $ix++) {
                    $order = ($ix + 1) * 10;
                    $menuitem = $ids[$ix];
                    DB::insert("INSERT INTO menuitems_roles (`order`, menuitem_id, role_id) VALUES ($order, $menuitem, $role)");
                }
                $rc = redirect('/menuitem-index');
            } elseif (($no = ViewHelper::numberOfButton($fields, 'insert')) != null) {
                $position = intval($fields['position']);
                if ($position <= 0 || $position > count($ids)) {
                    $position = $fields['position'] = 1;
                }
                array_splice($ids, $position - 1, 0, $no);
            } elseif (($no = ViewHelper::numberOfButton($fields, 'delete')) != null) {
                $ix = array_search(strval($no), $ids);
                array_splice($ids, $ix, 1);
            } elseif (($no = ViewHelper::numberOfButton($fields, 'up')) != null) {
                $no2 = strval($no);
                $ix = array_search($no2, $ids);
                if ($ix > 0) {
                    array_splice($ids, $ix, 1);
                    array_splice($ids, $ix - 1, 0, $no2);
                }
            } elseif (($no = ViewHelper::numberOfButton($fields, 'down')) != null) {
                $no2 = strval($no);
                $ix = array_search($no2, $ids);
                if ($ix < count($ids) - 1) {
                    array_splice($ids, $ix, 1);
                    array_splice($ids, $ix + 1, 0, $no2);
                }
            }
            if ($rc == null) {
                $fields['selectedMenuItems'] = implode(',', $ids);

                $roleOptions = DbHelper::comboboxDataOfTable('roles', 'name', 'id', $fields['role'], '');
                $role = intval(DbHelper::findCurrentSelectedInCombobox($roleOptions));
                if (empty($fields['selectedMenuItems'])) {
                    $records = DB::select("SELECT DISTINCT t0.id AS role FROM menuitems t0 
                LEFT JOIN menuitems_roles t1 on t1.menuitem_id=t0.id
                LEFT JOIN roles t2 on t2.id=t1.role_id
                WHERE t1.role_id=$role ORDER BY t1.`order`");
                    $ids2 = [];
                    foreach ($records as &$rec) {
                        array_push($ids2, $rec->role);
                    }
                    $fields['selectedMenuItems'] = implode(',', $ids2);
                }
                $ids3 = $fields['selectedMenuItems'];
                $records = empty($ids3) ? [] : DB::select("SELECT * FROM menuitems WHERE id in ($ids3)");
                $records = DbHelper::resortById($records, explode(',', $ids3));
                $where = empty($ids3) ? '' : " WHERE id NOT IN ($ids3)";
                $records2 = DB::select("SELECT * FROM menuitems$where");
                $fields['lastRole'] = $role;
                $context = new ContextLaraKnife($request, $fields);
                $rc = view('menuitem.order', [
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
        Route::get('/menuitem-index', [MenuitemController::class, 'index'])->middleware('auth');
        Route::post('/menuitem-index', [MenuitemController::class, 'index'])->middleware('auth');
        Route::get('/menuitem-create', [MenuitemController::class, 'create'])->middleware('auth');
        Route::put('/menuitem-store', [MenuitemController::class, 'store'])->middleware('auth');
        Route::post('/menuitem-edit/{menuitem}', [MenuitemController::class, 'edit'])->middleware('auth');
        Route::get('/menuitem-edit/{menuitem}', [MenuitemController::class, 'edit'])->middleware('auth');
        Route::post('/menuitem-update/{menuitem}', [MenuitemController::class, 'update'])->middleware('auth');
        Route::get('/menuitem-show/{menuitem}/delete', [MenuitemController::class, 'show'])->middleware('auth');
        Route::delete('/menuitem-show/{menuitem}/delete', [MenuitemController::class, 'destroy'])->middleware('auth');
        Route::get('/menuitem-order', [MenuitemController::class, 'order'])->middleware('auth');
        Route::post('/menuitem-order', [MenuitemController::class, 'order'])->middleware('auth');
        Route::get('/menuitem-menu_main', [MenuitemController::class, 'menuMain'])->middleware('auth');
        Route::post('/menuitem-menu_main', [MenuitemController::class, 'menuMain'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Menuitem $menuitem, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/menuitem-index');
        } else {
            $context = new ContextLaraKnife($request, null, $menuitem);
            $rc = view('menuitem.show', [
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
            $name = $fields['name'];
            if (empty($fields['label'])){
                $fields['label'] = StringHelper::toCapital($name);
            }
            if (empty($fields['link'])){
                $word = StringHelper::singularOf($name);
                $fields['link'] = "/$word-index";
            }
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                Menuitem::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/menuitem-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Menuitem $menuitem, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $menuitem->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/menuitem-index');
        }
        return $rc;
    }
}
