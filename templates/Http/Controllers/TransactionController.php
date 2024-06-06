<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Module;
use App\Helpers\Helper;
use App\Models\Account;
use App\Models\Mandator;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use App\Models\Transaction;
use App\Helpers\EmailHelper;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Account $account, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/transaction-index/$account->id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'name' => '',
                    'info' => '',
                    'amount' => '0.00',
                    'transactiontype_scope' => '',
                    'transactionstate_scope' => 1331,
                    'date' => (new \DateTime('now'))->format('Y-m-d'),
                    'owner_id' => auth()->user()->id
                ];
            }
            $optionsTransactiontype = SProperty::optionsByScope('transactiontype', $fields['transactiontype_scope'], '-');
            $optionsTransactionstate = SProperty::optionsByScope('transactionstate', $fields['transactionstate_scope'], '-');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $fields['account_id'] = $account->id;
            $fields['account'] = $account->name;
            $fields['mandator'] = Mandator::find($account->mandator_id)->name;
            $context = new ContextLaraKnife($request, $fields);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-create', 0, $account->id);
            $rc = view('transaction.create', [
                'context' => $context,
                'optionsTransactiontype' => $optionsTransactiontype,
                'optionsTransactionstate' => $optionsTransactionstate,
                'optionsOwner' => $optionsOwner,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    public function createDocument(Transaction $transaction, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/transaction-index_documents/$transaction->id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'filename' => '',
                    'filegroup_scope' => '1101',
                    'visibility_scope' => '1091',
                    'owner_id' => auth()->id()
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup_scope'], '-');
            $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope'], '-');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $id = $transaction->account_id;
            $account = Account::find($id);
            $fields['account_id'] = $account->id;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $context = new ContextLaraKnife($request, $fields);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-create-document', 6, $transaction->id, strval($mandator->id), $account->id);
            $rc = view('transaction.create_document', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'transaction' => $transaction,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/transaction-index/$transaction->account_id");
        } else {
            $fields = $request->all();
            if ($request->btnSubmit === 'btnStore') {
                $validator = Validator::make($fields, $this->rules(false));
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    $rc = back()->withErrors($validator)->withInput();
                } else {
                    $validated = $validator->validated();
                    $validated['info'] = strip_tags($validated['info']);
                    $transaction->update($validated);
                }
            }
            if (count($fields) === 0) {
                $fields = [
                    'name' => $transaction->name,
                    'info' => $transaction->info,
                    'amount' => $transaction->amount,
                    'transactiontype_scope' => $transaction->transactiontype_scope,
                    'transactionstate_scope' => $transaction->transactionstate_scope,
                    'date' => $transaction->date,
                ];
            }
            $optionsTransactiontype = SProperty::optionsByScope('transactiontype', $transaction->transactiontype_scope, '');
            $optionsTransactionstate = SProperty::optionsByScope('transactionstate', $transaction->transactionstate_scope, '');
            $id = $transaction->account_id;
            $account = Account::find($id);
            $fields['account_id'] = $account->id;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $context = new ContextLaraKnife($request, $fields, $transaction);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-edit', 3, $transaction->id, strval($mandator->id), $account->id);
            $rc = view('transaction.edit', [
                'context' => $context,
                'optionsTransactiontype' => $optionsTransactiontype,
                'optionsTransactionstate' => $optionsTransactionstate,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }
    public function editDocument(File $file, Transaction $transaction, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/transaction-index_documents/$file->reference_id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'description' => '',
                    'filename' => '',
                    'filegroup_scope' => '',
                    'visibility_scope' => '',
                    'owner_id' => ''
                ];
            }
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $file->filegroup_scope, '');
            $optionsVisibility = SProperty::optionsByScope('visibility', $file->visibility_scope, '');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $file->user_id, __('<Please select>'));
            $id = $transaction->account_id;
            $account = Account::find($id);
            $fields['account_id'] = $account->id;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $context = new ContextLaraKnife($request, null, $file);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-edit-document', 6, $transaction->id, $file->id, $account->id);
            $rc = view('transaction.edit_document', [
                'context' => $context,
                'optionsFilegroup' => $optionsFilegroup,
                'optionsVisibility' => $optionsVisibility,
                'optionsUser' => $optionsUser,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }

    public function editOwner(Transaction $transaction, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect("/transaction-index/$transaction->reference_id");
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'owner_id' => $transaction->owner_id,
                    'recipients' => '',
                ];
            }
            ViewHelper::adaptCheckbox($fields, 'withEmail');
            if ($request->btnSubmit === 'btnStore' && ($owner = $fields['owner_id']) != null) {
                $transaction->update(['owner_id' => $fields['owner_id']]);
                if ($fields['withEmail']) {
                    $this->sendEmail($transaction->owner_id, $transaction);
                }
            }
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $transaction->owner_id, __('<Please select>'));
            $account = Account::find($accountId = $transaction->account_id);
            $fields['account_id'] = $account->id;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-edit', 4, $transaction->id, $transaction->options, $account->id);
            $context = new ContextLaraKnife($request, $fields, $transaction);
            $rc = view('transaction.edit_owner', [
                'context' => $context,
                'optionsOwner' => $optionsOwner,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
        return $rc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $transaction->delete();
        }
        return redirect('/transaction-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Account $account, Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect("/transaction-create/$account->id");
        } else {
            $sql = "
SELECT t0.*,
  t1.name as transactiontype_scope,
  t2.name as transactionstate_scope,
  t3.name as owner
FROM transactions t0
LEFT JOIN sproperties t1 ON t1.id=t0.transactiontype_scope
LEFT JOIN sproperties t2 ON t2.id=t0.transactionstate_scope
LEFT JOIN users t3 ON t3.id=t0.owner_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $from = DateTimeHelper::firstDayOfYear()->format('Y-m-d');
                $fields = [
                    'transactiontype' => '',
                    'transactionstate' => '',
                    'owner' => auth()->user()->id,
                    'text' => '',
                    'from' => $from,
                    'until' => '',
                    '_sortParams' => 'date:desc;id:asc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionConstComparison($conditions, $parameters, 'account_id', $account->id);
                ViewHelper::addConditionComparism($conditions, $parameters, 'transactiontype_scope', 'transactiontype');
                ViewHelper::addConditionComparism($conditions, $parameters, 'transactionstate_scope', 'transactionstate');
                ViewHelper::addConditionComparism($conditions, $parameters, 'owner_id', 'owner');
                ViewHelper::addConditionPattern($conditions, $parameters, 'name,info', 'text');
                ViewHelper::addConditionComparism($conditions, $parameters, 'from', 'date', '>=');
                ViewHelper::addConditionComparism($conditions, $parameters, 'until', 'date', '<=');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $fields['sum'] = DbHelper::buildSum($records, 'amount');
            $optionsTransactiontype = SProperty::optionsByScope('transactiontype', $fields['transactiontype'], 'all');
            $optionsTransactionstate = SProperty::optionsByScope('transactionstate', $fields['transactionstate'], 'all');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner'], __('<Please select>'));
            $fields['account_id'] = $account->id;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $context = new ContextLaraKnife($request, $fields);
            $navigationTabInfo = ViewHelperLocal::getNavigationTabInfo('account-edit', 3, $account->id, null, $mandator->id);
            return view('transaction.index', [
                'context' => $context,
                'records' => $records,
                'optionsTransactiontype' => $optionsTransactiontype,
                'optionsTransactionstate' => $optionsTransactionstate,
                'optionsOwner' => $optionsOwner,
                'pagination' => $pagination,
                'navTabsInfo' => $navigationTabInfo
            ]);
        }
    }
    public function indexDocuments(Transaction $transaction, Request $request)
    {
        $moduleId = Module::idOfModule('Transaction');
        if ($request->btnSubmit === 'btnNew') {
            return redirect("/transaction-create_document/$transaction->id");
        } else {
            $sql = "
SELECT t0.*, t1.name as filegroup_scope, t2.name as user_id 
FROM files t0
LEFT JOIN sproperties t1 ON t1.id=t0.filegroup_scope
LEFT JOIN sproperties t2 ON t2.id=t0.user_id
";
            $parameters = [];
            $conditions = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'filegroup' => '',
                    'owner' => auth()->id(),
                    'text' => '',
                    'filegroup_scope' => '1101',
                    '_sortParams' => 'id:desc',
                ];
            } else {
                ViewHelper::addConditionPattern($conditions, $parameters, 'title,description,filename', 'text');
            }
            ViewHelper::addConditionConstComparison($conditions, $parameters, 'module_id', $moduleId);
            ViewHelper::addConditionConstComparison($conditions, $parameters, 'reference_id', $transaction->id);
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsFilegroup = SProperty::optionsByScope('filegroup', $fields['filegroup'], 'all');
            $optionsUser = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner']);
            $account = Account::find($transaction->account_id);
            $fields['account_id'] = $account->id;
            $mandator = Mandator::find($account->mandator_id);
            $fields['mandator'] = $mandator->name;
            $fields['account'] = $account->name;
            $fields['accountAmount'] = $account->amount;
            $fields['transaction_id'] = $transaction->id;
            $context = new ContextLaraKnife($request, $fields);
            $fileController = new FileController();
            $context->setCallback('buildAnchor', $fileController, 'buildAnchor');
            $navTabInfo = ViewHelperLocal::getNavigationTabInfo('transaction-edit', 5, $account->id, null, $mandator->id);
            return view('transaction.index_documents', [
                'context' => $context,
                'records' => $records,
                'pagination' => $pagination,
                'navigationTabs' => $navTabInfo,
                'transaction' => $transaction,
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
            'amount' => 'required',
            'transactiontype_scope' => 'required',
            'transactionstate_scope' => 'required',
            'date' => '',
            'owner_id' => 'required'
        ];
        return $rc;
    }
    public static function routes()
    {
        Route::get('/transaction-index/{account}', [TransactionController::class, 'index'])->middleware('auth');
        Route::post('/transaction-index/{account}', [TransactionController::class, 'index'])->middleware('auth');
        Route::get('/transaction-create/{account}', [TransactionController::class, 'create'])->middleware('auth');
        Route::put('/transaction-store/{account}', [TransactionController::class, 'store'])->middleware('auth');
        Route::put('/transaction-store_document/{transaction}', [TransactionController::class, 'storeDocument'])->middleware('auth');
        Route::put('/transaction-update_document/{file}/{transaction}', [TransactionController::class, 'updateDocument'])->middleware('auth');
        Route::post('/transaction-edit/{transaction}', [TransactionController::class, 'edit'])->middleware('auth');
        Route::get('/transaction-edit/{transaction}', [TransactionController::class, 'edit'])->middleware('auth');
        Route::post('/transaction-editowner/{transaction}', [TransactionController::class, 'editOwner'])->middleware('auth');
        Route::get('/transaction-editowner/{transaction}', [TransactionController::class, 'editOwner'])->middleware('auth');
        Route::post('/transaction-edit_document/{file}/{transaction}', [TransactionController::class, 'editDocument'])->middleware('auth');
        Route::get('/transaction-edit_document/{file}/{transaction}', [TransactionController::class, 'editDocument'])->middleware('auth');
        Route::post('/transaction-index_documents/{transaction}', [TransactionController::class, 'indexDocuments'])->middleware('auth');
        Route::get('/transaction-index_documents/{transaction}', [TransactionController::class, 'indexDocuments'])->middleware('auth');
        Route::get('/transaction-show/{transaction}/delete', [TransactionController::class, 'show'])->middleware('auth');
        Route::get('/transaction-create_document/{transaction}', [TransactionController::class, 'createDocument'])->middleware('auth');
        Route::post('/transaction-create_document/{transaction}', [TransactionController::class, 'createDocument'])->middleware('auth');
        Route::delete('/transaction-show/{transaction}/delete', [TransactionController::class, 'destroy'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/transaction-index')->middleware('auth');
        } else {
            $fields = $request->all();
            $optionsTransactiontype = SProperty::optionsByScope('transactiontype', $transaction->transactiontype_scope, '');
            $optionsTransactionstate = SProperty::optionsByScope('transactionstate', $transaction->transactionstate_scope, '');
            $optionsAccount = DbHelper::comboboxDataOfTable('accounts', 'name', 'id', $fields['account_id'], __('<Please select>'));
            $optionsTwin = DbHelper::comboboxDataOfTable('transactions', 'name', 'id', $fields['twin_id'], __('<Please select>'));
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner_id'], __('<Please select>'));
            $context = new ContextLaraKnife($request, null, $transaction);
            $rc = view('transaction.show', [
                'context' => $context,
                'optionsTransactiontype' => $optionsTransactiontype,
                'optionsTransactionstate' => $optionsTransactionstate,
                'optionsAccount' => $optionsAccount,
                'optionsTwin' => $optionsTwin,
                'optionsOwner' => $optionsOwner,
                'mode' => 'delete'
            ]);
        }
        return $rc;
    }
    private function sendEmail(int $userId, Transaction $transaction)
    {
        $user = User::find($userId);
        $account = Account::find($transaction->account_id);
        $mandator = Mandator::find($account->mandator_id);
        EmailHelper::sendMail('transaction.notification', $user->email, [
            'name' => $user->name,
            'title' => "$account->name $transaction->name",
            'transaction' => $transaction->name,
            'mandator' => $mandator->name,
            'account' => $account->name,
            'body' => $transaction->info,
            'from' => auth()->user()->name,
            'link' => ViewHelper::buildLink("/transaction-edit/$transaction->id")
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Account $account, Request $request)
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
                $validated['account_id'] = $account->id;
                $transaction = Transaction::create($validated);
                $account->update(['amount' => $account->amount + floatval($fields['amount'])]);
                $rc = redirect("/transaction-edit/$transaction->id");
            }
        }
        if ($rc == null) {
            $rc = redirect("/transaction-index/$account->id");
        }
        return $rc;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeDocument(Transaction $transaction, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $validator = Validator::make($fields, ['title' => 'required', 'filegroup_scope' => 'required', 'visibility_scope' => 'required']);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $controller = new FileController();
                $fields['module_id'] = Module::idOfModule('Transaction');
                $fields['reference_id'] = $transaction->id;
                $fields['description'] = strip_tags($fields['description']);
                $controller->storeFile($request, $fields);
            }
        }
        if ($rc == null) {
            $rc = redirect("/transaction-index_documents/$transaction->id");
        }
        return $rc;
    }
    public function updateDocument(File $file, Transaction $transaction, Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnStore') {
            $fields = $request->all();
            $fields['description'] = strip_tags($fields['description']);
            $validator = Validator::make($fields, ['title' => 'required']);
            if ($validator->fails()) {
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $fields2 = $request->only(['title', 'description', 'filegroup_scope']);
                $file->update($fields2);
            }
        }
        if ($rc == null) {
            $rc = redirect("/transaction-index_documents/$file->reference_id");
        }
        return $rc;
    }
}
