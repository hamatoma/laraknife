<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'nickname',
        'titles',
        'gender_scope',
        'persongroup_scope',
        'info'
    ];
    public function findAddresses(): string{
        $rc = '';
        $locations = DB::table('locations')->where('person_id', '=', $this->id)->get();
        foreach ($locations as &$location){
            $rc .= "<a href=\"/location-edit/$location->id\">";
            if ($location->additional != null && $location->additional !== ''){
                $rc .= "$location->additional ; ";
            } 
            $rc .= "$location->street<br>\n";
            $rc .= "$location->country-$location->zip $location->city</a><br><br>\n\n";
        }
        $addresses = DB::table('addresses')->where('person_id', '=', $this->id)->get();
        foreach ($addresses as &$address){
            $rc .= "<a href=\"/address-edit/$address->id\">";
            $info = $address->info;
            $info2 = $info !== null && $info !== '' ? " ; $info" : '';
            $rc .= "$address->name$info2</a><br>\n";
        }
        return $rc;
    }
}
