<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;
use App\Mail\ResetPassword;
use App\Mail\UserEmailVerification;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Geocoder;
use App\Models\SitesSetting;
class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                DB::rollBack();
                return $this->errorResponse('Invalid Credentials', 401);
            }

            $user = Auth::user();
            if (getSetting('email_verification_status') == 1) {
                if (empty($user->email_verified_at)) {
                    return $this->errorResponse('You are not authorized to login.', 401);
                }
            }

            // Revoke old tokens
            $user->tokens()->delete();

            $token = $user->createToken('New Token')->plainTextToken;
            $responseData = [
                'token' => $token,
                'user' => $user,
            ];
            // Get user IP address
            $ipAddress = $request->ip();
            // You may need to use a service or API to get the city and country from the IP address
            $city = null; // Placeholder
            $country = null; // Placeholder

            // Record user activity
            UserActivity::create([
                'user_id' => $user->id,
                'activity_type' => 'login',
                'ip_address' => $ipAddress,
                'city' => $city,
                'country' => $country,
                'logged_in' => now()
            ]);
            // Commit transaction
            DB::commit();

            return $this->successResponse($responseData, 'Login Successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'nullable'
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.'
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['verification_code'] = rand(111111, 999999);

            if (empty($data['role'])) {
                $data['user_role_id'] = config('constants.USER');
            }
            if (empty($data['surname'])) {
                $data['surname'] = '';
            }

            // Create user
            $user = User::create($data);

            // Send verification email
            if (getSetting('email_verification_status') == 1) {
                Mail::to($user->email)->send(new UserEmailVerification($user['verification_code']));
            }


            // Commit transaction
            DB::commit();

            return $this->successResponse(['user' => $user], 'Account Created Successfully. Please Verify Your Email');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function sendForgetPasswordLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.exists' => 'The email does not exist in our records.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $email = $request->email;

        // Begin transaction
        DB::beginTransaction();

        try {
            $tokenRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

            if ($tokenRecord) {
                $token = $tokenRecord->token;
            } else {
                $token = Str::random(40);
                DB::table('password_reset_tokens')->insert(['token' => $token, 'email' => $email]);
            }

            // Send reset password email
            Mail::to($email)->send(new ResetPassword($token));

            // Commit transaction
            DB::commit();

            return $this->successResponse(null, 'Please check your email. We have sent you a password reset link.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8'
        ], [
            'token.required' => 'Token is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'email.exists' => 'Email not found in our records.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        // Begin transaction
        DB::beginTransaction();

        try {
            // Check if the token exists and is valid
            $checkToken = DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->first();

            if ($checkToken) {
                // Update the user's password
                $passwordUpdate = User::where('email', $email)->update(['password' => Hash::make($password)]);

                if ($passwordUpdate) {
                    // Delete the token
                    DB::table('password_reset_tokens')->where(['token' => $token, 'email' => $email])->delete();

                    // Commit transaction
                    DB::commit();

                    return $this->successResponse(null, 'Password reset successfully.');
                } else {
                    // Rollback transaction if password update fails
                    DB::rollBack();
                    return $this->errorResponse('Failed to update password. Please try again.', 422);
                }
            } else {
                // Rollback transaction if token is invalid
                DB::rollBack();
                return $this->errorResponse('Invalid token or email.', 422);
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    /**
     * Log out the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            DB::beginTransaction();
            // Check if the user is authenticated
            if ($request->user()) {
                // Revoke the token that was used to authenticate the current request
                $request->user()->currentAccessToken()->delete();
                DB::commit();
                return $this->successResponse(null, 'Logout successfully.');
            } else {
                DB::commit();
                return $this->errorResponse('No valid authentication token provided.', 401);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }


}
