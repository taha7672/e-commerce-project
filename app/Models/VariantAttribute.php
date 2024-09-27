<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_name',
        'subcategory_id'
    ];

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'variant_attribute_values')->withPivot('value');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
}