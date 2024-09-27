<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'location',
        'status_updated_at',
        'expected_delivery_at',
        'tracking_number',
        'provider_id',
    ];

    //relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function provider()
    {
        return $this->belongsTo(ShippingCompany::class, 'provider_id');
    }
}
