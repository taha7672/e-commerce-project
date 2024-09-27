<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SitesSetting extends Model
{
    use HasFactory;

    public function language()
    {
        return $this->belongsTo(Language::class, 'selected_language_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'selected_currencies_id');
    }
}
