<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'subject',
        'description',
        'status',
        'priority',
        'user_id',
        
        'assigned_to'
    ];

    protected $table='tickets';
    public function attachments()
    {
        return $this->hasMany(TicketAttachments::class);
    }
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    public function prioritys(){
        return $this->belongsTo(TicketPriority::class,'priority');
    }
   
}
