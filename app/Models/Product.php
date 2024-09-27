<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image',
        'small_image',
        'description',
        'price',
        'stock_quantity',
        'is_active',
        'is_deleted',
        'medium_image',
        'currency_id',
        'additional_description',
        'additiona_info',
        'shipping_return'
    ];

    //relationship
    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'sub_category_id');
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->withPivot('sub_category_id')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tags', 'product_id', 'tags_id');
    }

    public function ProductVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withPivot('value');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function convertedPrice($symbol = false)
    {
        if ($symbol) {
            return toCurrency($this->price, $this->currency_id);
        }
        return toPrice($this->price, $this->currency_id);
    }

    public function seoMetaData()
    {
        return $this->hasOne(SeoMetadata::class, 'entity_id')->where('entity_type', 'product');
    }

}
