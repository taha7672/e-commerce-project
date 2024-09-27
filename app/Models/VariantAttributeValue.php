<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'attribute_id',
        'value',
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function variantAttribute()
    {
        return $this->belongsTo(VariantAttribute::class,'attribute_id');
    }
}