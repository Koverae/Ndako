<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use Illuminate\Http\Request;

class SwitchCompanyController extends Controller
{
    public function switch(Request $request)
    {
        $data = $request->validate(['company_id' => ['required','integer']]);


        $user = $request->user();
        $company = Company::findOrFail($data['company_id']);
        if($company->users()->where('id', $user->id)->doesntExist()){
            abort(403);
        }
        $user->current_company_id = $company->id;
        $user->save();

        // Persist in session (or DB, as you prefer)
        session(['current_company' => $company]);


        return back()->with('status', __('Switched to :name', ['name' => $company->name]));
    }
}
