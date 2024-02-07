<?php

namespace App\Models\Pivots;

use App\Models\Animal;
use App\Models\Quality;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnimalQuality extends Pivot
{
    protected $fillable = [
        'value',
    ];

    protected $casts = [
        'value' => 'boolean',
    ];

    public function animal() : BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function quality() : BelongsTo
    {
        return $this->belongsTo(Quality::class);
    }
}
