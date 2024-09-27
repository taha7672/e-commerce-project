<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'activity_type',
        'ip_address',
        'city',
        'country',
        'logged_in',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
