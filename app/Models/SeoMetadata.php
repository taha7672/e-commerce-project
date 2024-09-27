<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMetadata extends Model
{
    use HasFactory;
    // table name
    protected $table = 'seo_metadata_';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'entity_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'entity_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'entity_id');
    }





}
