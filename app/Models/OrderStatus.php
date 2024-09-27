<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';
    
    protected $fillable = [
        'order_id',
        'status',
        'location',
        'status_date',
    ];

    // Define the relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
