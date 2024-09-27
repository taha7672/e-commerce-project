<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function subCategoryList(Request $request)
    {
        try {

            $query = SubCategory::withCount('products')->with('seoMetaData')->where('is_deleted', 0);

            if ($request->name) {
                $name = $request->name;
                $query = $query->where('sub_categories.name', 'LIKE', '%' . $name . '%');
            }

            if ($request->category_id) {
                $categoryId = $request->category_id;
                $query = $query->where('sub_categories.category_id', $categoryId);
            }

            $subCategories = $query->get();

            if ($subCategories->isNotEmpty()) {
                $subCategories->transform(function ($subCategory) {
                    $subCategory->image = url($subCategory->image);
                    return $subCategory;
                });

                return $this->successResponse($subCategories, 'Sub Categories list fetched successfully');
            } else {
                return $this->successResponse([], 'No record found');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
