<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_name',
        'email',
        'phone_number',
        'address',
        'order_date',
    ];


    public function trackings()
    {
        return $this->hasMany(OrderTracking::class, 'provider_id');
    }
}
