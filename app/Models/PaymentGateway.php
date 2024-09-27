<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',            // Include 'type' column here
        'credentials',
        'status',
        'mode',
        'is_default',
    ];
}
