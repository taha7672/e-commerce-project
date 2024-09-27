<?php

namespace App\Imports;

use App\Models\{
    Category,
    Product,
    SubCategory,
    Tag,
    ProductCategory,
    ProductVariant,
    VariantAttributeValue,
    VariantAttribute,
};
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class ProductsImport implements WithHeadingRow, ToCollection
{
    public function __construct(public array $mapping, public string $imageBase = "uploads/products/")
    {
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            /** getter function that match with config's key */
            $get = fn($x) => $row[Str::slug($this->mapping[$x], '_')];

            $product = $this->createProduct($get);
            if (!$product) {
                continue;
            }

            $prodCat = $this->createCategory($product, $get);

            $this->createTag($product, $get);

            $variant = $this->createVariant($product, $get);

            if ($variant && $prodCat) {
                $this->createAttributes($prodCat, $variant, $row);
            }
        }
    }

    protected function createProduct($get)
    {
        $product = Product::where('name', $get('name'))->first();

        /** it is varient */
        if ($get('is_variant') != "simple") {
            return $product;
        }

        if ($product) {
            if ($get('description')) {
                $product->description = $get('description');
            }

            if ($get('default_price')) {
                $product->price = $get('default_price');
            }

            if ($get('status')) {
                $product->is_active = $get('status');
            }

            if ($get('img_name')) {
                $product->image = $this->imageBase . $get('img_name');
            }

            $product->save();
            return $product;
        }

        return Product::create([
            'name' => $get('name'),
            'slug' => Str::slug($get('name')),
            'image' => $get('img_name') ? $this->imageBase . $get('img_name') : null,
            'description' => $get('description'),
            'price' => $get('default_price'),
            'is_active' => ($get('status') == "1") ? 1 : 0,
            'currency_id' => defaultCurrency()->id,
        ]);
    }

    protected function createCategory(Product $product, $get)
    {
        $cat = Category::where('name', $get('category'))->first();
        if (!$cat) {
            $cat = Category::create([
                "name" => $get('category'),
                "slug" => Str::slug($get('category')),
                "is_active" => 1,
            ]);
        }

        $sub = SubCategory::where('name', $get('sub_category'))
            ->where('category_id', $cat->id)
            ->first();

        if (!$sub) {
            $sub = SubCategory::create([
                'category_id' => $cat->id,
                'name' => $get('sub_category'),
                'slug' => Str::slug($get('sub_category')),
                'is_active' => 1,
            ]);
        }

        $model = ProductCategory::where('product_id', $product->id)->first();

        if ($model) {
            $model->category_id = $cat->id;
            $model->sub_category_id = $sub->id;
            $model->save();

            return $model;
        }

        return ProductCategory::create([
            'product_id' => $product->id,
            'category_id' => $cat->id,
            'sub_category_id' => $sub->id
        ]);
    }

    protected function createVariant($product, $get)
    {
        $variant = ProductVariant::where('sku', $get('sku'))
            ->first();

        /** variant with SKU is found but have different product */
        if ($variant && $variant->product_id != $product->id) {
            return null;
        }

        if (!$variant) {
            return ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $get('sku'),
                'price' => $get('default_price'),
                'stock' => $get('quantity'),
                'currency_id' => defaultCurrency()->id,
            ]);
        }

        $variant->update([
            'price' => $get('default_price'),
            'stock' => $get('quantity'),
        ]);

        return $variant;
    }

    protected function createAttributes($prodCat, $variant, $row)
    {
        if (!$prodCat->sub_category_id) {
            return;
        }

        $names = [];
        $values = [];

        foreach ($row as $key => $value) {
            $match = null;
            preg_match("/attribute_(.*)_name/", $key, $match);
            if ($match) {
                $names[$match[1]] = $value;
            }

            $match = null;
            preg_match("/attribute_(.*)_value/", $key, $match);
            if ($match) {
                $values[$match[1]] = $value;
            }
        }

        foreach ($names as $i => $name) {
            $val = $values[$i] ?? "";

            if (trim($name) == "") {
                continue;
            }

            $attr = VariantAttribute::where('subcategory_id', $prodCat->sub_category_id)
                ->where('attribute_name', $name)
                ->first();

            if (!$attr) {
                $attr = VariantAttribute::create([
                    'attribute_name' => $name,
                    'subcategory_id' => $prodCat->sub_category_id,
                ]);
            }

            $attrVal = VariantAttributeValue::where('variant_id', $variant->id)
                ->where('attribute_id', $attr->id)
                ->first();

            if ($attrVal) {
                $attrVal->update(['value' => $val]);
                continue;
            }

            VariantAttributeValue::create([
                'variant_id' => $variant->id,
                'attribute_id' => $attr->id,
                'value' => $val,
            ]);
        }

    }

    protected function createTag($product, $get)
    {
        $tagNames = explode(",", $get('tags'));
        $tagNames = array_map(fn($n) => trim($n), $tagNames);
        $tags = Tag::whereIn('name', $tagNames)->get();

        $existed = $tags->pluck('name')->toArray();
        $tagIds = $tags->pluck('id');

        foreach ($tagNames as $name) {
            if (in_array($name, $existed)) {
                continue;
            }

            $model = Tag::create([
                'name' => $name,
                'is_active' => 1,
            ]);

            $tagIds[] = $model->id;
        }

        if ($tagIds) {
            $product->tags()->sync($tagIds);
        }
    }

}
