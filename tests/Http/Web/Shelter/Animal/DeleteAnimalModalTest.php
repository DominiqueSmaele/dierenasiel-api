<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\DeleteAnimalModal;
use App\Models\Animal;
use App\Policies\AdminDashboard\AnimalPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteAnimalModalTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Animal $animal;

    public function setUp() : void
    {
        parent::setUp();

        $this->animal = Animal::factory()->create();
    }

    /** @test */
    public function it_deletes_animal()
    {
        Livewire::test(DeleteAnimalModal::class, [$this->animal->id])
            ->call('delete')
            ->assertDispatched('animalDeleted')
            ->assertDispatched('modal.close');

        $dbDeletedAnimal = Animal::onlyTrashed()->find($this->animal->id);

        $this->assertNotNull($dbDeletedAnimal);
    }

    /** @test */
    public function it_returns_success_response_if_delete_animal_allowed_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldAllow('delete', $this->animal);

        Livewire::test(DeleteAnimalModal::class, [$this->animal->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_animal_denied_by_policy()
    {
        $this->partialMockPolicy(AnimalPolicy::class)->forUser($this->user)->shouldDeny('delete', $this->animal);

        Livewire::test(DeleteAnimalModal::class, [$this->animal->id])->assertForbidden();
    }
}
