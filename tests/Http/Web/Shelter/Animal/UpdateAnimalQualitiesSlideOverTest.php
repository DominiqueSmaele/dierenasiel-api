<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\UpdateAnimalQualitiesSlideOver;
use App\Models\Animal;
use App\Models\Quality;
use App\Policies\AdminDashboard\AnimalPolicy;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateAnimalQualitiesSlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Animal $animal;

    public Collection $qualities;

    public function setUp() : void
    {
        parent::setUp();

        $this->animal = Animal::factory()->create();

        $this->qualities = Quality::factory()->for($this->animal->type)->count(3)->create();

        $this->animal->qualities()->syncWithoutDetaching(
            $this->qualities->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_syncs_missing_qualities_to_animal_before_update()
    {
        $this->qualities->push(Quality::factory()->for($this->animal->type)->create());

        Livewire::test(UpdateAnimalQualitiesSlideOver::class, [$this->animal->id]);

        $dbAnimal = Animal::first();

        $this->assertCount(count($this->qualities), $dbAnimal->qualities);
    }

    /** @test */
    public function it_updates_animal_qualities()
    {
        Livewire::test(UpdateAnimalQualitiesSlideOver::class, [$this->animal->id])
            ->set('animalQualities.0.value', true)
            ->set('animalQualities.1.value', false)
            ->set('animalQualities.2.value', null)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('animalQualitiesUpdated');

        $dbAnimalQualities = Animal::first()->qualities->sortBy('name')->pluck('pivot');

        $this->assertEquals(1, $dbAnimalQualities[0]->value);
        $this->assertEquals(0, $dbAnimalQualities[1]->value);
        $this->assertNull($dbAnimalQualities[2]->value);
    }

    /** @test */
    public function it_returns_success_response_if_update_animal_qualities_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('updateQualities', $this->animal);

        Livewire::test(UpdateAnimalQualitiesSlideOver::class, [$this->animal->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_animal_qualities_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('updateQualities', $this->animal);

        Livewire::test(UpdateAnimalQualitiesSlideOver::class, [$this->animal->id])->assertForbidden();
    }
}
