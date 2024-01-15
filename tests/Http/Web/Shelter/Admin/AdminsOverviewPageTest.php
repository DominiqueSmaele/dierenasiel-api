<?php

namespace Tests\Http\Web\Shelter\Admin;

use App\Enums\ShelterRole;
use App\Http\Livewire\Shelter\Admin\AdminsOverviewPage;
use App\Models\Shelter;
use App\Models\User;
use App\Policies\AdminDashboard\UserPolicy;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class AdminsOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_admin_route()
    {
        $this->get("/shelter/{$this->shelter->id}/admins")
            ->assertStatus(200)
            ->assertSeeLivewire(AdminsOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_admins_of_shelter()
    {
        $admins = User::factory()->for($this->shelter)->assignShelterRole(ShelterRole::admin)->count(3)->create();
        $otherAdmins = User::factory()->assignShelterRole(ShelterRole::admin)->count(2)->create();
        $users = User::factory()->count(2)->create();

        Livewire::test(AdminsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('admins', function ($items) use ($admins) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($admins->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_admins_by_lastname_firstname_and_id()
    {
        $secondAdmin = User::factory()->for($this->shelter)->assignShelterRole(ShelterRole::admin)->create(['firstname' => 'a', 'lastname' => 'b']);
        $thirdAdmin = User::factory()->for($this->shelter)->assignShelterRole(ShelterRole::admin)->create(['firstname' => 'b', 'lastname' => 'b']);
        $firstAdmin = User::factory()->for($this->shelter)->assignShelterRole(ShelterRole::admin)->create(['firstname' => 'b', 'lastname' => 'a']);
        $fourthAdmin = User::factory()->for($this->shelter)->assignShelterRole(ShelterRole::admin)->create(['firstname' => 'b', 'lastname' => 'b']);

        Livewire::test(AdminsOverviewPage::class, ['shelter' => $this->shelter])
            ->assertViewHas('admins', function ($items) use ($firstAdmin, $secondAdmin, $thirdAdmin, $fourthAdmin) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertSame($firstAdmin->id, $items[0]->id);
                $this->assertSame($secondAdmin->id, $items[1]->id);
                $this->assertSame($thirdAdmin->id, $items[2]->id);
                $this->assertSame($fourthAdmin->id, $items[3]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_admin_allowed_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldAllow('viewAnyAdmin', $this->shelter);

        Livewire::test(AdminsOverviewPage::class, ['shelter' => $this->shelter])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_admin_denied_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldDeny('viewAnyAdmin', $this->shelter);

        Livewire::test(AdminsOverviewPage::class, ['shelter' => $this->shelter])->assertForbidden();
    }
}
