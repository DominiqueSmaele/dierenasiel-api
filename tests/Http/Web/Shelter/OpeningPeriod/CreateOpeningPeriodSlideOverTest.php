<?php

namespace Tests\Http\Web\Shelter\OpeningPeriod;

use App\Http\Livewire\Shelter\OpeningPeriod\CreateOpeningPeriodSlideOver;
use App\Models\Shelter;
use App\Policies\AdminDashboard\OpeningPeriodPolicy;
use Carbon\Carbon;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateOpeningPeriodSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public Carbon $open;

    public Carbon $close;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->open = Carbon::parse($this->faker->time($max = '18:59:59'));
        $this->close = $this->open->copy()->addHours(3);
    }

    /** @test */
    public function it_creates_opening_period()
    {
        Livewire::test(CreateOpeningPeriodSlideOver::class, [$this->shelter->id])
            ->set('openingPeriods.0.open', $this->open)
            ->set('openingPeriods.0.close', $this->close)
            ->set('openingPeriods.3.open', $this->open->copy()->subHour())
            ->set('openingPeriods.3.close', $this->close->copy()->addHours(2))
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('openingPeriodsCreated')
            ->assertDispatched('slide-over.close');

        $dbOpeningPeriods = $this->shelter->openingPeriods->sortBy('day');

        $this->assertSameMinute($this->open, $dbOpeningPeriods[0]->open);
        $this->assertSameMinute($this->close, $dbOpeningPeriods[0]->close);

        $this->assertSameMinute($this->open->copy()->subHour(), $dbOpeningPeriods[3]->open);
        $this->assertSameMinute($this->close->copy()->addHours(2), $dbOpeningPeriods[3]->close);
    }

    /** @test */
    public function it_throws_validation_error_if_open_or_close_time_is_missing_when_one_of_them_is_present()
    {
        Livewire::test(CreateOpeningPeriodSlideOver::class, [$this->shelter->id])
            ->set('openingPeriods.0.open', $this->open)
            ->set('openingPeriods.0.close', null)
            ->set('openingPeriods.3.open', null)
            ->set('openingPeriods.3.close', $this->close)
            ->call('create')
            ->assertHasErrors([
                'openingPeriods.0.close',
                'openingPeriods.3.open',
            ])
            ->assertHasNoErrors([
                'openingPeriods.0.open',
                'openingPeriods.3.close',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_create_opening_periods_allowed_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldAllow('create', $this->shelter);

        Livewire::test(CreateOpeningPeriodSlideOver::class, [$this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_create_opening_periods_denied_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldDeny('create', $this->shelter);

        Livewire::test(CreateOpeningPeriodSlideOver::class, [$this->shelter->id])->assertForbidden();
    }
}
