<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'currency_id', 'is_default'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'variant_id', 'id');
    }

    public function convertedPrice($symbol = false)
    {
        if ($symbol) {
            return toCurrency($this->price, $this->currency_id);
        }

        return toPrice($this->price, $this->currency_id);
    }

}
