<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate; 

class EmailTemplateController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:email-templates');
    }
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email_templates.index', compact('templates'));
    }

    public function create()
    {
		$shortcodes = config('shortcodes.shortcodes');
        return view('admin.email_templates.create', compact('shortcodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        EmailTemplate::create($request->all());

        return redirect()->route('admin.email-templates.index')
                         ->with('success', 'Email Template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
		$shortcodes = config('shortcodes.shortcodes');
        return view('admin.email_templates.edit', compact('emailTemplate' ,'shortcodes'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('admin.email-templates.index')
                         ->with('success', __('messages.email_template_updated'));
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')
                         ->with('success', __('messages.email_template_deleted'));
    }
}
