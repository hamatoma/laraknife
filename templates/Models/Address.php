<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'name',
        'info',
        'addresstype_scope',
        'priority',
        'person_id'
    ];
    public function byPerson(){
        $rc = '';
        $addresses = $this->hasMany(Person::class, 'owner_id');
        return $addresses;
    }
}
