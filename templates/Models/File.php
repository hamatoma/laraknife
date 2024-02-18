<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $fillable = [
        'title',
        'description',
        'filename',
        'filegroup_scope',
        'user_id',
        'size'
    ];
}
