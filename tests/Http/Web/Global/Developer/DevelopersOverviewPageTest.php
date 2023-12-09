<?php

namespace Tests\Http\Web\Global\Developer;

use App\Enums\Role;
use App\Http\Livewire\Global\Developer\DevelopersOverviewPage;
use App\Models\User;
use App\Policies\AdminDashboard\UserPolicy;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DevelopersOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public function setUp() : void
    {
        parent::setUp();
    }

    /** @test */
    public function it_is_accessible_on_developer_route()
    {
        $this->get('/developers')
            ->assertStatus(200)
            ->assertSeeLivewire(DevelopersOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_developers()
    {
        $developers = User::factory()->assignRole(Role::developer)->count(3)->create()->push($this->user);
        $users = User::factory()->count(2)->create();

        Livewire::test(DevelopersOverviewPage::class)
            ->assertViewHas('developers', function ($items) use ($developers) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($developers->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_developers_by_lastname_firstname_and_id()
    {
        $secondDeveloper = tap($this->user)->update(['firstname' => 'a', 'lastname' => 'b']);
        $thirdDeveloper = User::factory()->assignRole(Role::developer)->create(['firstname' => 'b', 'lastname' => 'b']);
        $firstDeveloper = User::factory()->assignRole(Role::developer)->create(['firstname' => 'b', 'lastname' => 'a']);
        $fourthDeveloper = User::factory()->assignRole(Role::developer)->create(['firstname' => 'b', 'lastname' => 'b']);

        Livewire::test(DevelopersOverviewPage::class)
            ->assertViewHas('developers', function ($items) use ($firstDeveloper, $secondDeveloper, $thirdDeveloper, $fourthDeveloper) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertSame($firstDeveloper->id, $items[0]->id);
                $this->assertSame($secondDeveloper->id, $items[1]->id);
                $this->assertSame($thirdDeveloper->id, $items[2]->id);
                $this->assertSame($fourthDeveloper->id, $items[3]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_developer_allowed_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldAllow('viewAnyDeveloper');

        Livewire::test(DevelopersOverviewPage::class)->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_developer_denied_by_policy()
    {
        $this->partialMockPolicy(UserPolicy::class)->forUser($this->user)->shouldDeny('viewAnyDeveloper');

        Livewire::test(DevelopersOverviewPage::class)->assertForbidden();
    }
}
