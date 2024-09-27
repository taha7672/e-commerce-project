<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try{
                // Begin transaction
                DB::beginTransaction(); 
                    $user = User::where('email', $request->email)
                    ->where('verification_code', $request->verification_code)
                    ->first();

                    if (!$user) {
                    return response()->json(['message' => 'Invalid verification code or email.'], 400);
                    }

                    $user->email_verified_at = now();
                    $user->verification_code = null;
                    $user->save();
                 // Commit transaction
                DB::commit();
                return response()->json(['message' => 'Email verified successfully.'],200);
            } catch (\Throwable $th) {
                return $this->serverException($th);
            }   
        
    }

}
