<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Account;
use App\Models\Mandator;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Mandator $mandator, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/account-index/$mandator->id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'info' => '',
                    'mandator_id' => '',
                    'amount' => '0.00'
                ];
            }
            $fields['mandator'] = $mandator->name;
            $fields['mandator_id'] = $mandator->id;
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('account.create', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/account-index/$account->mandator_id");
        } else {
            $fields = $request->all();
            if ($request->btnSubmit === 'btnStore') {
                $fields = $request->all();
                $validator = Validator::make($fields, ['name' => Rule::unique('accounts')->ignore($account->id)]);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $rc = back()->withErrors($validator)->withInput();
                } else {
                    $validated = $validator->validated();
                    $validated['info'] = strip_tags($fields['info']);
                    $account->update($validated);
                }
            }
            if ($rc == null) {
                if (count($fields) === 0) {
                    $fields = [
                        'name' => $account->name,
                        'info' => $account->info,
                        'mandator_id' => $account->mandator_id,
                        'mandator' => Mandator::find($account->mandator_id)->name
                    ];
                }
                $context = new ContextLaraKnife($request, $fields, $account);
                $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('account-edit', 0, $account->id);
                $rc = view('account.edit', [
                    'context' => $context,
                    'navTabsInfo' => $navigationTabInfo
                ]);
            }
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $account->delete();
        }
        return redirect('/account-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Mandator $mandator, Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            $url = "/account-create/$mandator->id";
            $rc = redirect($url);
        } else {
            $sql = "
SELECT t0.*,
  t1.name as mandator
FROM accounts t0
LEFT JOIN mandators t1 ON t1.id=t0.mandator_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'text' => '',
                    '_sortParams' => 'name:asc;id:desc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionPattern($conditions, $parameters, 't0.name,t0.info', 'text');
                ViewHelper::addConditionPattern($conditions, $parameters, 'info');
                ViewHelper::addConditionConstComparison($conditions, $parameters, 'mandator_id', $mandator->id);
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $fields['mandator_id'] = $mandator->id;
            $context = new ContextLaraKnife($request, $fields);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('mandator-edit', 1, $mandator->id);
            $rc = view('account.index', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'navTabsInfo' => $navigationTabInfo
            ]);
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
            'info' => '',
            'mandator_id' => 'required',
            'amount' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/account-index/{mandator}', [AccountController::class, 'index'])->middleware('auth');
        Route::post('/account-index/{mandator}', [AccountController::class, 'index'])->middleware('auth');
        Route::get('/account-create/{mandator}', [AccountController::class, 'create'])->middleware('auth');
        Route::put('/account-store/{mandator}', [AccountController::class, 'store'])->middleware('auth');
        Route::post('/account-edit/{account}', [AccountController::class, 'edit'])->middleware('auth');
        Route::get('/account-edit/{account}', [AccountController::class, 'edit'])->middleware('auth');
        Route::get('/account-show/{account}/delete', [AccountController::class, 'show'])->middleware('auth');
        Route::delete('/account-show/{account}/delete', [AccountController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Account $account, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/account-index')->middleware('auth');
        } else {
            $context = new ContextLaraKnife($request, null, $account);
            $rc = view('account.show', [
                'context' => $context,
                'mode' => 'delete'
            ]);
        }
        return $rc;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Mandator $mandator, Request $request)
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
                $validated['mandator_id'] = $mandator->id;
                Account::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect("/account-index/$mandator->id");
        }
        return $rc;
    }
}
