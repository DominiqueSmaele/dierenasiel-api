<?php

namespace App\Models;

use App\Models\Casts\AsPoint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'coordinates',
        'street',
        'number',
        'box_number',
        'zipcode',
        'city',
    ];

    protected $casts = [
        'coordinates' => AsPoint::class,
        'country_id' => 'integer',
    ];

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }
}
