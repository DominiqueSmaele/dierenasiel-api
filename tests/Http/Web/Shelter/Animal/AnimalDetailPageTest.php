<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\AnimalDetailPage;
use App\Models\Animal;
use App\Policies\AdminDashboard\AnimalPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class AnimalDetailPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Animal $animal;

    public function setUp() : void
    {
        parent::setUp();

        $this->animal = Animal::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_animal_detail_route()
    {
        $this->get("animal/{$this->animal->id}")
            ->assertStatus(200)
            ->assertSeeLivewire(AnimalDetailPage::class);
    }

    /** @test */
    public function it_shows_animal_detail()
    {
        Livewire::test(AnimalDetailPage::class, ['animal' => $this->animal])
            ->assertViewHas('animal', function ($item) {
                $this->assertSame($this->animal->id, $item->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_animal_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('view', $this->animal);

        Livewire::test(AnimalDetailPage::class, ['animal' => $this->animal])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_animal_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('view', $this->animal);

        Livewire::test(AnimalDetailPage::class, ['animal' => $this->animal])->assertForbidden();
    }

    /** @test */
    public function it_shows_update_animal_button_if_update_animal_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('update', $this->animal);

        Livewire::test(AnimalDetailPage::class, ['animal' => $this->animal])
            ->assertSee('shelter.update-animal-slide-over');
    }

    /** @test */
    public function it_does_not_show_update_animal_button_if_update_animal_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('update', $this->animal);

        Livewire::test(AnimalDetailPage::class, ['animal' => $this->animal])
            ->assertDontSee('shelter.update-animal-slide-over');
    }
}
