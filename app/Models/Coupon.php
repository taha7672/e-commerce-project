<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_percentage',
        'discount_amount',
        'expiry_date',
        'minimum_order_amount',
        'one_time_use',
        'currency_id',
    ];

    //relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function convertedAmt($symbol = false) {
        if ($symbol) {
            return toCurrency($this->discount_amount, $this->currency_id);
        }
        
        return toPrice($this->discount_amount, $this->currency_id);
    }

}
