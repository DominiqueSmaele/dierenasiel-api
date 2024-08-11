<?php

namespace App\Models;

use App\Models\Builders\AnimalBuilder;
use App\Models\Pivots\AnimalQuality;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'birth_date',
        'race',
        'description',
    ];

    protected $casts = [
        'birth_date' => 'datetime',
        'type_id' => 'integer',
        'shelter_id' => 'integer',
    ];

    protected $with = [
        'media',
    ];

    public function newEloquentBuilder($query) : AnimalBuilder
    {
        return new AnimalBuilder($query);
    }

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function qualities() : BelongsToMany
    {
        return $this->belongsToMany(Quality::class, 'animal_quality')
            ->withPivot(['id', 'value'])
            ->withTimeStamps()
            ->using(AnimalQuality::class);
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
