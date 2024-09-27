<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'is_active',
        'is_deleted',
    ];

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_tags');
    // }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }
}
