<?php

namespace App\Http\Controllers;

use App\Models\SProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SPropertyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sproperty.create');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SProperty $sproperty)
    {
        return view('sproperty.edit', ['sproperty' => $sproperty]);
    }
    /**
     * Shows the deletion dialog.
     */
    public function delete(SProperty $sproperty)
    {
        return view('sproperty.delete', ['sproperty' => $sproperty]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SProperty $sproperty)
    {
        $sproperty->delete();
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
            $records = null;
            if (count($_POST) == 0) {
                $fields = ['scope' => '', 'text' => ''];
            } else {
                $fields = $_POST;
                $condition = null;
                $values = [];
                $value = array_key_exists('scope', $_POST) ? $_POST['scope'] : '';
                if (!empty($value)) {
                    $condition = "scope=:scope";
                    $values = [':scope' => $value];
                }
                $value = str_replace('*', '%', $_POST['text']) . '%';
                $value = str_replace('%%', '%', $value);
                if ($value !== '%') {
                    $condition2 = "(scope like :v1 or name like :v2 "
                        . "or shortname like :v3 or value like :v4  or info like :v5)";
                    if ($condition == null) {
                        $condition = $condition2;
                    } else {
                        $condition .= " and $condition2";
                    }
                    $values[':v1'] = $value;
                    $values[':v2'] = $value;
                    $values[':v3'] = $value;
                    $values[':v4'] = $value;
                    $values[':v5'] = $value;
                }
                if ($condition != null) {
                    $records = DB::select("select * from sproperties where $condition order by id", $values);
                }
            }
            if ($records === null) {
                $records = SProperty::orderBy('id', 'desc')->get();
            }
            $options = SProperty::comboDataAsString(SProperty::scopes(true), isset($fields['scope']) ? $fields['scope'] : '');
            return view('sproperty.index', [
                'records' => $records,
                'fields' => $fields,
                'options' => $options
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
            'shortname' => 'required|alpha',
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
        Route::get('/sproperty-delete/{sproperty}', [SPropertyController::class, 'delete']);
        Route::delete('/sproperty-delete/{sproperty}', [SPropertyController::class, 'destroy']);
    }
    /**
     * Display the specified resource.
     */
    public function show(SProperty $id)
    {
        //
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
