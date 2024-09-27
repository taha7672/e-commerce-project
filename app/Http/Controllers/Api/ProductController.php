<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productList(Request $request)
    {

        // try {
        //     // Hard delete all products
        //     Product::query()->delete();

        //     return response()->json([
        //         'message' => 'All products have been deleted successfully.'
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'Failed to delete products.',
        //         'error' => $e->getMessage(),
        //     ], 500);
        // }
        try {
            $query = Product::with(['productTags.tag', 'productCategories', 'ProductVariants.attributeValues.variantAttribute', 'reviews', 'seoMetaData', 'images'])
                ->where('products.is_deleted', 0);

            if ($request->name) {
                $name = $request->name;
                $query = $query->where('products.name', 'LIKE', '%' . $name . '%');
            }

            if ($request->tag_name) {
                $tagName = $request->tag_name;
                $query = $query->whereHas('productTags.tag', function ($q) use ($tagName) {
                    $q->where('name', $tagName);
                });
            }

            if ($request->category_id) {
                $categoryId = $request->category_id;
                $query = $query->whereHas('productCategories', function ($q1) use ($categoryId) {
                    $q1->where('category_id', $categoryId);
                });
            }

            $products = $query->get();

            if ($products->isNotEmpty()) {
                $products->transform(function ($product) {
                    $product->image = url($product->image);
                    $images = array();
                    foreach ($product->images() as $image) {
                        $images[] = url($image->image_path);
                    }
                    $product->images = $images;
                    return $product;
                });

                return $this->successResponse($products, 'Products list fetched successfully');
            } else {

                return $this->successResponse($products, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }

    public function productDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ], [
            'id.required' => 'Product ID is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        try {
            $id = $request->id;
            $productDetails = Product::with(['productTags', 'productCategories', 'productVariants.attributeValues.variantAttribute', 'reviews', 'seoMetaData', 'images'])
                ->where('products.id', $id)
                ->first();

            if ($productDetails) {
                $productDetails->image = url($productDetails->image);

                $images = array();
                foreach ($productDetails->images() as $image) {
                    $images[] = url($image->image_path);
                }
                $productDetails->images = $images;

                return $this->successResponse($productDetails, 'Product details fetched successfully');
            } else {

                return $this->successResponse(null, 'No record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }


    // Products API with Filters and Pagination


    public function filteredProductList(Request $request)
    {
        try {
            $query = Product::with([
                    'productTags.tag',
                    'productCategories',
                    'productCategories.category',
                    'ProductVariants.attributeValues.variantAttribute',
                    'reviews.user', // Include the user with reviews
                    'seoMetaData',
                    'images'
                ])
                ->where('products.is_deleted', 0)
                ->where('products.is_active', 1)
                // Select the additional fields
                ->select('products.*', 'products.additional_description', 'products.additional_info', 'products.shipping_return');

            $filters = $request->filters;
            $pagination = $request->pagination;
            $searchKeyword = $request->searchKeyword;

            $maxPrice = $query->clone()->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->where('product_variants.price', '>', 0)
                ->max('product_variants.price');

            if (!empty($searchKeyword)) {
                $query = $query->where(function ($q) use ($searchKeyword) {
                    $q->where('products.name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('products.description', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereHas('productTags.tag', function ($q1) use ($searchKeyword) {
                            $q1->where('name', 'LIKE', '%' . $searchKeyword . '%');
                        });
                });
            }

            if (!empty($filters['color'])) {
                $color = $filters['color'];
                $query = $query->whereHas('ProductVariants.attributeValues', function ($q1) use ($color) {
                    $q1->where('value', $color);
                });
            }

            if (!empty($filters['categories'])) {
                $categories = $filters['categories'];
                $query = $query->whereHas('productCategories', function ($q1) use ($categories) {
                    $q1->whereIn('category_id', $categories);
                });
            }

            if (!empty($filters['priceRange'])) {
                $priceRange = $filters['priceRange'];
                $priceRange = explode('-', $priceRange);
                if (count($priceRange) == 2) {
                    $query = $query->whereHas('ProductVariants', function ($q1) use ($priceRange) {
                        $q1->whereBetween('product_variants.price', $priceRange);
                    });
                }
            }

            if (!empty($filters['sort'])) {
                $sort = $filters['sort'];
                if ($sort['sort_by'] === 'popularity') {
                    $query = $query->withCount([
                        'orderItems as popularity' => function ($q) {
                            $q->select(DB::raw('count(order_items.product_id)'));
                        }
                    ])->orderBy('popularity', $sort['order']);
                } elseif ($sort['sort_by'] === 'rating') {
                    $query = $query->withAvg([
                        'reviews' => function ($query) {
                            $query->where('is_approved', 1);
                        }
                    ], 'rating')->orderBy('reviews_avg_rating', $sort['order']);
                } else {
                    $query = $query->orderBy($sort['sort_by'], $sort['order']);
                }
            }
            if (!empty($filters['size'])) {
                $size = $filters['size'];
                $query = $query->whereHas('ProductVariants.attributeValues', function ($q1) use ($size) {
                    $q1->where('value', $size);
                });
            }

            $totalProducts = $query->get();

            if (!empty($pagination)) {
                $query = $query->skip($pagination['skip']);
                $query = $query->take($pagination['limit']);
            }

            $products = $query->get();

            if ($products->isNotEmpty()) {
                $products->transform(function ($product) {
                    $product->image = url($product->image);
                    for ($i = 0; $i < count($product->images); $i++) {
                        $product->images[$i]['image_path'] = url($product->images[$i]['image_path']);
                    }
                    return $product;
                });

                return $this->successResponse([
                    'data' => $products,
                    'total' => count($totalProducts),
                    'max_price' => $maxPrice,
                ], 'Products list fetched successfully');
            } else {
                return $this->successResponse($products, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }

    // Products API with Search and Pagination

    public function searchProductList(Request $request)
    {
        try {
            $query = Product::with(['productTags.tag', 'productCategories', 'ProductVariants.attributeValues.variantAttribute', 'reviews'])
                ->where('products.is_deleted', 0);

            $searchKeyword = $request->searchKeyword;
            $pagination = $request->pagination;

            if (!empty($searchKeyword)) {
                // $query = $query->where('products.name', 'LIKE', '%' . $searchKeyword . '%');
                $query = $query->whereHas('productTags.tag', function ($q) use ($searchKeyword) {
                    $q->where('name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('products.name', 'LIKE', '%' . $searchKeyword . '%');
                });
            }

            if (!empty($pagination)) {
                $query = $query->skip($pagination['skip']);
                $query = $query->take($pagination['limit']);
            }

            $products = $query->get();

            if ($products->isNotEmpty()) {
                $products->transform(function ($product) {
                    $product->image = url($product->image);
                    $images = array();
                    foreach ($product->images() as $image) {
                        $images[] = url($image->image_path);
                    }
                    $product->images = $images;
                    return $product;
                });

                return $this->successResponse($products, 'Products list fetched successfully');
            } else {

                return $this->successResponse($products, 'No Record found');
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
