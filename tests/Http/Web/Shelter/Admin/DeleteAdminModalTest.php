<?php

namespace Tests\Http\Web\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Http\Livewire\Shelter\Admin\DeleteAdminModal;
use App\Models\User;
use App\Policies\AdminDashboard\UserPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteAdminModalTest extends TestCase
{
    use AuthenticateAsWebUser;

    public User $selectedUser;

    public function setUp() : void
    {
        parent::setUp();

        $this->selectedUser = User::factory()->assignShelterRole(ShelterRole::admin)->create();
    }

    /** @test */
    public function it_deletes_admin()
    {
        Livewire::test(DeleteAdminModal::class, [$this->selectedUser->id])
            ->call('delete')
            ->assertDispatched('adminDeleted')
            ->assertDispatched('modal.close');

        $dbDeletedAdmin = User::onlyTrashed()->find($this->selectedUser->id);

        $this->assertNotNull($dbDeletedAdmin);
    }

    /** @test */
    public function it_returns_success_response_if_delete_admin_allowed_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldAllow('deleteAdmin', $this->selectedUser);

        Livewire::test(DeleteAdminModal::class, ['userId' => $this->selectedUser->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_admin_denied_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldDeny('deleteAdmin', $this->selectedUser);

        Livewire::test(DeleteAdminModal::class, ['userId' => $this->selectedUser->id])->assertForbidden();
    }
}
