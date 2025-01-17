<?php

namespace App\Http\Controllers;

use App\Models\Change;
use App\Models\Person;
use App\Helpers\Helper;
use App\Models\Address;
use App\Models\Location;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    /**
     * Show the form for address management.
     */
    public function address(Person $person, Request $request)
    {
        $button = $request->btnSubmit;
        if ($button === 'btnCancel') {
            $rc = redirect('/person-index');
        } else {
            $rc = null;
            $error = null;
            $fields = $request->all();
            if ($button === 'btnAdd') {
                $msg = $this->storeAddresses($person, $fields['address']);
                if ($msg != null) {

                }
            }
            $fields['list'] = $person->findAddresses();
            $fields['address'] = '';
            if ($rc == null) {
                $context = new ContextLaraKnife($request, $fields, $person);
                $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('person-edit', 2, $person->id);
                $rc = view('person.address', [
                    'context' => $context,
                    'person' => $person,
                    'navTabsInfo' => $navigationTabInfo
                ]);
            }
        }
        return $rc;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/person-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'firstname' => '',
                    'middlename' => '',
                    'lastname' => '',
                    'nickname' => '',
                    'titles' => '',
                    'gender_scope' => '',
                    'persongroup_scope' => '',
                    'info' => ''
                ];
            }
            $optionsGender = SProperty::optionsByScope('gender', $fields['gender_scope'], '-');
            $optionsPersongroup = SProperty::optionsByScope('persongroup', $fields['persongroup_scope'], '-');
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('person.create', [
                'context' => $context,
                'optionsGender' => $optionsGender,
                'optionsPersongroup' => $optionsPersongroup,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/person-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'firstname' => $person->firstname,
                    'middlename' => $person->middlename,
                    'lastname' => $person->lastname,
                    'nickname' => $person->nickname,
                    'titles' => $person->titles,
                    'gender_scope' => $person->gender_scope,
                    'persongroup_scope' => $person->persongroup_scope,
                    'info' => $person->info
                ];
            }
            $optionsGender = SProperty::optionsByScope('gender', $person->gender_scope, '');
            $optionsPersongroup = SProperty::optionsByScope('persongroup', $person->persongroup_scope, '');
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('person-edit', 1, $person->id);
            $context = new ContextLaraKnife($request, null, $person);
            $rc = view('person.edit', [
                'context' => $context,
                'optionsGender' => $optionsGender,
                'optionsPersongroup' => $optionsPersongroup,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $person->delete();
            Change::createFromModel($person, Change::$DELETE, 'Person');
        }
        return redirect('/person-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/person-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as gender,
  t2.name as persongroup
FROM persons t0
LEFT JOIN sproperties t1 ON t1.id=t0.gender_scope
LEFT JOIN sproperties t2 ON t2.id=t0.persongroup_scope
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'gender' => '',
                    'persongroup' => '',
                    'text' => '',
                    '_sortParams' => 'lastname:desc'
                ];
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'gender_scope', 'gender');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'persongroup_scope', 'persongroup');
            ViewHelper::addConditionPattern($fields, $conditions, $parameters, 'firstname,lastname,middlename,titles,nickname,t0.info', 'text');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsGender = SProperty::optionsByScope('gender', $fields['gender'], 'all');
            $optionsPersongroup = SProperty::optionsByScope('persongroup', $fields['persongroup'], 'all');
            $context = new ContextLaraKnife($request, $fields);
            return view('person.index', [
                'context' => $context,
                'records' => $records,
                'optionsGender' => $optionsGender,
                'optionsPersongroup' => $optionsPersongroup,
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
            'firstname' => '',
            'middlename' => '',
            'lastname' => 'required',
            'nickname' => 'required',
            'titles' => '',
            'gender_scope' => 'required',
            'persongroup_scope' => 'required',
            'info' => ''
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/person-index', [PersonController::class, 'index'])->middleware('auth');
        Route::post('/person-index', [PersonController::class, 'index'])->middleware('auth');
        Route::get('/person-create', [PersonController::class, 'create'])->middleware('auth');
        Route::put('/person-store', [PersonController::class, 'store'])->middleware('auth');
        Route::post('/person-edit/{person}', [PersonController::class, 'edit'])->middleware('auth');
        Route::get('/person-address/{person}', [PersonController::class, 'address'])->middleware('auth');
        Route::post('/person-address/{person}', [PersonController::class, 'address'])->middleware('auth');
        Route::get('/person-edit/{person}', [PersonController::class, 'edit'])->middleware('auth');
        Route::post('/person-update/{person}', [PersonController::class, 'update'])->middleware('auth');
        Route::get('/person-show/{person}/delete', [PersonController::class, 'show'])->middleware('auth');
        Route::delete('/person-show/{person}/delete', [PersonController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Person $person, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/person-index')->middleware('auth');
        } else {
            $optionsGender = SProperty::optionsByScope('gender', $person->gender_scope, '');
            $optionsPersongroup = SProperty::optionsByScope('persongroup', $person->persongroup_scope, '');
            $context = new ContextLaraKnife($request, null, $person);
            $rc = view('person.show', [
                'context' => $context,
                'optionsGender' => $optionsGender,
                'optionsPersongroup' => $optionsPersongroup,
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
                $validated['info'] = strip_tags($validated['info']);
                $person = Person::create($validated);
                Change::createFromFields($validated, Change::$CREATE, 'Person', $person->id);
                $rc = redirect(to: "/person-address/$person->id");
            }
        }
        if ($rc == null) {
            $rc = redirect(to: '/person-index');
        }
        return $rc;
    }
    public function storeAddresses(Person &$person, string $text): ?string
    {
        $rc = null;
        $lines = explode("\n", $text);
        $line1 = $lines[0];
        $parts = explode(';', $line1);
        $address = $parts[0];
        $info = '';
        $priority = 10;
        $matcher = null;
        $addressType = 0;
        foreach ($lines as &$line) {
            if (preg_match('/prio(rity)?:\s*(\d+)/i', $line, $matcher)) {
                $prio = intval($matcher[2]);
            }
        }
        if (count($parts) > 1) {
            array_shift($parts);
            $info = implode("\n", $parts);
        }
        if (strpos($line1, '@') !== false) {
            $addressType = 1321;
            $validator = Validator::make(['email' => $address], ['email' => 'email']);
            if ($validator->fails()) {
                //$rc = back()->withErrors($validator)->withInput();
                $rc = __('Wrong email address');
            }
        } elseif (preg_match('/^[+]?\d/', $line1)) {
            $addressType = 1322;
            $validator = Validator::make(['phone' => $address], ['phone' => 'regex:/^[+]?[0-9 -]+$/']);
            if ($validator->fails()) {
                //$rc = back()->withErrors($validator)->withInput();
                $rc = __('Wrong phone number. Examples: +49-89-12342432 or 0831-12 34 56');
            }
        } else {
            $address = null;
            if (count($lines) < 2) {
                $rc = 'too few lines';
            } else {
                $street = $line1;
                $country = 'D';
                // ...............12...2.1.3...3.4..4
                if (!preg_match('/^(([a-zA-Z]+)-)?(\d+) (.*)\s*/', $lines[1], $matcher)) {
                    $rc = 'Wrong format of line 2 (zip city)';
                } else {
                    if ($matcher[2] !== '') {
                        $country = $matcher[2];
                    }
                    $zip = $matcher[3];
                    $city = $matcher[4];
                    $attributes = [
                        'country' => $country,
                        'zip' => $zip,
                        'city' => $city,
                        'street' => $street,
                        'info' => strip_tags($info),
                        'priority' => $priority,
                        'person_id' => $person->id
                    ];
                    $location = Location::create($attributes);
                    Change::createFromFields($attributes, Change::$CREATE, 'Location', $location->id);
                }
            }
        }
        if ($rc == null && $address != null) {
            $attributes = [
                'name' => $address,
                'addresstype_scope' => $addressType,
                'info' => strip_tags($info),
                'priority' => $priority,
                'person_id' => $person->id
            ];
            $address = Address::create($attributes);
            Change::createFromFields($attributes, Change::$CREATE, 'Address', $address->id);
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Person $person, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(false));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $person->update($validated);
                Change::createFromFields($validated, Change::$UPDATE, 'Person', $person->id);
            }
        }
        if ($rc == null) {
            $rc = redirect("/person-edit/$person->id");
        }
        return $rc;
    }
}
