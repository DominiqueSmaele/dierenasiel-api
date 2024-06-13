<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'shelter_id' => 'integer',
        'volunteer_id' => 'integer',
    ];

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function volunteer() : BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function getDateAttribute($value) : string
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
