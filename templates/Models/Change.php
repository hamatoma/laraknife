<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Change extends Model
{
    use HasFactory;
    public static $CREATE = 1341;
    public static $UPDATE = 1342;
    public static $DELETE = 1343;
    protected $table = 'changes';
    protected $fillable = [
        'changetype_scope',
        'module_id',
        'reference_id',
        'description',
        'current',
        'link',
        'user_id',
        'created_at'
    ];

    public static function createChange(
        int $changeType,
        int $moduleId,
        int $referenceId,
        ?string $description,
        string $current,
        ?string $link = null
    ) {
        Change::create([
            'changetype_scope' => $changeType,
            'module_id' => $moduleId,
            'reference_id' => $referenceId,
            'description' => $description,
            'current' => $current,
            'link' => $link,
            'user_id' => auth()->user()->id
        ]);
    }
    
    public static function createFromFields(
        array $fields,
        int $changeType,
        string $moduleName,
        int $referenceId,
        ?string $link = null
    ) {
        $current = '';
        foreach ($fields as $key => $value) {
            if ($current !== ''){
                $current .= "\n";
            }
            $current .= "\u{27FC}[$key]: $value";
        }
        $moduleId = Module::idOfModule($moduleName);
        if ($moduleName === 'Page') {
            $link = "/page-showpretty/$referenceId";
        } else {
            $link = '/' . strtolower($moduleName) . "-edit/$referenceId";
        }
        Change::create([
            'changetype_scope' => $changeType,
            'module_id' => $moduleId,
            'reference_id' => $referenceId,
            'description' => null,
            'current' => $current,
            'link' => $link,
            'user_id' => auth()->user()->id
        ]);
    }
    public static function createFromModel(
        Model $model,
        int $changeType,
        string $moduleName) {
        $current = '';
        $attributes = $model->getAttributes();
        foreach ($attributes as $key => $attribute) {
            if ($current !== ''){
                $current .= "\n";
            }
            $current .= "\u{27FC}[$key]: $attribute";
        }
        $moduleId = Module::idOfModule($moduleName);
        $link = '';
        Change::create([
            'changetype_scope' => $changeType,
            'module_id' => $moduleId,
            'reference_id' => $model->id,
            'description' => null,
            'current' => $current,
            'link' => $link,
            'user_id' => auth()->user()->id
        ]);
    }
    
}
