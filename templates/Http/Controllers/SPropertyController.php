<?php

namespace App\Http\Controllers;

use App\Models\SProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Hamatoma\Laraknife as LKN;

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
                $conditions = [];
                $parameters = [];
                LKN\ViewHelpers::addConditionComparism($conditions, $parameters, 'scope');
                LKN\ViewHelpers::addConditionPattern($conditions, $parameters, 'scope,name,shortname,value,info', 'text');
                if (count($conditions) > 0) {
                    $condition = count($conditions) == 1 ? $conditions[0] : implode(' AND ', $conditions);
                    $records = DB::select("select * from sproperties where $condition order by scope,`order`,id", $parameters);
                }
            }
            if ($records === null) {
                $records = SProperty::orderBy('scope')->orderBy('order')->orderBy('id')->get();
            }
            $scopes = SProperty::scopes();
            $options = LKN\ViewHelpers::buildEntriesOfCombobox($scopes, null, isset($fields['scope']) ? $fields['scope'] : '', '-');
            return view('sproperty.index', [
                'records' => $records,
                'fields' => $fields,
                'options' => $options,
                'legend' => ''
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
