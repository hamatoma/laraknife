<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hour extends Model
{
    use HasFactory;
    protected $table = 'hours';
    protected $fillable = [
        'time',
        'duration',
        'hourtype_scope',
        'hourstate_scope',
        'description',
        'owner_id',
        'factor',
        'interested',
    ];
}
