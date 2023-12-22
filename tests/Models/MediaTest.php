<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class MediaTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Schema::create('model_with_media', function (Blueprint $table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });

        Relation::enforceMorphMap([
            'model_with_media' => ModelWithMedia::class,
        ]);
    }

    /** @test */
    public function it_saves_image_dimensions_as_custom_properties()
    {
        $model = ModelWithMedia::create();

        $model->addMedia(UploadedFile::fake()->image('image.png', 50, 20))
            ->withCustomProperties(['foo' => 'bar'])
            ->toMediaCollection('image');

        $this->assertSame([
            'foo' => 'bar',
            'dimensions' => ['width' => 50, 'height' => 20],
        ], Media::first()->custom_properties);
    }

    /** @test */
    public function it_does_not_saves_image_dimensions_as_custom_properties_if_file_is_no_image()
    {
        $model = ModelWithMedia::create();

        $model->addMedia(UploadedFile::fake()->create('no-image.txt'))
            ->withCustomProperties(['foo' => 'bar'])
            ->toMediaCollection('image');

        $this->assertSame(['foo' => 'bar'], Media::first()->custom_properties);
    }
}

class ModelWithMedia extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
