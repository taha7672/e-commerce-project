<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\ResetPassword;
use App\Mail\TwoFactorAuth;


class AuthController extends Controller
{
    public function showLogin()
    {
        // echo Hash::make('password123');
        // exit;
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $admin = Admin::whereEmail($request->email)->first();
        if (!$admin) {
            return redirect()->back()->withErrors([__('messages.No_Account_found_with_this_email') ])->withInput();
        }

        if (Hash::check($request->password, $admin->password)) {
            if ($admin->two_factor_auth) {
                session()->put('admin_id', $admin->id);
                //  generate a random code and store in the database  4 digit code
                $code = rand(1000, 9999);
                $admin->update(['two_factor_code' => $code]);
                // send the code to the admin email
                Mail::to($admin->email)->send(new TwoFactorAuth($code));

                return redirect()->route('admin.two.factor.auth');
            }
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withErrors(['Invalid Credentials.'])->withInput();
    }

    // forgot password      
    public function showForgotPassword()
    {
        return view('auth.admin.forgot-password');
    }
    public function resetPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);
        try {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now(),
                ]
            );
            $admin = Admin::whereEmail($request->email)->first();
            if ($admin) {
                Mail::to($admin->email)->send(new ResetPassword($token));
                return redirect()->back()->with('success',  __('messages.password_reset_link_sent'));
            } else {
                return redirect()->back()->with('error',  __('messages.email_not_found'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.something_went_wrong'));
        }

    }
    public function adminShowUpdateForm($token)
    {
        $token = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$token) {
            session()->flash('error', 'Invalid Password Reset Link');
            return redirect()->route('reset-password.status');
        }
        $email = $token->email;
        $token = $token->token;
        return view('auth.admin.confirm', compact('token', 'email'));
    }

    public function adminResetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
            'token' => 'required',
        ]);


        if ($request->password != $request->password_confirmation) {
            return redirect()->back()->with('error',  __('messages.password_mismatch'));
        }

        $password = $request->password;
        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$token) {
            session()->flash('error', 'Invalid Password Reset Link');
            return redirect()->route('reset-password.status');
        }
        $user = Admin::whereEmail($token->email)->first();
        $user->update(['password' => bcrypt($password)]);
        DB::table('password_reset_tokens')->where('token', $token->token)->delete();

        session()->flash('success',  __('messages.password_reset_success'));
        return redirect()->route('admin.showLogin');

    }

    // twoFactorAuth 
    public function twoFactorAuth()
    {
        if (!session()->has('admin_id')) {
            return redirect()->route('admin.showLogin');
        }

        return view('auth.admin.two-factor-auth');
    }
    // verifyTwoFactorAuth 
    public function verifyTwoFactorAuth(Request $request)
    {
        $request->validate([
            'auth_code' => 'required',
        ]);
        $admin = Admin::find(session()->get('admin_id'));
        if ($admin->two_factor_code == $request->auth_code) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }
        session()->flash('error', __('messages.Invalid_Code'));
        return redirect()->back();
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.showLogin');
    }
}
