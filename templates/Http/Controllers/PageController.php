<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Page;
use App\Models\Change;
use App\Models\Module;
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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    private function asHtml(Page &$page, ?string $contents = null): string
    {
        $contents ??= $page->contents;
        $rc = ViewHelper::asHtml($contents, $page->markup_scope);
        return $rc;
    }
    private function asPreview(Page &$page, ?string $contents = null): string
    {
        $contents ??= $page->contents;
        $type = $page->markup_scope;
        switch ($type) {
            case 1122: // mediawiki
                $wiki = new MediaWiki();
                $text = $wiki->toHtml($contents);
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
    public function export(array &$records)
    {
        $fn = FileHelper::buildExportName('page-export', '.txt');
        $sep = '~' . StringHelper::randomString(StringHelper::$charSetAlphaNumeric, 3) . '%';
        $count = count($records);
        $host = env('APP_URL');
        $date = (new \DateTime('now'))->format('Y-m-d H:i');
        $contents = ":LaraKnife-Export
:host=$host
:exported=$date
:separator=$sep
:records=$count
";
        $recordNo = 0;
        foreach ($records as &$record) {
            $recordNo++;
            $contents .= ":action=insert
:table=pages
!id=page$recordNo
!reference_id=$record->reference_id
!audio_id=$record->audio_id
!owner_id=$record->owner_id
!previous_id=$record->previous_id
!next_id=$record->next_id
!up_id=$record->up_id
title=$record->title
name=$record->name
pagetype_scope=$record->pagetype_scope
markup_scope=$record->markup_scope
order=$record->order
language_scope=$record->language_scope
~contents=$record->contents
$sep
~info=$record->info
$sep
";
        }
        file_put_contents($fn, $contents);
        $rc = redirect('/export-index');
        return $rc;
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page, Request $request)
    {
        $fields = $request->all();
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-index');
        } else {
            if (count($fields) === 0) {
                $fields = [
                    'title' => $page->title,
                    'name' => $page->name,
                    'columns' => $page->columns,
                    'contents' => $page->contents,
                    'info' => $page->info,
                    'pagetype_scope' => $page->pagetype_scope,
                    'markup_scope' => $page->markup_scope,
                    'language_scope' => $page->language_scope,
                    'order' => $page->order ?? '0',
                    'audio_id' => $page->audio_id,
                    'previous_id' => $page->previous_id,
                    'next_id' => $page->next_id,
                    'up_id' => $page->up_id,
                    'message' => ''
                ];
            } else {
                if (strpos($fields['title'], '"') !== false) {
                    $fields['title'] = str_replace('"', "\u{201F}", $fields['title']);
                }
                $fields['markup_scope'] = $page->markup_scope;
                $fields['language_scope'] = $page->language_scope;
            }
            $fields['contents'] = MediaWiki::expandStarItems($fields['contents']);
            if ($request->btnSubmit === 'btnStore') {
                $this->update($page, $request, $fields);
            }
            $optionsPagetype = SProperty::optionsByScope('pagetype', $page->pagetype_scope, '');
            $optionsMarkup = SProperty::optionsByScope('markup', $page->markup_scope, '');
            $optionsLanguage = SProperty::optionsByScope('localization', $page->language_scope, '');
            if ($request->btnSubmit === 'btnPreview') {
                $wiki = new MediaWiki();
                //$wiki->setClozeParameters('preview');
                $wikiText = $fields['contents'];
                $fields['preview'] = $wiki->toHtml($wikiText);
                if ($wikiText !== $page->contents) {
                    $page->contents = $wikiText;
                    $fields['message'] = '+++ ' . __('There are corrections:') . $wiki->corrections;
                }
            }
            if ($page->audio_id != null) {
                $file = File::find($page->audio_id);
                $fields['audio'] = $file->filename;
            }
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
     * Show the form for editing the specified resource.
     */
    public function editWiki(Page $page, Request $request)
    {
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/page-showpretty/' . $page->id);
        } else {
            $fields = $request->all();
            if (count($fields) <= 3) {
                $fields = [
                    'title' => $page->title,
                    'name' => $page->name,
                    'columns' => $page->columns,
                    'contents' => $page->contents,
                    'info' => $page->info,
                    'pagetype_scope' => $page->pagetype_scope,
                    'markup_scope' => $page->markup_scope,
                    'language_scope' => $page->language_scope,
                    'order' => $page->order ?? '0',
                    'audio_id' => $page->audio_id,
                    'previous_id' => $page->previous_id,
                    'next_id' => $page->next_id,
                    'up_id' => $page->up_id,
                    'message' => ''
                ];
            } else {
                if (strpos($fields['title'], '"') !== false) {
                    $fields['title'] = str_replace('"', "\u{201F}", $fields['title']);
                }
                $fields['markup_scope'] = $page->markup_scope;
                $fields['language_scope'] = $page->language_scope;
            }
            $fields['contents'] = MediaWiki::expandStarItems($fields['contents']);
            if ($request->btnSubmit === 'btnStore') {
                $this->update($page, $request, $fields);
            }
            $optionsPagetype = SProperty::optionsByScope('pagetype', $page->pagetype_scope, '');
            $optionsMarkup = SProperty::optionsByScope('markup', $page->markup_scope, '');
            $optionsLanguage = SProperty::optionsByScope('localization', $page->language_scope, '');
            if ($request->btnSubmit === 'btnPreview') {
                $wiki = new MediaWiki();
                //$wiki->setClozeParameters('preview');
                $wikiText = $fields['contents'];
                $fields['preview'] = $wiki->toHtml($wikiText);
                if ($wikiText !== $page->contents) {
                    $page->contents = $wikiText;
                    $fields['message'] = '+++ ' . __('There are corrections:') . $wiki->corrections;
                }
            }
            if ($page->audio_id != null) {
                $file = File::find($page->audio_id);
                $fields['audio'] = $file->filename;
            }
            $context = new ContextLaraKnife($request, $fields, $page);
            $rc = view('page.editwiki', [
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
            Change::createFromModel($page, Change::$DELETE, 'Page');
         }
        return redirect('/page-index');
    }
    /**
     * Display the database records of the resource.
     */
    public function index(Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnNew') {
            $rc = redirect('/page-create');
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
LEFT JOIN users t4 ON t4.id=t0.owner_id
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
            }
            $conditions = [];
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'pagetype_scope', 'pagetype');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'markup_scope', 'markup');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'language_scope', 'language');
            ViewHelper::addConditionComparison($fields, $conditions, $parameters, 'owner_id', 'owner');
            ViewHelper::addConditionPattern($conditions, $parameters, 't0.name,title,t0.info', 'title');
            ViewHelper::addConditionPattern($conditions, $parameters, 't0.info,contents', 'contents');
            $sql = DbHelper::addConditions($sql, $conditions);
            $sql = DbHelper::addOrderBy($sql, $fields['_sortParams']);
            $pagination = new Pagination($sql, $parameters, $fields);
            $records = $pagination->records;
            if ($request->btnSubmit === 'btnExport') {
                $rc = $this->export($records);
            }
            if ($rc == null) {
                $optionsPagetype = SProperty::optionsByScope('pagetype', $fields['pagetype'], 'all');
                $optionsMarkup = SProperty::optionsByScope('markup', $fields['markup'], 'all');
                $optionsLanguage = SProperty::optionsByScope('localization', $fields['markup'], 'all');
                $optionsOwner = DbHelper::comboboxDataOfTable('users', 'name', 'id', $fields['owner']);
                $context = new ContextLaraKnife($request, $fields);
                $rc = view('page.index', [
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
        return $rc;
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
            'pagetype_scope' => $isCreate ? 'required' : ''
        ];
        if ($isCreate) {
            $rc['markup_scope'] = 'required';
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
        Route::get('/page-editwiki/{page}', [PageController::class, 'editWiki'])->middleware('auth');
        Route::post('/page-editwiki/{page}', [PageController::class, 'editWiki'])->middleware('auth');
        Route::get('/page-edit/{page}', [PageController::class, 'edit'])->middleware('auth');
        Route::get('/page-show/{page}/delete', [PageController::class, 'show'])->middleware('auth');
        Route::delete('/page-show/{page}/delete', [PageController::class, 'destroy'])->middleware('auth');
        Route::get('/page-showpretty/{page}', [PageController::class, 'showPretty'])->middleware('auth');
        Route::post('/page-showpretty/{page}', [PageController::class, 'showPretty'])->middleware('auth');
        Route::get('/page-showmenu/{title}', [PageController::class, 'showMenu'])->middleware('auth');
        Route::get('/page-showhelp/{title}', [PageController::class, 'showHelp'])->middleware('auth');
        Route::get('/page-showbyname/{name}/{pageType}', [PageController::class, 'showByName'])->middleware('auth');
        Route::get('/page-startpage', [PageController::class, 'showStartPage']);
        Route::get('/page-userpage', [PageController::class, 'showUserPage'])->middleware('auth');
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
            $rc = redirect(to: '/page-index')->middleware('auth');
        } else {
            $textRaw = $page->contents;
            $view = 'page.show-col1';
            $audio = $page->audio_id == null ? null : File::relativeFileLink($page->audio_id);
            $params = ['audio' => $audio];
            switch ($page->pagetype_scope) {
                case 1144: /* wiki */
                    $view = 'page.showwiki';
                    $params["text"] = $this->asHtml($page);
                    $params["title"] = $page->title;
                    break;
                default:
                    $columns = 1 + substr_count($textRaw, "\n---- %col%");
                    if ($columns <= 1) {
                        $params["text1"] = $this->asHtml($page);
                    } else {
                        $wiki = new MediaWiki();
                        $parts = explode("\n---- %col%", $textRaw, 4);
                        for ($no = 1; $no <= $columns; $no++) {
                            $params["text$no"] = $wiki->toHtml($parts[$no - 1]);
                        }
                        $view = "page.show-col$columns";
                    }
                    break;
            }
            if ($page->previous_id != null) {
                $params['prev'] = "/page-showpretty/$page->previous_id";
            }
            if ($page->next_id != null) {
                $params['next'] = "/page-showpretty/$page->next_id";
            }
            if ($page->up_id != null) {
                $params['up'] = "/page-showpretty/$page->up_id";
            }
            $context = new ContextLaraKnife($request, $params, $page);
            $rc = view($view, ['context' => $context]);
        }
        return $rc;
    }
    public function showByName(string $name, int $pageType, Request $request)
    {
        $page = Page::where(['name' => $name, 'pagetype_scope' => $pageType])->first();
        if ($page == null) {
            $context = new ContextLaraKnife($request, ['text' => "invalid reference: $name $pageType"]);
            $rc = view('page.unknown', [
                'context' => $context,
            ]);
        } else {
            $rc = $this->showPretty($page, $request);
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

    public function showStartPage(Request $request)
    {
        $rc = $this->showByName('main', 1141, $request);
        return $rc;
    }

    public function showUserPage(Request $request)
    {
        $name = 'user.' . strval(auth()->id());
        $page = Page::where(['name' => $name, 'pagetype_scope' => 1141])->first();
        if ($page == null) {
            $rc = $this->showByName('main', 1141, $request);
        } else {
            $rc = $this->showPretty($page, $request);
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
            ViewHelper::addFieldIfMissing($fields, 'order', '0');
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
                $validated['contents'] = MediaWiki::expandStarItems($validated['contents']);
                $page = Page::create($validated);
                Change::createFromFields($validated, Change::$UPDATE, 'Page', $page->id);
                if ($page != null) {
                    $rc = redirect("/page-edit/$page->id");
                }
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
    public function update(Page $page, Request $request, array &$fields)
    {
        $rules = $this->rules(false);
        if ($fields['order'] == null) {
            $fields['order'] = 0;
        }
        if ($fields['reference_id'] != null) {
            $rules['reference_id'] = 'exists:pages,id';
        }
        if ($fields['previous_id'] != null) {
            $rules['previous_id'] = 'exists:pages,id';
        }
        if ($fields['up_id'] != null) {
            $rules['up_id'] = 'exists:pages,id';
        }
        if ($fields['next_id'] != null) {
            $rules['next_id'] = 'exists:pages,id';
        }
        $validator = Validator::make($fields, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $rc = back()->withErrors($validator)->withInput();
        } else {
            $validated = $validator->validated();
            $validated['info'] = strip_tags($validated['info']);
            $audioId = $page->audio_id;
            $x = $request->file('file');
            if ($audioId == null && $request->file('file') != null) {
                $filename = FileHelper::textToFilename($page->title);
                $moduleId = Module::idOfModule('Page');
                $fileId = File::storeFile($request, $page->title, 1103, 1091, 'audio file of page', $filename, $moduleId, $page->id);
                $validated['audio_id'] = $fileId;
            }
            $page->update($validated);
            $current = "<title>: " . $validated['title'] . "\n<name>: " . $validated['name']
                . "\n<info>: " . $validated['info'] . "\n<contents>: " . $validated['contents'] . "\n";
            $link = null;
            Change::createFromFields($validated, Change::$UPDATE, 'Page', $page->id);
        }
    }
}
