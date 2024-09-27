<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_num',
        'coupon_id',
        'total_amount',
        'status',
        'order_date',
		'vat_amount',
		'discount_amount',
		'paid_amount',
        'billing_address_id',
        'shipping_address_id',
      'shipping_amount',
        'currency_id',
    ];

    protected static function booted()
    {
        static::created(function ($order) {
            if ($order->order_num == null) {
                $order->order_num = uniqueOrderNumber($order);
                $order->save();
            }
        });

        static::updated(function ($order) {
            $changes = array_diff($order->getOriginal(), $order->getAttributes());

            /**
             * create status history only if order status was changed
             */
            if( array_key_exists('status', $changes) ) {
                OrderStatus::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'status_date' => date('Y-m-d H:i:s'),
                    'location' => ''
                ]);
				send_order_status_change_email($order);
            }
        });
    }

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatus()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function orderBillingAddress()
    {
        return $this->hasOne(OrderBillingAddress::class);
    }
    public function orderShippingAddress()
    {
        return $this->hasOne(OrderShippingAddress::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

     /**
     * Get the shipping address associated with the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }

    /**
     * Get the billing address associated with the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class, 'billing_address_id');
    }

    // public function orderShippingAddress()
    // {
    //     return $this->hasOne(OrderShippingAddress::class);
    // }

    // public function orderBillingAddress()
    // {
    //     return $this->hasOne(OrderBillingAddress::class);
    // }

       public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}


