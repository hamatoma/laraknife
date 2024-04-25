<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Page;
use App\Models\Module;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\MediaWiki;
use App\Helpers\FileHelper;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\TextProcessor;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    private function asPreview(Page &$page, ?string $contents = null): string
    {
        $contents ??= $page->contents;
        $type = $page->markup_scope;
        switch ($type) {
            case 1122: // mediawiki
                $wiki = new MediaWiki();
                $text = $wiki->ToHtml($contents);
                break;
            case 1223: // html
                $text = $contents;
                break;
            default:
            case 1121: // plain text
                $text = '<p>' . str_replace('/\r?\n/', "</p>\n<p>", $contents) . "</p>";
                break;
        }
        return $text;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-index');
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => '',
                    'name' => '',
                    'contents' => '',
                    'info' => '',
                    'pagetype_scope' => '',
                    'markup_scope' => '1122',
                    'language_scope' => SProperty::idOfLocalization(auth()->user()->localization),
                    'order' => '0',
                    'columns' => '1',
                    'audio_id' => '',
                ];
            }
            $optionsPagetype = SProperty::optionsByScope('pagetype', $fields['pagetype_scope'], '-');
            $optionsMarkup = SProperty::optionsByScope('markup', $fields['markup_scope'], '-');
            $optionsLanguage = SProperty::optionsByScope('localization', $fields['language_scope'], '-');
            $context = new ContextLaraKnife($request, $fields);
            $rc = view('page.create', [
                'context' => $context,
                'optionsPagetype' => $optionsPagetype,
                'optionsMarkup' => $optionsMarkup,
                'optionsLanguage' => $optionsLanguage,
            ]);
        }
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-index');
        } elseif ($request->btnSubmit === 'btnStore') {
            $rc = $this->update($page, $request);
        } else {
            $fields = $request->all();
            if (count($fields) === 0) {
                $fields = [
                    'title' => $page->title,
                    'name' => $page->title,
                    'columns' => $page->columns,
                    'contents' => $page->contents,
                    'info' => $page->info,
                    'pagetype_scope' => $page->pagetype_scope,
                    'markup_scope' => $page->markup_scope,
                    'language_scope' => $page->language_scope,
                    'order' => $page->order ?? '0',
                    'audio_id' => $page->audio_id
                ];
            } else {
                $fields['pagetype_scope'] = $page->pagetype_scope;
                $fields['markup_scope'] = $page->markup_scope;
                $fields['language_scope'] = $page->language_scope;
            }
            $optionsPagetype = SProperty::optionsByScope('pagetype', $page->pagetype_scope, '');
            $optionsMarkup = SProperty::optionsByScope('markup', $page->markup_scope, '');
            $optionsLanguage = SProperty::optionsByScope('localization', $page->language_scope, '');
            $fields = $request->btnSubmit !== 'btnPreview' ? null : ['preview' => $this->asPreview($page)];
            $context = new ContextLaraKnife($request, $fields, $page);
            $rc = view('page.edit', [
                'context' => $context,
                'optionsPagetype' => $optionsPagetype,
                'optionsMarkup' => $optionsMarkup,
                'optionsLanguage' => $optionsLanguage,
            ]);
        }
        return $rc;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, Request $request)
    {
        if ($request->btnSubmit === 'btnDelete') {
            $page->delete();
        }
        return redirect('/page-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        if ($request->btnSubmit === 'btnNew') {
            return redirect('/page-create');
        } else {
            $sql = "
SELECT t0.*,
  t1.name as pagetype,
  t2.name as markup,
  t3.shortname as language,
  t4.name as owner
FROM pages t0
LEFT JOIN sproperties t1 ON t1.id=t0.pagetype_scope
LEFT JOIN sproperties t2 ON t2.id=t0.markup_scope
LEFT JOIN sproperties t3 ON t3.id=t0.language_scope
LEFT JOIN sproperties t4 ON t4.id=t0.owner_id
";
            $parameters = [];
            $fields = $request->all();
            if (count($fields) == 0) {
                $fields = [
                    'pagetype' => '',
                    'markup' => '',
                    'title' => '',
                    'contents' => '',
                    'owner' => '',
                    '_sortParams' => 'title:asc;id:asc'
                ];
            } else {
                $conditions = [];
                ViewHelper::addConditionComparism($conditions, $parameters, 'pagetype_scope', 'pagetype');
                ViewHelper::addConditionComparism($conditions, $parameters, 'markup_scope', 'markup');
                ViewHelper::addConditionComparism($conditions, $parameters, 'language_scope', 'language');
                ViewHelper::addConditionComparism($conditions, $parameters, 'owner_id', 'owner');
                ViewHelper::addConditionPattern($conditions, $parameters, 'name,title,info', 'title');
                ViewHelper::addConditionPattern($conditions, $parameters, 'info,contents', 'contents');
                $sql = DbHelper::addConditions($sql, $conditions);
            }
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            $optionsPagetype = SProperty::optionsByScope('pagetype', $fields['pagetype'], 'all');
            $optionsMarkup = SProperty::optionsByScope('markup', $fields['markup'], 'all');
            $optionsLanguage = SProperty::optionsByScope('localization', $fields['markup'], 'all');
            $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner']);
            $context = new ContextLaraKnife($request, $fields);
            return view('page.index', [
                'context' => $context,
                'records' => $records,
                'optionsPagetype' => $optionsPagetype,
                'optionsMarkup' => $optionsMarkup,
                'optionsLanguage' => $optionsLanguage,
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
            'title' => 'required',
            'name' => 'required',
            'contents' => 'required',
            'info' => '',
            'order' => 'integer|min:0|max:9999',
            'columns' => 'integer|min:1|max:4'
        ];
        if ($isCreate) {
            $rc['markup_scope'] = 'required';
            $rc['pagetype_scope'] = 'required';
            $rc['language_scope'] = 'required';
        }
        return $rc;
    }
    public static function routes()
    {
        Route::get('/page-index', [PageController::class, 'index'])->middleware('auth');
        Route::post('/page-index', [PageController::class, 'index'])->middleware('auth');
        Route::get('/page-create', [PageController::class, 'create'])->middleware('auth');
        Route::put('/page-store', [PageController::class, 'store'])->middleware('auth');
        Route::post('/page-edit/{page}', [PageController::class, 'edit'])->middleware('auth');
        Route::get('/page-edit/{page}', [PageController::class, 'edit'])->middleware('auth');
        Route::post('/page-update/{page}', [PageController::class, 'update'])->middleware('auth');
        Route::get('/page-show/{page}/delete', [PageController::class, 'show'])->middleware('auth');
        Route::delete('/page-show/{page}/delete', [PageController::class, 'destroy'])->middleware('auth');
        Route::get('/page-showpretty/{page}', [PageController::class, 'showPretty'])->middleware('auth');
        Route::post('/page-showpretty/{page}', [PageController::class, 'showPretty'])->middleware('auth');
        Route::get('/page-showmenu/{title}', [PageController::class, 'showMenu'])->middleware('auth');
        Route::get('/page-showhelp/{title}', [PageController::class, 'showHelp'])->middleware('auth');
        Route::get('/page-showbyname/{name}/{pageType}', [PageController::class, 'showByName'])->middleware('auth');
    }
    /**
     * Display the specified resource.
     */
    public function show(Page $page, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-index')->middleware('auth');
        } else {
            $optionsPagetype = SProperty::optionsByScope('pagetype', $page->pagetype_scope, '');
            $optionsMarkup = SProperty::optionsByScope('markup', $page->markup_scope, '');
            $context = new ContextLaraKnife($request, null, $page);
            $rc = view('page.show', [
                'context' => $context,
                'optionsPagetype' => $optionsPagetype,
                'optionsMarkup' => $optionsMarkup,
                'mode' => 'delete'
            ]);
        }
        return $rc;
    }
    public function showPretty(Page $page, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-index')->middleware('auth');
        } else {
            $text = $this->asPreview($page);
            $link = $page->audio_id == null ? null : File::relativeFileLink($page->audio_id);
            $context = new ContextLaraKnife($request, ['text' => $text, 'link' => $link], $page);
            $rc = view('page.showcol1', [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    public function showByName(string $name, int $pageType, Request $request)
    {
        $page = Page::where(['name' => $name, 'pagetype_scope' => $pageType])->first();
        if ($page == null) {
            $context = new ContextLaraKnife($request, ['text' => "invalid reference: $name $pageType"]);
            $rc = $rc = view('page.unknown', [
                'context' => $context,
            ]);
        } else {
            $params = [];
            $columns = $page->columns;
            if ($columns <= 1) {
                $params['text'] = $this->asPreview($page);
            } else {
                $cols = explode('----', $page->contents);
                for ($no = 1; $no <= $columns; $no++) {
                    $params["text$no"] = $this->asPreview($page, $cols[$no - 1]);
                }
            }
            $context = new ContextLaraKnife($request, $params, $page);
            $rc = view("page.showcol$columns", [
                'context' => $context,
            ]);
        }
        return $rc;
    }
    public function showHelp(string $title, Request $request)
    {
        $rc = $this->showByName($title, 1142, $request);
        return $rc;
    }

    public function showMenu(string $title, Request $request)
    {
        $rc = $this->showByName($title, 1141, $request);
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
            ViewHelper::addFieldIfMissing($fields, 'owner_id', auth()->id());
            $fields['name'] = StringHelper::textToUrl(empty($fields['name']) ? $fields['title'] : $fields['name']);
            $lang = auth()->user()->localization;
            $lang2 = SProperty::byScopeAndName('localization', $lang, 'shortname');
            ViewHelper::addFieldIfMissing($fields, 'language_scope', $lang2);
            $validator = Validator::make($fields, $this->rules(true));
            if ($validator->fails()) {
                $errors = $validator->errors();
                $rc = back()->withErrors($validator)->withInput();
            } else {
                $validated = $validator->validated();
                $validated['info'] = strip_tags($validated['info']);
                $validated['owner_id'] = $fields['owner_id'];
                $validated['contents'] = TextProcessor::expandStarItems(strip_tags($validated['contents']));
                Page::create($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect('/page-index');
        }
        return $rc;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Page $page, Request $request)
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
                if (empty($page->audio_id) && $request->file('file') != null) {
                    $filename = FileHelper::textToFilename($page->title);
                    $moduleId = Module::idOfModule('Page');
                    $fileId = File::storeFile($request, $page->title, 1103, 1091, 'audio file of page', $filename, $moduleId, $page->id);
                    $validated['audio_id'] = $fileId;
                }
                $page->update($validated);
            }
        }
        if ($rc == null) {
            $rc = redirect("/page-edit/$page->id");
        }
        return $rc;
    }
}
