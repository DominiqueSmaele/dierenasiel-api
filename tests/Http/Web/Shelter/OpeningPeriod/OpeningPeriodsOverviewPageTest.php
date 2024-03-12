<?php

namespace Tests\Http\Web\Shelter\OpeningPeriod;

use App\Http\Livewire\Shelter\OpeningPeriod\OpeningPeriodsOverviewPage;
use App\Models\OpeningPeriod;
use App\Models\Shelter;
use App\Policies\AdminDashboard\OpeningPeriodPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class OpeningPeriodsOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_shelter_detail_route()
    {
        $this->get("shelter/{$this->shelter->id}/information")
            ->assertStatus(200)
            ->assertSeeLivewire(OpeningPeriodsOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_opening_periods_of_shelter()
    {
        $openingPeriods = OpeningPeriod::factory()->for($this->shelter)->count(3)->create();
        $otherOpeningPeriods = OpeningPeriod::factory()->count(3)->create();

        Livewire::test(OpeningPeriodsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('openingPeriods', function ($items) use ($openingPeriods) {
                $this->assertEqualsArray($openingPeriods->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_opening_periods_by_day()
    {
        $secondOpeningPeriod = OpeningPeriod::factory()->for($this->shelter)->create(['day' => 2]);
        $firstOpeningPeriod = OpeningPeriod::factory()->for($this->shelter)->create(['day' => 1]);
        $thirdOpeningPeriod = OpeningPeriod::factory()->for($this->shelter)->create(['day' => 3]);

        Livewire::test(OpeningPeriodsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('openingPeriods', function ($items) use ($firstOpeningPeriod, $secondOpeningPeriod, $thirdOpeningPeriod) {
                $this->assertSame($firstOpeningPeriod->id, $items[0]->id);
                $this->assertSame($secondOpeningPeriod->id, $items[1]->id);
                $this->assertSame($thirdOpeningPeriod->id, $items[2]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_opening_periods_allowed_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldAllow('view', $this->shelter);

        Livewire::test(OpeningPeriodsOverviewPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_opening_periods_denied_by_policy()
    {
        $this->partialMockPolicy(OpeningPeriodPolicy::class)->forUser($this->user)->shouldDeny('view', $this->shelter);

        Livewire::test(OpeningPeriodsOverviewPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
