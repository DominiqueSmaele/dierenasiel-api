<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Volunteer extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
