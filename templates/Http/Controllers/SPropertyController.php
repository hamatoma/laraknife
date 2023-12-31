<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\SProperty;
use App\Helpers\DbHelper;
use App\Helpers\ViewHelper;
use App\Helpers\Pagination;

class SPropertyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rc = null;
        $error = null;
        if (count($_POST) > 0) {
            $fields = $_POST;
            try {
                $incomingFields = $request->validate($this->rules(true));
                $rc = $this->store($request);
            } catch(\Exception $e ){ 
                $error = $e->getMessage();
            }
        } else {
            $fields = ['id' => '', 'scope' => '', 'name' => '', 'shortname' => '',
                'order' => '', 'value' => '', 'info' => ''];
        }
        if ($rc == null){
            $rc = view('sproperty.create', ['fields' => $fields, 'error' => $error]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SProperty $sproperty)
    {
        return view('sproperty.edit', ['sproperty' => $sproperty]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SProperty $sproperty)
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] == 'btnDelete') {
            $sproperty->delete();
        }
        return redirect('/sproperty-index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (array_key_exists('btnSubmit', $_POST) && $_POST['btnSubmit'] == 'btnNew') {
            return redirect('/sproperty-create');
        } else {
            $sql = 'SELECT * FROM sproperties';
            $parameters = [];
            if (count($_POST) == 0) {
                $fields = ['scope' => '', 'text' => '', '_sortParams' => 'scope:asc;order:asc;name:asc'];
            } else {
                $fields = $_POST;
                $conditions = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'scope');
                ViewHelper::addConditionPattern($conditions, $parameters, 'scope,name,shortname,value,info', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $records = DB::select($sql, $parameters);
            $pagination = new Pagination($sql, $parameters, $fields);
            $scopes = SProperty::scopes();
            $options = ViewHelper::buildEntriesOfCombobox($scopes, null,
                isset($fields['scope']) ? $fields['scope'] : '', '<All>', true);
            return view('sproperty.index', [
                'records' => $records,
                'fields' => $fields,
                'options' => $options,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate = false): array
    {
        $rc = [
            'scope' => 'required|alpha',
            'name' => 'required',
            'order' => 'integer|min:1|max:9999',
            'shortname' => 'required|alpha_num',
            'value' => 'nullable',
            'info' => 'nullable'
        ];
        if ($isCreate) {
            $rc['id'] = 'required|integer|min:1|unique:sproperties';
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/sproperty-index', [SPropertyController::class, 'index']);
        Route::post('/sproperty-index', [SPropertyController::class, 'index']);
        Route::get('/sproperty-create', [SPropertyController::class, 'create']);
        Route::post('/sproperty-create', [SPropertyController::class, 'create']);
        Route::put('/sproperty-create', [SPropertyController::class, 'store']);
        Route::get('/sproperty-edit/{sproperty}', [SPropertyController::class, 'edit']);
        Route::post('/sproperty-update/{sproperty}', [SPropertyController::class, 'update']);
        Route::get('/sproperty-show/{sproperty}/delete', [SPropertyController::class, 'show']);
        Route::delete('/sproperty-show/{sproperty}/delete', [SPropertyController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(SProperty $sproperty)
    {
        return view('sproperty.show', ['sproperty' => $sproperty, 'mode' => 'delete']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->btnSubmit == 'btnStore') {
            $incomingFields = $request->validate($this->rules(true));
            $incomingFields['info'] = strip_tags($incomingFields['info']);
            SProperty::create($incomingFields);
        }
        return redirect('/sproperty-index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(SProperty $sproperty, Request $request)
    {
        if ($request->btnSubmit == 'btnStore') {
            $incomingFields = $request->validate($this->rules());
            $incomingFields['info'] = strip_tags($incomingFields['info']);
            $sproperty->update($incomingFields);
        }
        return redirect('/sproperty-index');
    }
}
