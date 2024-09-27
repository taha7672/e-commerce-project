<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'is_active',
        'is_deleted',
    ];

    //relations
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
    // relationship with seo metadata 
    public function seoMetaData()
    {
        // return $this->morphOne(SeoMetadata::class, 'entity', 'entity_type', 'entity_id');
        return $this->hasOne(SeoMetadata::class, 'entity_id')->where('entity_type', 'category');

    }

}
