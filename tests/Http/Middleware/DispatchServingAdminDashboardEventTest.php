<?php

namespace Tests\Http\Middleware;

use App\Events\ServingAdminDashboard;
use App\Http\Middleware\DispatchServingAdminDashboardEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DispatchServingAdminDashboardEventTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        Route::get('dispatch-serving-admin-dashboard-event', fn () => ['message' => 'ok'])->middleware(DispatchServingAdminDashboardEvent::class);
    }

    /** @test */
    public function it_dispatches_event()
    {
        Event::fake();

        $this->getJson('/dispatch-serving-admin-dashboard-event')->assertSuccessful()->assertExactJson(['message' => 'ok']);

        Event::assertDispatched(ServingAdminDashboard::class);
    }
}
