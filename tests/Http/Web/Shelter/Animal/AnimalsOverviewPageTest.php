<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\AnimalsOverviewPage;
use App\Models\Animal;
use App\Models\Shelter;
use App\Policies\AdminDashboard\AnimalPolicy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class AnimalsOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_animals_route()
    {
        $this->get("shelter/{$this->shelter->id}/animals")
            ->assertStatus(200)
            ->assertSeeLivewire(AnimalsOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_animals_of_shelter()
    {
        $otherAnimals = Animal::factory()->count(3)->create();
        $animals = Animal::factory()->for($this->shelter)->count(4)->create();

        Livewire::test(AnimalsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('animals', function ($items) use ($animals) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($animals->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_animals_by_name_and_id()
    {
        $secondAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'b']);
        $firstAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'a']);
        $thirdAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'b']);

        Livewire::test(AnimalsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('animals', function ($items) use ($firstAnimal, $secondAnimal, $thirdAnimal) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertSame($firstAnimal->id, $items[0]->id);
                $this->assertSame($secondAnimal->id, $items[1]->id);
                $this->assertSame($thirdAnimal->id, $items[2]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_animal_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('viewAny', $this->shelter);

        Livewire::test(AnimalsOverviewPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_animal_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('viewAny', $this->shelter);

        Livewire::test(AnimalsOverviewPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
