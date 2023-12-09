<?php

namespace Tests\Http\Web\Global\Shelter;

use App\Http\Livewire\Global\Shelter\SheltersOverviewPage;
use App\Models\Shelter;
use App\Policies\AdminDashboard\ShelterPolicy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class SheltersOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public function setUp() : void
    {
        parent::setUp();
    }

    /** @test */
    public function it_is_accessible_on_shelters_route()
    {
        $this->get('/shelters')
            ->assertStatus(200)
            ->assertSeeLivewire(SheltersOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_shelters()
    {
        $shelters = Shelter::factory()->count(3)->create();

        Livewire::test(SheltersOverviewPage::class)
            ->assertViewHas('shelters', function ($items) use ($shelters) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($shelters->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_shelters_by_name_and_id()
    {
        $secondShelter = Shelter::factory()->create(['name' => 'b']);
        $firstShelter = Shelter::factory()->create(['name' => 'a']);
        $thirdShelter = Shelter::factory()->create(['name' => 'b']);

        Livewire::test(SheltersOverviewPage::class)
            ->assertViewHas('shelters', function ($items) use ($firstShelter, $secondShelter, $thirdShelter) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertSame($firstShelter->id, $items[0]->id);
                $this->assertSame($secondShelter->id, $items[1]->id);
                $this->assertSame($thirdShelter->id, $items[2]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_shelter_allowed_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldAllow('viewAny');

        Livewire::test(SheltersOverviewPage::class)->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_shelter_denied_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldDeny('viewAny');

        Livewire::test(SheltersOverviewPage::class)->assertForbidden();
    }
}
