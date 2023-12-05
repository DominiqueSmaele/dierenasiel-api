<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Locale;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
    ];

    public function getName() : string
    {
        return Locale::getDisplayRegion("-{$this->code}", app()->getLocale());
    }
}
