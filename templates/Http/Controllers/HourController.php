<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class HourController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/hour-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'owner_id' => auth()->id(),
                    'time' => date('Y-m-d'),
                    'start' => '',
                    'end' => '',
                    'duration' => '',
                    'hourtype_scope' => 1351,
                    'hourstate_scope' => 1361,
                    'description' => ''
                ];
            }
            $optionsHourtype = SProperty::optionsByScope('hourtype', $fields['hourtype_scope'], '-');
            $optionsHourstate = SProperty::optionsByScope('hourstate', $fields['hourstate_scope'], '-');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('hour.create', [
                'context' => $context,
                'optionsHourtype' => $optionsHourtype,
                'optionsHourstate' => $optionsHourstate,
                'optionsOwner' => $optionsOwner,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hour $hour, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/hour-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'time' => $hour->time,
                    'duration' => $hour->duration,
                    'hourtype_scope' => $hour->hourtype_scope,
                    'hourstate_scope' => $hour->hourstate_scope,
                    'description' => $hour->description,
                    'owner_id' => $hour->owner_id
                ];
            }
            $optionsHourtype = SProperty::optionsByScope('hourtype', $hour->hourtype_scope, '');
            $optionsHourstate = SProperty::optionsByScope('hourstate', $hour->hourstate_scope, '');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $hour);
            $rc = view('hour.edit', [
                'context' => $context,
                'optionsHourtype' => $optionsHourtype,
                'optionsHourstate' => $optionsHourstate,
                'optionsOwner' => $optionsOwner,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hour $hour, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $hour->delete();
        }
        return redirect('/hour-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/hour-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as hourtype,
  t2.name as hourstate,
  t3.name as owner
FROM hours t0
LEFT JOIN sproperties t1 ON t1.id=t0.hourtype_scope
LEFT JOIN sproperties t2 ON t2.id=t0.hourstate_scope
LEFT JOIN users t3 ON t3.id=t0.owner_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'hourtype' => '',
                    'hourstate' => 1361,
                    'owner' => '',
                    'from' => '',
                    'until' => '',
                    'text' => '',
                    '_sortParams' => 'time:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'hourtype_scope', 'hourtype');
                ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'hourstate_scope', 'hourstate');
                ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'owner_id', 'owner');
                ViewHelper::addConditionDateTimeRange($fields, $conditions, $parameters, 'from', 'until', 'time');
                ViewHelper::addConditionPattern($fields, $conditions, $parameters, 'description', 'text');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $fields['sum'] = DbHelper::buildSum($records, 'duration', 'h:m');
            $optionsHourtype = SProperty::optionsByScope('hourtype', $fields['hourtype'], 'all');
            $optionsHourstate = SProperty::optionsByScope('hourstate', $fields['hourstate'], 'all');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner'], __('all'));
            $context = new ContextLaraKnife($request, $fields);
            return view('hour.index', [
                'context' => $context,
                'records' => $records,
                'optionsHourtype' => $optionsHourtype,
                'optionsHourstate' => $optionsHourstate,
                'optionsOwner' => $optionsOwner,
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
            'time' => 'required|date',
            'duration' => 'required|integer',
            'hourtype_scope' => 'required',
            'hourstate_scope' => 'required',
            'description' => '',
            'owner_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/hour-index', [HourController::class, 'index'])->middleware('auth');
        Route::post('/hour-index', [HourController::class, 'index'])->middleware('auth');
        Route::get('/hour-create', [HourController::class, 'create'])->middleware('auth');
        Route::put('/hour-store', [HourController::class, 'store'])->middleware('auth');
        Route::post('/hour-edit/{hour}', [HourController::class, 'edit'])->middleware('auth');
        Route::get('/hour-edit/{hour}', [HourController::class, 'edit'])->middleware('auth');
        Route::post('/hour-update/{hour}', [HourController::class, 'update'])->middleware('auth');
        Route::get('/hour-show/{hour}/delete', [HourController::class, 'show'])->middleware('auth');
        Route::delete('/hour-show/{hour}/delete', [HourController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Hour $hour, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/hour-index')->middleware('auth');
        } else {
            $optionsHourtype = SProperty::optionsByScope('hourtype', $hour->hourtype_scope, '');
            $optionsHourstate = SProperty::optionsByScope('hourstate', $hour->hourstate_scope, '');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $hour->owner_id, __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $hour);
            $rc = view('hour.show', [
                'context' => $context,
                'optionsHourtype' => $optionsHourtype,
                'optionsHourstate' => $optionsHourstate,
                'optionsOwner' => $optionsOwner,
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
            $start = DateTimeHelper::timeToMinutes($fields['start']);
            if ($start != null) {
                $fields['time'] .= ' ' . $fields['start'];
            }
            if ($start != null && ($end = DateTimeHelper::timeToMinutes($fields['end'])) != null) {    
                $fields['duration'] = $end - $start;
            }
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['description'] = strip_tags($validated['description']);
                Hour::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/hour-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Hour $hour, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['description'] = strip_tags($validated['description']);
                $hour->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/hour-index');
        }
        return $rc;
    }
}
