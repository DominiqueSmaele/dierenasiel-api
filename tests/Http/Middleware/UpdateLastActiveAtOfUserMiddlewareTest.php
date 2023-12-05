<?php

namespace Tests\Http\Middleware;

use App\Http\Middleware\UpdateLastActiveAtOfUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UpdateLastActiveAtOfUserMiddlewareTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Route::get('update-last-active-at-of-user', fn () => ['message' => 'ok'])->middleware(UpdateLastActiveAtOfUser::class);
    }

    /** @test */
    public function it_updates_last_active_at_of_auth_user_to_start_of_current_hour()
    {
        $this->freezeTime();

        $this->actingAs($user = User::factory()->create(['last_active_at' => $this->faker->dateTime()]))
            ->get('update-last-active-at-of-user')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);

        $this->assertSameMinute(now()->startOfHour(), User::find($user->id)->last_active_at);
    }

    /** @test */
    public function it_does_nothing_if_no_auth_user_is_present()
    {
        $this->get('update-last-active-at-of-user')
            ->assertSuccessful()
            ->assertExactJson(['message' => 'ok']);
    }
}
