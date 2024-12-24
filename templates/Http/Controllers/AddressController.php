<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Helpers\Helper;
use App\Models\Address;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/address-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'person_id' => auth()->id(),
                    'name' => '',
                    'info' => '',
                    'priority' => '10',
                    'addresstype_scope' => ''
                ];
            }
            $optionsAddresstype = SProperty::optionsByScope('addresstype', $fields['addresstype_scope'], '-');
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $fields['person_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('address.create', [
                'context' => $context,
                'optionsAddresstype' => $optionsAddresstype,
                'optionsPerson' => $optionsPerson,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/address-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => $address->name,
                    'info' => $address->info,
                    'addresstype_scope' => $address->addresstype_scope,
                    'priority' => $address->priority,
                    'person_id' => $address->person_id
                ];
            }
            $optionsAddresstype = SProperty::optionsByScope('addresstype', $address->addresstype_scope, '');
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $fields['person_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $address);
            $rc = view('address.edit', [
                'context' => $context,
                'optionsAddresstype' => $optionsAddresstype,
                'optionsPerson' => $optionsPerson,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $address->delete();
            Change::createFromModel($address, Change::$DELETE, 'Address');
        }
        return redirect('/address-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/address-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as addresstype,
  t2.nickname as person
FROM addresses t0
LEFT JOIN sproperties t1 ON t1.id=t0.addresstype_scope
LEFT JOIN persons t2 ON t2.id=t0.person_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'addresstype' => '',
                    'person' => '',
                    'text' => '',
                    '_sortParams' => 't0.name:asc;priority:desc;id:asc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'addresstype_scope', 'addresstype');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'person_id', 'person');
            ViewHelper::addConditionPattern($conditions, $parameters, 't0.name,t0.info,t2.nickname', 'text');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsAddresstype = SProperty::optionsByScope('addresstype', $fields['addresstype'], 'all');
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $fields['person'], __('all'));
            $context = new ContextLaraKnife($request, $fields);
            return view('address.index', [
                'context' => $context,
                'records' => $records,
                'optionsAddresstype' => $optionsAddresstype,
                'optionsPerson' => $optionsPerson,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate, ?int $addressType): array
    {
        $rc = [
            'name' => $addressType == 1321 /* email */ ? 'required|email' : 'required|regex:/^[+]?[0-9 -]+$/',
            'info' => '',
            'addresstype_scope' => $isCreate ? 'required' : '',
            'person_id' => $isCreate ? 'required' : '',
            'priority' => 'required|integer'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/address-index', [AddressController::class, 'index'])->middleware('auth');
        Route::post('/address-index', [AddressController::class, 'index'])->middleware('auth');
        Route::get('/address-create', [AddressController::class, 'create'])->middleware('auth');
        Route::put('/address-store', [AddressController::class, 'store'])->middleware('auth');
        Route::post('/address-edit/{address}', [AddressController::class, 'edit'])->middleware('auth');
        Route::get('/address-edit/{address}', [AddressController::class, 'edit'])->middleware('auth');
        Route::post('/address-update/{address}', [AddressController::class, 'update'])->middleware('auth');
        Route::get('/address-show/{address}/delete', [AddressController::class, 'show'])->middleware('auth');
        Route::delete('/address-show/{address}/delete', [AddressController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Address $address, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/address-index')->middleware('auth');
        } else {
            $fields = $request->all();
            $optionsAddresstype = SProperty::optionsByScope('addresstype', $address->addresstype_scope, '');
            $person = $address->person_id;
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $person, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $address);
            $rc = view('address.show', [
                'context' => $context,
                'optionsAddresstype' => $optionsAddresstype,
                'optionsPerson' => $optionsPerson,
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
            $addressType = $fields['addresstype_scope'];
            $validator = Validator::make($fields, $this->rules(true, $addressType));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $address = Address::create($validated);
                Change::createFromFields($validated, Change::$CREATE, 'Address', $address->id);
            }
        }
        if ($rc == null) {
            $rc = redirect('/address-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Address $address, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $addressType = $address->getAttribute('addresstype_scope');
            $validator = Validator::make($fields, $this->rules(false, $addressType));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $address->update($validated);
                Change::createFromFields($validated, Change::$UPDATE, 'Address', $address->id);
            }
        }
        if ($rc == null) {
            $rc = redirect('/address-index');
        }
        return $rc;
    }
}
