<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBillingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
		'first_name',
		'last_name',
		'phone',
		'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    /**
     * Get the order that owns the billing address.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
 
}
