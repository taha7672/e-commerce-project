<?php
namespace App\DTOs;

use Illuminate\Http\Request;

class TicketDTO
{
    public $subject;
    public $description;
    public $status;
    public $priority;
    public $assigned_to;
    public $user_id;

    public function __construct(Request $request)
    {
        $this->subject = $request->input('subject');
        $this->description = $request->input('description');
        $this->status = $request->input('status');
        $this->priority = $request->input('priority');
        $this->assigned_to = $request->input('assigned_to');
        $this->user_id = $request->input('user');
    }

    public function toArray(): array
    {
        return [
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'assigned_to' => $this->assigned_to,
            'user_id' => $this->user_id,
        ];
    }
}
