<?php

namespace Tests\Http\Middleware;

use App\Http\Middleware\DetectLocale;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DetectLocaleMiddlewareTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Route::get('detect-locale-middleware', fn () => ['message' => 'ok'])->middleware(DetectLocale::class);
    }

    /** @test */
    public function it_sets_app_locale_based_on_accept_language_header()
    {
        $this->withHeader('Accept-Language', 'en_US')
            ->get('detect-locale-middleware')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);

        $this->assertSame('en', app()->getLocale());
    }

    /** @test */
    public function it_sets_app_locale_to_fallback_locale_if_no_supported_locale_found_based_on_accept_language_header()
    {
        $this->withHeader('Accept-Language', 'foo')
            ->get('detect-locale-middleware')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);

        $this->assertSame('nl', app()->getLocale());
    }

    /** @test */
    public function it_updates_locale_of_user_based_on_accept_language_header()
    {
        $this->actingAs($user = User::factory()->create())
            ->withHeader('Accept-Language', 'en_US')
            ->get('detect-locale-middleware')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);

        $this->assertSame('en', User::find($user->id)->locale);
    }

    /** @test */
    public function it_updates_locale_of_user_based_on_accept_language_header_even_if_its_not_a_supported_locale()
    {
        $this->actingAs($user = User::factory()->create())
            ->withHeader('Accept-Language', 'foo')
            ->get('detect-locale-middleware')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);

        $this->assertSame('foo', User::find($user->id)->locale);
    }
}
