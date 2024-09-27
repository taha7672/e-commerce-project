<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VariantAttribute;
// use App\Models\ProductAttributes;

class AttributeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories,admin');
    }

    public function getAttributes($subCategoryId)
    {
        $attributes = VariantAttribute::where('subcategory_id', $subCategoryId)->get();
        // $attributes = ProductAttributes::where('subcategory_id', $subCategoryId)->get();
        return response()->json($attributes);
    }
}