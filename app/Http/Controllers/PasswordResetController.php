<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class PasswordResetController extends Controller
{


    public function showResetForm($token)
    {
        $token = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$token) {
            session()->flash('error', 'Invalid Password Reset Link');
            return redirect()->route('reset-password.status');
        }
        $email = $token->email;
        $token = $token->token;
        return view('auth.passwords.confirm', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
            'token' => 'required',
        ]);
        $password = $request->password;
        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$token) {
            session()->flash('error', 'Invalid Password Reset Link');
            return redirect()->route('reset-password.status');
        }
        $user = User::whereEmail($token->email)->first();
        $user->update(['password' => bcrypt($password)]);
        DB::table('password_reset_tokens')->where('token', $token->token)->delete();

        session()->flash('success', 'Password reset successfully. Use your new credentials to login.');
        return redirect()->route('reset-password.status');
    }

    public function resetPasswordStatus()
    {
        $success = session()->get('success');
        $error = session()->get('error');
        if ($success || $error) {
            return view('auth.passwords.status', compact('success', 'error'));
        }
        return redirect()->route('home');
    }
}
