<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Term extends Model
{
    use HasFactory;
    protected $table = 'terms';
    protected $fillable = [
        'title',
        'term',
        'duration',
        'description',
        'visibility_scope',
        'owner_id'
    ];
}
