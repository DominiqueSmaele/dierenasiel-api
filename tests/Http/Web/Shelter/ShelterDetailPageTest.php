<?php

namespace Tests\Http\Web\Shelter;

use App\Http\Livewire\Shelter\ShelterDetailPage;
use App\Models\Shelter;
use App\Policies\AdminDashboard\ShelterPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class ShelterDetailPageTest extends TestCase
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
            ->assertSeeLivewire(ShelterDetailPage::class);
    }

    /** @test */
    public function it_returns_success_response_if_view_shelter_detail_allowed_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldAllow('view', $this->shelter);

        Livewire::test(ShelterDetailPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_shelter_detail_denied_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldDeny('view', $this->shelter);

        Livewire::test(ShelterDetailPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
