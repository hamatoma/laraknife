<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\Location;
use App\Models\SProperty;
use App\Helpers\ContextLaraKnife;
use App\Helpers\ViewHelper;
use App\Helpers\DbHelper;
use App\Helpers\Helper;
use App\Helpers\Pagination;

class LocationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/location-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'person_id' => '',
                    'country' => 'D',
                    'zip' => '',
                    'city' => '',
                    'street' => '',
                    'additional' => '',
                    'info' => '',
                    'priority' => 10
                ];
            }
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $fields['person_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);     
            $rc = view('location.create', [
                'context' => $context,
                'optionsPerson' => $optionsPerson,
                ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/location-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'country' => $location->country,
                    'zip' => $location->zip,
                    'city' => $location->city,
                    'street' => $location->street,
                    'additional' => $location->additional,
                    'info' => $location->info,
                    'priority' => $location->priority,
                    'person_id' => $location->person_id
                    ];
            }
            $optionsPerson = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['person_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $location);
            $rc = view('location.edit', [
                'context' => $context,
                'optionsPerson' => $optionsPerson,
                ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $location->delete();
        }
        return redirect('/location-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/location-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.nickname as person
FROM locations t0
LEFT JOIN persons t1 ON t1.id=t0.person_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                'country' => '',
                'zip' => '',
                'city' => '',
                'text' => '',
                '_sortParams' => 'person:desc;country:desc;zip:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionPattern($conditions, $parameters, 'country');
                ViewHelper::addConditionPattern($conditions, $parameters, 'zip');
                ViewHelper::addConditionPattern($conditions, $parameters, 'city');
                ViewHelper::addConditionPattern($conditions, $parameters, 'zip,city,street,additional,t0.info,t1.nickname', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $context = new ContextLaraKnife($request, $fields);
            return view('location.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination
            ]);
        }
    }
    /**
     * Returns the validation rules.
     * @return array<string, string> The validation rules.
     */
    private function rules(bool $isCreate=false): array
    {
        $rc = [
            'country' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'street' => 'required',
            'additional' => '',
            'info' => '',
            'priority' => 'required|integer',
            'person_id' => $isCreate ? 'required' : ''
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/location-index', [LocationController::class, 'index'])->middleware('auth');
        Route::post('/location-index', [LocationController::class, 'index'])->middleware('auth');
        Route::get('/location-create', [LocationController::class, 'create'])->middleware('auth');
        Route::put('/location-store', [LocationController::class, 'store'])->middleware('auth');
        Route::post('/location-edit/{location}', [LocationController::class, 'edit'])->middleware('auth');
        Route::get('/location-edit/{location}', [LocationController::class, 'edit'])->middleware('auth');
        Route::post('/location-update/{location}', [LocationController::class, 'update'])->middleware('auth');
        Route::get('/location-show/{location}/delete', [LocationController::class, 'show'])->middleware('auth');
        Route::delete('/location-show/{location}/delete', [LocationController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Location $location, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/location-index')->middleware('auth');
        } else {
            $optionsPerson = DbHelper::comboboxDataOfTable('persons', 'nickname', 'id', $location->person_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $location);
            $rc = view('location.show', [
                'context' => $context,
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
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                Location::create($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/location-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Location $location, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $location->update($validated);
            }
        }
        if ($rc == null){
            $rc = redirect('/location-index');
        }
        return $rc;
    }
}
