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
        'cacheof_id',
        'owner_id'
    ];
}
