<?php

namespace Tests\Http\Web\Global\Shelter;

use App\Http\Livewire\Global\Shelter\DeleteShelterModal;
use App\Models\Shelter;
use App\Policies\AdminDashboard\ShelterPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteShelterModalTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_deletes_shelter()
    {
        Livewire::test(DeleteShelterModal::class, [$this->shelter->id])
            ->call('delete')
            ->assertDispatched('shelterDeleted')
            ->assertDispatched('modal.close');

        $dbDeletedShelter = Shelter::onlyTrashed()->find($this->shelter->id);

        $this->assertNotNull($dbDeletedShelter);
    }

    /** @test */
    public function it_returns_success_response_if_delete_shelter_allowed_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldAllow('delete', $this->shelter);

        Livewire::test(DeleteShelterModal::class, [$this->shelter->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_shelter_denied_by_policy()
    {
        $this->partialMockPolicy(ShelterPolicy::class)->forUser($this->user)->shouldDeny('delete', $this->shelter);

        Livewire::test(DeleteShelterModal::class, [$this->shelter->id])->assertForbidden();
    }
}
