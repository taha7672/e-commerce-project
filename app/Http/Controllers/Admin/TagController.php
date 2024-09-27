<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags=Tag::where('is_deleted',0)->get();
        return view('admin.tags.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'=>'required|string',
            'is_active' => 'required|boolean',     
        ]);
        if ($request->hasFile('image')) {
            $uploadedFile = $validatedData['image'];
            $name=time().$uploadedFile->getClientOriginalName();
            $uploadedFile->move('uploads/tags',$name);

            $validatedData['image'] ="uploads/tags/$name";
        }
        $tag=Tag::create($validatedData);
        return redirect()->route('admin.tags.index')->with('success',  __('messages.tag_created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag=Tag::find($id);
       
        return view('admin.tags.edit',compact('tag'));

    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'=>'required|string',
            'is_active' => 'required|boolean',     
        ]);
        if ($request->hasFile('image')) {
            $uploadedFile = $validatedData['image'];
            $name=time().$uploadedFile->getClientOriginalName();
            $uploadedFile->move('uploads/tags',$name);

            $validatedData['image'] ="uploads/tags/$name";
        }

        
        $tag=Tag::findOrFail($id);
        $filteredData = collect($validatedData)->toArray();
        $tag->update($filteredData);

        return redirect()->route('admin.tags.index')->with('success', __('messages.tag_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag=Tag::find($id);
        $tag->is_deleted=true;
        $tag->update();
        return redirect()->back()->with('success', __('messages.tag_deleted'));
    }
}
