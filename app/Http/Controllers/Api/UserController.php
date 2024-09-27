<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
        /**
     * Display the currently authenticated user.
     */
    public function show(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $user = User::with('role')->find($user->id);

        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }

    /**
     * Update the currently authenticated user.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        // Update user
        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        if (isset($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']); // Hash the password
        }
        $user->save();

        return response()->json([
            'success' => true,
            'user' => $user,
        ], 200);
    }
}
