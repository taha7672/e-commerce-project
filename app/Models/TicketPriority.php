<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    use HasFactory;
    protected $fillable = ['id','name'];
    protected $table='ticket_priorities';
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'priority');
    }
}
