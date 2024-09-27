<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'is_active',
        'is_deleted',
    ];

    //relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'sub_category_id', 'product_id');
    }
    // relationship with seo metadata
    public function seoMetaData()
    {
        return $this->hasOne(SeoMetadata::class, 'entity_id')->where('entity_type', 'sub_category');
    }
}
