<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\EmailLog;

class LogSentMessage 
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {  
        $email = $event->sent->getOriginalMessage();
         // Get an array of recipient addresses
        $recipients = $email->getTo();
        $to = '';
         // Extract email addresses from the Address objects
        foreach ($recipients as $recipient) {
            $to = $recipient->getAddress(); 
            break;
        }
        $alreadyRecorded = EmailLog::where('to_email', $to)
                            ->where('created_at', date('Y-m-d H:i:s'))
                            ->first();

        if(!$alreadyRecorded){ 
            $input = [
                'to_email' => $to,
                'subject' => $email->getSubject(),
                'status' => 1,
                'failed_reason' => '',
                'body'  => $email->getHtmlBody()
            ]; 
            EmailLog::create($input);
        }
    }
}
