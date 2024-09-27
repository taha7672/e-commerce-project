<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:users,admin');
    }

    public function index(){
        $users=User::where('is_deleted', 0)->get();
        return view('admin.users.index',compact('users'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::find($id);
        $user->is_deleted=true;
        $user->update();
        return redirect()->back()->with('success', __('messages.user_deleted'));
    }
}
