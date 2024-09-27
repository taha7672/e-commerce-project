<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\SeoMetadata;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\ProductCategory;
use App\Models\VariantAttributeValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Services\SeoMetadataService;
use Illuminate\Support\Str;
use Log;
class ProductController extends Controller
{
    protected $seoMetadataService;

    public function __construct(SeoMetadataService $seoMetadataService)
    {
        $this->seoMetadataService = $seoMetadataService;
        $this->middleware('permission:products,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'productTags.tag',
            'productCategories' => function ($query) {
                $query->with([
                    'subCategory' => function ($subQuery) {
                        $subQuery->with('category');
                    }
                ]);
            }
        ])->where('products.is_deleted', 0)->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::where('is_deleted', 0)->get();
        $categories = Category::where('is_deleted', 0)->get();
        $subCategories = SubCategory::where('is_deleted', 0)->get();
        return view('admin.products.create', compact('tags', 'categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock_quantity' => 'required|integer',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'nullable|integer|exists:sub_categories,id',
            'tags_id' => 'required|array',
            'variants' => 'required',
            'tags_id.*' => 'integer|exists:tags,id',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|string|max:255',
        ]);


        DB::beginTransaction();

        $this->createDir();

        try {
            // Handle the image (thumbnail ) upload
            if ($request->hasFile('image')) {
                $uploadedFile = $validatedData['image'];
                $name = time() . '_' . $uploadedFile->getClientOriginalName();
                $manager = new ImageManager(new Driver());
                $image = $manager->read($uploadedFile);
                $largeImage = $image->scale(width: config('business.productImgSize.product_img_size.large_img_width'));
                $largeImage->save("uploads/products/large_img/$name");
                $validatedData['image'] = "uploads/products/large_img/$name";

                $mediumImage = $image->scale(width: config('business.productImgSize.product_img_size.medium_img_width'));
                $mediumImage->save("uploads/products/medium_img/$name");
                $validatedData['medium_image'] = "uploads/products/medium_img/$name";

                $smallImage = $image->scale(width: config('business.productImgSize.product_img_size.small_img_width'));
                $smallImage->save("uploads/products/small_img/$name");
                $validatedData['small_image'] = "uploads/products/small_img/$name";
            }
            if ($request->hasFile('images')) {
                //  dir for images
                $dir = public_path('uploads/products/images');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $images = $request->file('images');
                $imagesPath = [];
                foreach ($images as $image) {
                    $name = time() . '_' . $image->getClientOriginalName();
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($image);
                    $largeImage = $image->scale(width: config('business.productImgSize.product_img_size.large_img_width'));
                    $largeImage->save("uploads/products/images/$name");
                    $imagesPath[] = "uploads/products/images/$name";
                }
            }

            // Create the product
            $product = Product::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'] ?? Str::slug($validatedData['name']),
                'image' => $validatedData['image'] ?? null,
                'small_image' => $validatedData['small_image'] ?? null,
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'is_active' => $validatedData['is_active'],
                'medium_image' => $validatedData['medium_image'] ?? null,
                'currency_id' => defaultCurrency()->id,
                'additional_description' => $validatedData['additional_description'],
                'additiona_info' => $validatedData['additional_info'],
                'shipping_return' => $validatedData['shipping_return'],
            ]);

            $productId = $product->id;
            // Save product images
            if (isset($imagesPath) && count($imagesPath) > 0) {
                foreach ($imagesPath as $imagePath) {
                    ProductImage::create([
                        'product_id' => $productId,
                        'image_path' => $imagePath
                    ]);
                }
            }
            // Associate category and subcategory
            $productCategory = ProductCategory::create([
                'product_id' => $productId,
                'category_id' => $validatedData['category_id'],
                'sub_category_id' => $validatedData['sub_category_id'] ?? null,
            ]);
            $tags = Tag::whereIn('id', $validatedData['tags_id'])->get();
            // Attach tags
            $product->tags()->attach($validatedData['tags_id']);
            // create defult variant of product
            $productVariant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $validatedData['name'],
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock_quantity'],
                'is_default' => true,
                'currency_id' => defaultCurrency()->id,
            ]);

            // Handle product variants
            $variants = [];
            foreach ($request->input('variants', []) as $variant) {
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variant['sku'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'currency_id' => defaultCurrency()->id,
                ]);

                if (isset($variant['attributes']) && is_array($variant['attributes'])) {
                    foreach ($variant['attributes'] as $attributeId => $value) {
                        VariantAttributeValue::create([
                            'variant_id' => $productVariant->id,
                            'attribute_id' => $attributeId,
                            'value' => $value,
                        ]);
                    }
                }
                $variants[] = $variant;
            }

            $categoryName = Category::where('id', $validatedData['category_id'])->pluck('name')->first();
            $subCategoryName = SubCategory::where('id', $validatedData['sub_category_id'])->pluck('name')->first() ?? null;

            // Generate SEO metadata for the product
            $this->seoMetadataService->generateForProduct($product, $categoryName, $subCategoryName, $tags, $variants);

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', __('messages.product_created'));
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            $product->is_deleted = true;
            $product->update();
            if ($product->is_deleted) {
                ProductCategory::where('product_id', $id)->delete();
                ProductVariant::where('product_id', $id)->delete();
                ProductImage::where('product_id', $id)->delete();
                SeoMetadata::where('entity_type', 'product')->where('entity_id', $id)->delete();
                $product->tags()->detach();
                if ($product->images) {
                    foreach ($product->images as $image) {
                        if (file_exists(public_path($image->image_path))) {
                            @unlink(public_path($image->image_path));
                        }
                        $image->delete();
                    }
                }
            }
            return redirect()->back()->with('success', __('messages.product_deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with(['productTags', 'productCategories', 'productVariants.attributeValues.variantAttribute', 'images'])->where('products.id', $id)->first();
        $categories = Category::where('is_deleted', 0)->get();
        $tags = Tag::where('is_deleted', 0)->get();
        // echo "<pre>";
        //  print_r($product->toArray());
        //  die();
        return view('admin.products.edit', compact('product', 'categories', 'tags'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

            $validatedData = $request->validate([
                'name' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string',
                // 'stock_quantity' => 'required|integer',
                'category_id' => 'required',
                'sub_category_id' => 'nullable',
                'price' => 'required',
                'tags_id' => 'required|array',
                'is_active' => 'required|boolean',
                'slug' => 'nullable|string|max:255',
            ]);
            try {
            $product = Product::findOrFail($id);
            if ($request->has('removed_image_ids')) {
                $removedImageIds = explode(',', $request->input('removed_image_ids'));
                foreach ($removedImageIds as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image) {
                        if (file_exists(public_path($image->image_path))) {
                            @unlink(public_path($image->image_path));
                        }
                        $image->delete();
                    }
                }
            }
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file) {
                        $dir = public_path('uploads/products/images');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $name = time() . '_' . $file->getClientOriginalName();
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($file);
                        $largeImage = $image->scale(width: config('business.productImgSize.product_img_size.large_img_width'));
                        $largeImage->save("uploads/products/images/$name");
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => "uploads/products/images/$name"
                        ]);
                    }
                }
            }

            $this->createDir();

            // Handle the image upload
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image) {
                    // Storage::delete($product->image);
                    if (file_exists(public_path($product->medium_image))) {
                        @unlink(public_path($product->medium_image));
                    }
                    if (file_exists(public_path($product->small_image))) {
                        @unlink(public_path($product->small_image));
                    }
                    if (file_exists(public_path($product->image))) {
                        @unlink(public_path($product->image));
                    }
                }

                $uploadedFile = $validatedData['image'];
                $name = time() . '_' . $uploadedFile->getClientOriginalName();
                $manager = new ImageManager(new Driver());
                $image = $manager->read($uploadedFile);
                $largeImage = $image->scale(width: config('business.productImgSize.product_img_size.large_img_width'));
                $largeImage->save("uploads/products/large_img/$name");
                $validatedData['image'] = "uploads/products/large_img/$name";

                $mediumImage = $image->scale(width: config('business.productImgSize.product_img_size.medium_img_width'));
                $mediumImage->save("uploads/products/medium_img/$name");
                $validatedData['medium_image'] = "uploads/products/medium_img/$name";

                $smallImage = $image->scale(width: config('business.productImgSize.product_img_size.small_img_width'));
                $smallImage->save("uploads/products/small_img/$name");
                $validatedData['small_image'] = "uploads/products/small_img/$name";
                // $uploadedFile->move('uploads/products', $name);
                // $validatedData['image'] = "uploads/products/$name";
            }

            // Update the product
            $product->update([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'] ?? Str::slug($validatedData['name']),
                'image' => $validatedData['image'] ?? $product->image,
                'medium_image' => $validatedData['medium_image'] ?? $product->medium_image,
                'small_image' => $validatedData['small_image'] ?? $product->small_image,
                'description' => $validatedData['description'],
                // 'stock_quantity' => $validatedData['stock_quantity'],
                'price' => $validatedData['price'],
                'is_active' => $validatedData['is_active'],
                'currency_id' => defaultCurrency()->id,
            ]);
            // update category and subcategory
            $productCategory = ProductCategory::where('product_id', $product->id)->first();
            $productCategory->update([
                'category_id' => $validatedData['category_id'],
                'sub_category_id' => $validatedData['sub_category_id'] ?? null,
            ]);
            $product->tags()->sync($validatedData['tags_id']);
            // Update the default variant

            // Handle variants
            if ($request->has('variants')) {
                foreach ($request->input('variants') as $variantData) {
                    if (isset($variantData['id'])) {
                        // Update existing variant
                        $variant = ProductVariant::findOrFail($variantData['id']);
                        if ($variant) {
                            $variant->update([
                                'sku' => $variantData['sku'],
                                'price' => $variantData['price'],
                                'stock' => $variantData['stock'],
                                'currency_id' => defaultCurrency()->id,
                            ]);
                        }

                        // Handle attribute values if needed
                        // foreach ($variantData['attributes'] as $attributeId => $value) {
                        //     $variant->attributeValues()->updateOrCreate(
                        //         ['attribute_id' => $attributeId],
                        //         ['value' => $value]
                        //     );
                        // }
                    } else {
                        // Create new variant
                        //  dd($variantData);
                        $productVariant = ProductVariant::create([
                            'sku' => $variantData['sku'],
                            'product_id' => $product->id,
                            'price' => $variantData['price'],
                            'stock' => $variantData['stock'],
                            'currency_id' => defaultCurrency()->id,
                        ]);

                        // Save attribute values
                        foreach ($variantData['attributes'] as $attributeId => $value) {
                            VariantAttributeValue::create([
                                'variant_id' => $productVariant->id,
                                'attribute_id' => $attributeId,
                                'value' => $value,
                            ]);
                        }
                    }
                }
            }
            $productVariant = ProductVariant::where('product_id', $product->id)->where('is_default', 1)->first();
            if(!$productVariant){
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $validatedData['name'],
                    'price' => $validatedData['price'],
                    'stock' => 10,
                    'currency_id' => defaultCurrency()->id,
                    'is_default' => true,
                ]);
            }
            // Generate SEO metadata for the product
            $categoryName = Category::where('id', $validatedData['category_id'])->pluck('name')->first();
            $subCategoryName = SubCategory::where('id', $validatedData['sub_category_id'])->pluck('name')->first() ?? null;
            $tags = Tag::whereIn('id', $validatedData['tags_id'])->get();
            $this->seoMetadataService->generateForProduct($product, $categoryName, $subCategoryName, $tags, $request->variants);

            return redirect()->route('admin.products.index')->with('success', __('messages.product_updated'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }

    public function createDir()
    {
        $dir = [
            "large_img",
            "medium_img",
            "small_img",
        ];

        foreach ($dir as $d) {
            $path = public_path("uploads/products/{$d}");

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

}
