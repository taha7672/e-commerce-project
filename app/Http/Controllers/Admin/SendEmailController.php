<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:send-emails');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emailLogs = EmailLog::orderBy('id','DESC')->paginate(25);
        return view('admin.send_emails.index', compact('emailLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = EmailTemplate::all();
		$shortcodes = config('shortcodes.shortcodes');
        $users = User::select('id','email', 'name')->where('is_deleted', 0)->get();
        $orders = Order::select('id','order_num')->whereNotNull('order_num')->get();
        return view('admin.send_emails.create', compact('templates','shortcodes','users','orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_emails' => 'required_without:send_to_all',
            'send_to_all' => 'required_without:user_emails',
            'template_id' => 'required',
        ]);

        $data = $request->all();
        $user_emails = $data['user_emails']??[];
        $template_id = $data['template_id']; 
        $order_id = $data['order_id']; 
        if(isset($data['send_to_all'])){
            $users = User::select('email')->where('is_deleted', 0)->get();
            foreach($users as $user){
                $user_emails[] = $user->email;  
            }
        }
        // Fetch the template
        $template = EmailTemplate::where('id', $template_id)->first();

        if ($template) { 
            $order = Order::where('id', $order_id)->first();
            // Send the email
            try{ 
                foreach($user_emails as $email){
                    Mail::to($email)->send(new \App\Mail\CustomEmail($template, $order)); 
                }
            }
            catch(\Exception $ex){ 
                // dd($ex);
            }
            return redirect()->route('admin.send-emails.create')
                             ->with('success',  __('messages.email_sent'));
        }
        else{
            return redirect()->back()->withError("Template Not Found!");
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $emailLog = EmailLog::findOrFail($id);
        echo $emailLog->body;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $emailLog = EmailLog::findOrFail($id);
        $emailLog->delete();
        return redirect()->route('admin.send-emails.index')
                         ->with('success', __('messages.email_deleted'));
    }
}
