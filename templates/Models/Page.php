<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;
    protected $table = 'pages';
    protected $fillable = [
        'name',
        'title',
        'contents',
        'info',
        'pagetype_scope',
        'markup_scope',
        'language_scope',
        'order',
        'columns',
        'audio_id',
        'reference_id',
        'previous_id',
        'up_id',
        'next_id',
        'owner_id'
    ];
    public static function byId(int $id): ?Page{
        $page = Page::find($id);
        return $page;
    }
    public static function byNameAndType(string $name, int $pageType): mixed{
        $page = Page::where([['name', '=', $name], ['pagetype_scope', '=', $pageType]])->first();
        return $page;
    }
    public static function byTitle(string $title): mixed{
        $page = Page::where('title', '=', $title)->first();
        return $page;
    }
}
