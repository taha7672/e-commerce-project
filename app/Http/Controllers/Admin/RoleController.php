<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin-management,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles=Role::with('permissions')->where('name','!=','superadmin')->get();
        return view('admin.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions=Permission::all();
        return view('admin.roles.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name'),'guard_name'=>'admin']);
        $role->syncPermissions($request->input('permissions'));
        return redirect()->route('admin.roles.index')->with('success', __('messages.role_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role=Role::with('permissions')->findOrFail($id);
        $permissions=Permission::all();
        return view('admin.roles.edit',compact('permissions','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'. $id,
            'permissions' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permissions'));


        return redirect()->route('admin.roles.index')
                        ->with('success' , __('messages.role_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role=Role::find($id);
        $role->delete();
        return redirect()->back()->with('success', __('messages.role_deleted'));
    }
}
