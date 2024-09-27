<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'subject', 'body'];
	
	
    public function parseShortcodes($replacements)
    {
        $body = $this->body;

        foreach ($replacements as $shortcode => $value) {
            $body = str_replace("[$shortcode]", $value, $body);
        }

        return $body;
    }
    public function parseSubjectShortcodes($replacements)
    {
        $subject = $this->subject;

        foreach ($replacements as $shortcode => $value) {
            $subject = str_replace("[$shortcode]", $value, $subject);
        }

        return $subject;
    }
}
