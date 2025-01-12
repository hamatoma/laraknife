<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/group-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'info' => '',
                    'members' => ''
                ];
            }
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('group.create', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/group-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => $group->name,
                    'info' => $group->info,
                    'member' => '',
                    'members' => $group->members ?? ',',
                    'names' => $this->editIdsToNames($group->members)
                ];
            }
            $member = $fields['member'];
            if ($request->btnSubmit === 'btnStore') {
                $rc = $this->update($group, $request);
            }
            if ($request->btnSubmit === 'btnChange' && $member!= null) {
                if (strpos($fields['members'], ",$member,") === false){
                    $fields['members'] .= "$member,";
                } else {
                    $fields['members'] = str_replace("$member,", '', $fields['members']);
                }
                if (! str_starts_with($fields['members'], ',')){
                    $fields['members'] = ',' . $fields['members'];
                }
                $fields['names'] = $this->editIdsToNames($fields['members']);
            }
            if ($rc == null){
                $context = new ContextLaraKnife($request, $fields, $group);
                 $optionsMember = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['member'], __('<Please select>'));
                
                $rc = view('group.edit', [
                    'context' => $context,
                    'optionsMember' => $optionsMember,
                    'groupController' => $this
                ]);
            }
        }
        return $rc;
    }
    public function editIdsToNames(?string $ids): string
    {
        $userPerLine = 5;
        $rc = '';
        $ids2 = explode(',', $ids ?? '');
        $users = [];
        foreach ($ids2 as $id) {
            if ($id !== '') {
                if (($user = User::find(intval($id))) != null) {
                    array_push($users, $user->name);
                }
            }
        }
        asort($users);
        for ($ix = 0; $ix < count($users); $ix++){
            $rc .= $ix > 0 && $ix % $userPerLine == 0 ? "\n" : ($ix == 0 ? '' : ' ');
            $rc .= $users[$ix];
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $group->delete();
        }
        return redirect('/group-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/group-create');
        } else {
            $sql = "
SELECT t0.*
FROM groups t0
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'name' => '',
                    'info' => '',
                    'member' => '',
                    '_sortParams' => 'name:asc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionPattern($fields, $conditions, $parameters, 'name,info', 'text');
                ViewHelper::addConditionFindInList($conditions, $parameters, 'members', $fields['member']);
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $context = new ContextLaraKnife($request, $fields);
            $context->setCallback('members', $this, 'editIdsToNames');
            $optionsMember = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['member']);
            return view('group.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'optionsMember' => $optionsMember
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
            'name' => 'required',
            'info' => '',
        ];
        if (!$isCreate) {
            $rc['members'] = 'required';
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/group-index', [GroupController::class, 'index'])->middleware('auth');
        Route::post('/group-index', [GroupController::class, 'index'])->middleware('auth');
        Route::get('/group-create', [GroupController::class, 'create'])->middleware('auth');
        Route::put('/group-store', [GroupController::class, 'store'])->middleware('auth');
        Route::post('/group-edit/{group}', [GroupController::class, 'edit'])->middleware('auth');
        Route::get('/group-edit/{group}', [GroupController::class, 'edit'])->middleware('auth');
        Route::get('/group-show/{group}/delete', [GroupController::class, 'show'])->middleware('auth');
        Route::delete('/group-show/{group}/delete', [GroupController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Group $group, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/group-index')->middleware('auth');
        } else {
            $fields = $request->all();
            $fields['members'] = $this->editIdsToNames($group->members);
            $context = new ContextLaraKnife($request, $fields, $group);
            $rc = view('group.show', [
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
        $group = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $group = Group::create($validated);
            }
        }
        if ($rc == null) {
            $url = $group == null ? '/group-index' : "/group-edit/$group->id";
            $rc = redirect($url);
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Group $group, Request $request)
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
                $validated['members'] = strip_tags($validated['members']);
                $group->update($validated);
            }
        }
        return $rc;
    }
}
