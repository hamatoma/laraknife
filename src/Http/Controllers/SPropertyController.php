<?php

namespace App\Http\Controllers;

use App\Models\SProperty;
use Illuminate\Http\Request;

class SPropertyController extends Controller
{
    /**
     * Returns the validation rules
     */
    private function rules(): array
    {
        return [
            'scope' => 'required|alpha',
            'name' => 'required',
            'order' => 'min:0|max:9999',
            'shortname' => 'required|alpha'
        ];
    }
    /**
     * Creates a record of the table sproperties with data from a $request.
     */
    public function createSProperty(Request $request)
    {
        $incomingFields = $request->validate($this->rules());
        $incomingFields['info'] = strip_tags($incomingFields['info']);
        SProperty::create($incomingFields);
        return redirect('/sproperty-index');
    }
    /**
     * Updates the record $sproperty of the table SPropertys with data from a $request.
     */
    public function updateSProperty(SProperty $sproperty, Request $request)
    {
        $incomingFields = $request->validate($this->rules());
        $incomingFields['info'] = strip_tags($incomingFields['info']);
        $sproperty->update($incomingFields);
        return redirect('/sproperty-index');
    }
   /**
     * Deletes the record $sproperty from the table SPropertys.
     */
    public function deleteSProperty(SProperty $sproperty)
    {
        $sproperty->delete();
        return redirect('/sproperty-index');
    }
}
