<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Models\Team as TeamModel;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Shelter extends TeamModel implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'facebook',
        'instagram',
        'tiktok',
    ];

    protected $casts = [
        'phone' => E164PhoneNumberCast::class . ':BE',
        'address_id' => 'integer',
    ];

    protected $with = [
        'media',
    ];

    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    public function animals() : HasMany
    {
        return $this->hasMany(Animal::class);
    }

    public function openingPeriods() : HasMany
    {
        return $this->hasMany(OpeningPeriod::class);
    }

    protected function image() : Attribute
    {
        return Attribute::make(get: fn () : ?Media => $this->getFirstMedia('image'));
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null) : void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, width: 256)
            ->keepOriginalImageFormat()
            ->performOnCollections('image');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, width: 512)
            ->keepOriginalImageFormat()
            ->performOnCollections('image');
    }
}
