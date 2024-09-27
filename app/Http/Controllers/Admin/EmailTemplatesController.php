<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplates;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller
{
    
    public function index(Request $request) {

        $templates = EmailTemplates::limit(500)->get();

        return view('admin.email-templates.index', compact('templates'));
    }

}
