<?php

namespace Tests\Http\Resources;

use App\Http\Resources\ImageResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class ImageResourceTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Schema::create('model_with_conversions', function (Blueprint $table) {
            $table->temporary();
            $table->id();
            $table->timestamps();
        });

        Relation::enforceMorphMap([
            'model_with_conversions' => ModelWithConversions::class,
        ]);

        Route::get('image-resource', function () {
            $model = ModelWithConversions::first();

            return ImageResource::make($model->getFirstMedia('image'))->ofModel($model);
        });
    }

    /** @test */
    public function it_returns_urls_with_correct_width_and_height()
    {
        $image = ModelWithConversions::create()
            ->addMedia(UploadedFile::fake()->image('image.png', 700, 800))
            ->toMediaCollection('image');

        $this->getJson('image-resource')
            ->assertExactJson([
                'data' => [
                    'original' => [
                        'url' => $image->getFullUrl(),
                        'width' => 700,
                        'height' => 800,
                    ],
                    'conversions' => [
                        [
                            'url' => $image->getFullUrl('no-resize'),
                            'width' => 700,
                            'height' => 800,
                        ],
                        [
                            'url' => $image->getFullUrl('only-width-resize-to-300px'),
                            'width' => 300,
                            'height' => (int) (800 * (300 / 700)),
                        ],
                        [
                            'url' => $image->getFullUrl('only-height-resize-to-400px'),
                            'width' => (int) (700 * (400 / 800)),
                            'height' => 400,
                        ],
                        [
                            'url' => $image->getFullUrl('width-and-height-resize-to-500px-and-600px'),
                            'width' => 500,
                            'height' => 600,
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_only_returns_generated_conversions()
    {
        $image = ModelWithConversions::create()
            ->addMedia(UploadedFile::fake()->image('image.png', 700, 800))
            ->toMediaCollection('image')
            ->fresh()
            ->markAsConversionNotGenerated('only-height-resize-to-400px');

        $this->getJson('image-resource')
            ->assertExactJson([
                'data' => [
                    'original' => [
                        'url' => $image->getFullUrl(),
                        'width' => 700,
                        'height' => 800,
                    ],
                    'conversions' => [
                        [
                            'url' => $image->getFullUrl('no-resize'),
                            'width' => 700,
                            'height' => 800,
                        ],
                        [
                            'url' => $image->getFullUrl('only-width-resize-to-300px'),
                            'width' => 300,
                            'height' => (int) (800 * (300 / 700)),
                        ],
                        [
                            'url' => $image->getFullUrl('width-and-height-resize-to-500px-and-600px'),
                            'width' => 500,
                            'height' => 600,
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_only_returns_conversions_that_did_not_upscale_the_original_image_if_original_width_is_too_small()
    {
        $image = ModelWithConversions::create()
            ->addMedia(UploadedFile::fake()->image('image.png', 250, 450))
            ->toMediaCollection('image');

        $this->getJson('image-resource')
            ->assertExactJson([
                'data' => [
                    'original' => [
                        'url' => $image->getFullUrl(),
                        'width' => 250,
                        'height' => 450,
                    ],
                    'conversions' => [
                        [
                            'url' => $image->getFullUrl('no-resize'),
                            'width' => 250,
                            'height' => 450,
                        ],
                        [
                            'url' => $image->getFullUrl('only-height-resize-to-400px'),
                            'width' => (int) (250 * (400 / 450)),
                            'height' => 400,
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_only_returns_conversions_that_did_not_upscale_the_original_image_if_original_height_is_too_small()
    {
        $image = ModelWithConversions::create()
            ->addMedia(UploadedFile::fake()->image('image.png', 350, 250))
            ->toMediaCollection('image');

        $this->getJson('image-resource')
            ->assertExactJson([
                'data' => [
                    'original' => [
                        'url' => $image->getFullUrl(),
                        'width' => 350,
                        'height' => 250,
                    ],
                    'conversions' => [
                        [
                            'url' => $image->getFullUrl('no-resize'),
                            'width' => 350,
                            'height' => 250,
                        ],
                        [
                            'url' => $image->getFullUrl('only-width-resize-to-300px'),
                            'width' => 300,
                            'height' => (int) (250 * (300 / 350)),
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function it_throws_runtime_exception_if_model_of_media_is_not_provided()
    {
        $image = ModelWithConversions::create()
            ->addMedia(UploadedFile::fake()->image('image.png'))
            ->toMediaCollection('image');

        $this->assertThrows(
            fn () => ImageResource::make($image)->toArray(request()),
            RuntimeException::class,
            'Model of media is not provided'
        );
    }

    /** @test */
    public function it_throws_runtime_exception_if_provided_model_does_not_match_model_of_media()
    {
        $model = ModelWithConversions::create();
        $image = $model->addMedia(UploadedFile::fake()->image('image.png'))->toMediaCollection('image');

        $this->assertThrows(
            fn () => ImageResource::make($image)->ofModel(ModelWithConversions::make()->forceFill(['id' => $model->id + 1])),
            RuntimeException::class,
            'Model does not match model of media'
        );

        $this->assertThrows(
            fn () => ImageResource::make($image)->ofModel(User::make()->forceFill(['id' => $model->id])),
            RuntimeException::class,
            'Model does not match model of media'
        );
    }
}

class ModelWithConversions extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null) : void
    {
        $this->addMediaConversion('only-width-resize-to-300px')
            ->width(300)
            ->keepOriginalImageFormat()
            ->performOnCollections('image');

        $this->addMediaConversion('only-height-resize-to-400px')
            ->height(400)
            ->keepOriginalImageFormat()
            ->performOnCollections('image');

        $this->addMediaConversion('width-and-height-resize-to-500px-and-600px')
            ->width(500)
            ->height(600)
            ->keepOriginalImageFormat()
            ->performOnCollections('image');

        $this->addMediaConversion('no-resize')
            ->keepOriginalImageFormat()
            ->performOnCollections('image');
    }
}
