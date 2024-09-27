<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
     use HasFactory;
     protected $fillable = [
        'image',
        'sub_title',
        'title',
        'title_color',
        'link',
        'button_text',
        'small_image',
        'medium_image',
     ];
     protected $table = 'slider';

}
