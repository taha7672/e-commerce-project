<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tags_id',
    ];

    //relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class,'tags_id');
    }
}
