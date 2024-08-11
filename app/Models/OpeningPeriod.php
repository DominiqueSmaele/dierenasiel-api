<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
    ];

    protected $casts = [
        'open' => 'datetime',
        'close' => 'datetime',
        'shelter_id' => 'integer',
    ];

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }
}
