<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachments extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'file_path'
    ];
    public $timestamps = false;

    protected $table='ticket_attachments';
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
