<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function categoryList(Request $request)
    {
        try {
            $query = Category::with('subCategories', 'seoMetaData')->withCount('products')->where('is_deleted', 0)->where('is_active',1);

            if ($request->name) {
                $name = $request->name;
                $query = $query->where('categories.name', 'LIKE', '%' . $name . '%');
            }

            $categories = $query->get();

            if ($categories->isNotEmpty()) {
                $categories->transform(function ($category) {
                    $category->image = url($category->image);
                    return $category;
                });
                return $this->successResponse($categories, 'Categories list fetched successfully');
            } else {
                return $this->successResponse([], 'No record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }

}
