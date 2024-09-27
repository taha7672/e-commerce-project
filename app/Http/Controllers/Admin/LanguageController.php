<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    // changeLanguage 
    public function changeLanguage(Request $request)
    {
        $language = $request->lang;
       
        App::setLocale($language);
        session()->put('locale', $language);
        return response()->json(['success' => true]);
    }
}
