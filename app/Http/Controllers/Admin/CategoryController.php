<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\SeoMetadataService;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    protected $seoMetadataService;
    public function __construct(SeoMetadataService $seoMetadataService)
    {
        $this->seoMetadataService = $seoMetadataService;
        $this->middleware('permission:categories,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('is_deleted', 0)->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);
        if ($request->hasFile('image')) {
            $uploadedFile = $validatedData['image'];
            $name = time() . $uploadedFile->getClientOriginalName();
            $uploadedFile->move('uploads/categories', $name);

            $validatedData['image'] = "uploads/categories/$name";
        }
        $category = Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'image' => $validatedData['image'],
            'description' => $validatedData['description'],
            'is_active' => $validatedData['is_active'],
        ]);

        $this->seoMetadataService->generateForCategory($category);


        return redirect()->route('admin.categories.index')->with('success', __('messages.category_created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        return view('admin.categories.edit', compact('category'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);
        if ($request->hasFile('image')) {
            $uploadedFile = $validatedData['image'];
            $name = time() . $uploadedFile->getClientOriginalName();
            $uploadedFile->move('uploads/categories', $name);

            $validatedData['image'] = "uploads/categories/$name";
        }


        $category = Category::findOrFail($id);
        $filteredData = collect($validatedData)->toArray();
        $category->update($filteredData);

        return redirect()->route('admin.categories.index')->with('success', __('messages.category_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->is_deleted = true;
        $category->update();
        return redirect()->back()->with('success', __('messages.category_deleted'));
    }


}
