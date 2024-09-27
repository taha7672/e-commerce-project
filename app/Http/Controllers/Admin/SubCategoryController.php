<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\VariantAttribute;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Services\SeoMetadataService;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    protected $seoMetadataService;
    public function __construct(SeoMetadataService $seoMetadataService)
    {
        $this->seoMetadataService = $seoMetadataService;
        $this->middleware('permission:sub-categories,admin');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->where('sub_categories.is_deleted', 0)->get();
        return view('admin.sub-categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::where('is_deleted', 0)->get();
        return view('admin.sub-categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|string',
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'attributesName' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Handle the image upload
            if ($request->hasFile('image')) {
                $uploadedFile = $validatedData['image'];
                $name = time() . $uploadedFile->getClientOriginalName();
                $uploadedFile->move('uploads/sub-categories', $name);
                $validatedData['image'] = "uploads/sub-categories/$name";
            }

            // Create the subcategory
            $subCategory = SubCategory::create([
                'category_id' => $validatedData['category_id'],
                'slug' => Str::slug($validatedData['name']),
                'name' => $validatedData['name'],
                'image' => $validatedData['image'],
                'description' => $validatedData['description'],
                'is_active' => $validatedData['is_active'],
            ]);

            $this->seoMetadataService->generateForSubCategory($subCategory);
            // Save attributes
            // print_r($subCategories);
            // dd($validatedData['attributesName']);

            if (isset($validatedData['attributesName'])) {
                $attributes = $validatedData['attributesName'];
                foreach ($attributes as $attribute) {

                    VariantAttribute::create([
                        'subcategory_id' => $subCategory->id,
                        'attribute_name' => $attribute,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.sub-categories.index')->with('success',  __('messages.sub_category_created'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.sub-categories.create')
                ->with('error', 'An error occurred while processing your request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subCategory = SubCategory::with('category')->find($id);
        $category = Category::where('is_deleted', 0)->get();
        return view('admin.sub-categories.edit', compact('subCategory', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|string',
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);
        if ($request->hasFile('image')) {
            $uploadedFile = $validatedData['image'];
            $name = time() . $uploadedFile->getClientOriginalName();
            $uploadedFile->move('uploads/sub-categories', $name);

            $validatedData['image'] = "uploads/sub-categories/$name";
        }


        $subCategory = SubCategory::findOrFail($id);
        $filteredData = collect($validatedData)->toArray();
        $subCategory->update($filteredData);

        return redirect()->route('admin.sub-categories.index')->with('success',  __('messages.sub_category_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = SubCategory::find($id);
        $subCategory->is_deleted = true;
        $subCategory->update();
        return redirect()->back()->with('success', __('messages.sub_category_deleted'));
    }

    public function getSubcategories($id)
    {
        $subCategories = SubCategory::where('category_id', $id)->get();
        return response()->json($subCategories);
    }
}
