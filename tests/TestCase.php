<?php

namespace Tests;

use Database\Seeders\LaratrustSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use NextApps\TestHelpers\MakesCustomAssertions;
use NextApps\TestHelpers\MocksPolicies;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        MakesCustomAssertions,
        MocksPolicies,
        RefreshDatabase,
        WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->seed(LaratrustSeeder::class);

        Storage::fake();
        Notification::fake();

        $this->withoutVite();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[AuthenticateAsOAuthUser::class])) {
            $this->setUpOAuthUser();
        }

        if (isset($uses[AuthenticateAsWebUser::class])) {
            $this->setUpWebAdminUser();
        }
    }

    protected function tearDown() : void
    {
        parent::tearDown();
    }

    public function assertSameSecond(Carbon $expected, Carbon $actual) : void
    {
        PHPUnit::assertTrue(
            $expected->isSameSecond($actual),
            "Given date `{$actual->format('c')}` is not the same as expected date `{$expected->format('c')}`."
        );
    }
}
