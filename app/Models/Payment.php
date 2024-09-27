<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'total_amount',
        'payment_method',
        'payment_date',
        'transaction_id',
        'payment_status',
    ];

    //relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
