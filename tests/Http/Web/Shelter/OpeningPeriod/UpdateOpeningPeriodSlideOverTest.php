<?php

namespace Tests\Http\Web\Shelter\OpeningPeriod;

use App\Http\Livewire\Shelter\OpeningPeriod\UpdateOpeningPeriodSlideOver;
use App\Models\OpeningPeriod;
use App\Models\Shelter;
use App\Policies\AdminDashboard\OpeningPeriodPolicy;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateOpeningPeriodSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public Collection $openingPeriods;

    public Carbon $open;

    public Carbon $close;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->openingPeriods = OpeningPeriod::factory()->for($this->shelter)->count(2)->create();

        $this->open = Carbon::parse($this->faker->time());
        $this->close = $this->open->copy()->addHours(3);
    }

    /** @test */
    public function it_updates_opening_periods()
    {
        Livewire::test(UpdateOpeningPeriodSlideOver::class, [$this->shelter->id])
            ->set('openingPeriods.0.open', $this->open)
            ->set('openingPeriods.0.close', $this->close)
            ->set('openingPeriods.1.open', $this->open->copy()->subHour())
            ->set('openingPeriods.1.close', $this->close->copy()->addHours(2))
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('openingPeriodsUpdated')
            ->assertDispatched('slide-over.close');

        $dbOpeningPeriods = $this->shelter->openingPeriods->sortBy('day');

        $this->assertSameMinute($this->open, $dbOpeningPeriods[0]->open);
        $this->assertSameMinute($this->close, $dbOpeningPeriods[0]->close);

        $this->assertSameMinute($this->open->copy()->subHour(), $dbOpeningPeriods[1]->open);
        $this->assertSameMinute($this->close->copy()->addHours(2), $dbOpeningPeriods[1]->close);
    }

    /** @test */
    public function it_throws_validation_error_if_open_or_close_time_is_missing_when_one_of_them_is_present()
    {
        Livewire::test(UpdateOpeningPeriodSlideOver::class, [$this->shelter->id])
            ->set('openingPeriods.0.close', null)
            ->set('openingPeriods.1.open', null)
            ->call('update')
            ->assertHasErrors([
                'openingPeriods.0.close',
                'openingPeriods.1.open',
            ])
            ->assertHasNoErrors([
                'openingPeriods.0.open',
                'openingPeriods.1.close',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_update_opening_periods_allowed_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldAllow('update', $this->shelter);

        Livewire::test(UpdateOpeningPeriodSlideOver::class, [$this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_opening_periods_denied_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldDeny('update', $this->shelter);

        Livewire::test(UpdateOpeningPeriodSlideOver::class, [$this->shelter->id])->assertForbidden();
    }
}
