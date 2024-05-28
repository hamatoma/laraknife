<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Note;
use App\Models\Page;
use App\Models\Group;
use App\Models\Module;
use App\Helpers\Helper;
use App\Helpers\DbHelper;
use App\Models\SProperty;
use App\Helpers\FileHelper;
use App\Helpers\Pagination;
use App\Helpers\ViewHelper;
use Illuminate\Http\Request;
use App\Helpers\ViewHelperLocal;
use App\Helpers\ContextLaraKnife;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $noteId = null;
    protected $pageId = null;
    public static function generate(int $noteId, int $pageId = null)
    {
        $rc = new TaskController();

        $rc->noteId = $noteId;
        $rc->pageId = $pageId;
        return $rc;
    }
    public function create(Request $request)
    {
        $rc = null;
        if ($request->btnSubmit === 'btnCancel') {
            $rc = redirect('/note-index');
        } else {
            $fields = $request->all();
            $pageId = array_key_exists('page_id', $fields) ? $fields['page_id'] : null;
            if (count($fields) <= 1) {
                $fields = [
                    'title' => '',
                    'body' => '',
                    'category_scope' => '1054',
                    'visibility_scope' => '1091',
                    'owner_id' => strval(auth()->id()),
                    'task' => ''
                ];
            }
            if ($pageId != null) {
                $page = Page::find(intval($pageId));
                if ($page != null) {
                    $fields['title'] = __('Task') . ": $page->title";
                    $fields['options'] = "ref-page=$page->id";
                }
            }
            if ($request->btnSubmit === 'btnStore') {
                $validator = Validator::make($fields, ['title' => 'required', 'task' => 'required']);
                if ($validator->fails()) {
                    $rc = back()->withErrors($validator)->withInput();
                } else {
                    $fields['notestatus_scope'] = 1011;
                    $fields['options'] .= "\ntask=" . $fields['task'];
                    $fields['owner_id'] = auth()->id();
                    $task = Note::create($fields);
                    $rc = redirect("/note-edit/$task->id");
                }
            }
            if ($rc == null) {
                $optionsVisibility = SProperty::optionsByScope('visibility', $fields['visibility_scope']);
                $optionsTask = SProperty::optionsByScope('task', $fields['task'], '-');
                $optionsCategory = SProperty::optionsByScope('category', $fields['category_scope']);
                $context = new ContextLaraKnife($request, $fields);
                $rc = view('task.create', [
                    'context' => $context,
                    'optionsVisibility' => $optionsVisibility,
                    'optionsTask' => $optionsTask,
                    'optionsCategory' => $optionsCategory,
                ]);
            }
        }
        return $rc;
    }

    public static function routes()
    {
        Route::get('/task-create', [TaskController::class, 'create'])->middleware('auth');
        Route::post('/task-create', [TaskController::class, 'create'])->middleware('auth');
    }

}

