<?php

namespace App\Http\Controllers;

use App\Models\Noun;
use App\Helpers\DbAccess;
use App\Models\SProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ViewHelper;
use Illuminate\Support\Facades\Route;

class ExchangeController extends Controller
{
    /**
     * Show the form for exporting from the database.
     */
    public function export()
    {
        //$records = DB::select('show tables')->get();
        //$texts = ''
        //foreach($records as $record){
        //}
        $options = SProperty::optionsByScope('genus', '', '<Please select>');
        return view('exchanges.export', ['options' => $options]);
    }
}