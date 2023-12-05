<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Shelter extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $casts = [
        'phone' => E164PhoneNumberCast::class,
        'address_id' => 'integer',
    ];

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }
}
