<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
     * Get the user that owns the billing address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders that use this billing address.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
