<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menuitem extends Model
{
    use HasFactory;
    protected $table = 'menuitems';
    protected $fillable = [
        'name',
        'label',
        'icon',
        'section',
        'link'
    ];
}
