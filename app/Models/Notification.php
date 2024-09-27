<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  use HasFactory;
    protected $fillable = [
        'user_id',
        'message',
         'status',
        'created_at',
        'updated_at',
        'order_id',
    ];

     // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }


}