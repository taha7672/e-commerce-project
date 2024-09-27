<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SitesSetting;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $order;

    public function __construct($template, $order = null)
    {
        $this->template = $template;
        $this->order = $order;
    }

    public function build()
    {
        $replacementArray = generate_replacement_array($this->order);

        // Replace placeholders in the subject and body
        $subject = $this->template->parseSubjectShortcodes($replacementArray);
        $email_body = $this->template->parseShortcodes($replacementArray);
        $customer_email = $this->to[0]['address'];
        return $this->subject($subject)
            ->view('emails.order-emails')
            ->with(compact('email_body'));
    }
}
