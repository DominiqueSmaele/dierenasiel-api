<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Animal extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        SoftDeletes;

    protected $fillable = [
        'name',
        'sex',
        'years',
        'months',
        'race',
        'description',
    ];

    protected $casts = [
        'type_id' => 'integer',
        'shelter_id' => 'integer',
    ];

    protected $with = [
        'media',
    ];

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(Type::class);
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
