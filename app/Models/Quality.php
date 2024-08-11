<?php

namespace App\Models;

use App\Models\Pivots\AnimalQuality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'type_id' => 'integer',
    ];

    public function animals() : BelongsToMany
    {
        return $this->belongsToMany(Animal::class, 'animal_quality')
            ->withPivot('value')
            ->withTimestamps()
            ->using(AnimalQuality::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
