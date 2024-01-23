<?php

namespace Tests\Http\Web\Global\Quality;

use App\Http\Livewire\Global\Quality\QualitiesOverviewPage;
use App\Models\Quality;
use App\Models\Type;
use App\Policies\AdminDashboard\QualityPolicy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class QualitiesOverviewPageTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Type $type;

    public function setUp() : void
    {
        parent::setUp();

        $this->type = Type::factory()->create();
    }

    /** @test */
    public function it_is_accessible_on_qualities_route()
    {
        $this->get('/qualities')
            ->assertStatus(200)
            ->assertSeeLivewire(QualitiesOverviewPage::class);
    }

    /** @test */
    public function it_shows_all_qualities()
    {
        $qualities = Quality::factory()->count(3)->create();

        Livewire::test(QualitiesOverviewPage::class)
            ->assertViewHas('qualities', function ($items) use ($qualities) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($qualities->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_shows_qualities_filtered_by_type()
    {
        $qualities = Quality::factory()->for($this->type)->count(3)->create();
        $otherQualities = Quality::factory()->count(2)->create();

        Livewire::test(QualitiesOverviewPage::class)
            ->set('filterValue', $this->type->id)
            ->assertViewHas('qualities', function ($items) use ($qualities) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertEqualsArray($qualities->pluck('id'), $items->pluck('id'));

                return true;
            });
    }

    /** @test */
    public function it_sorts_all_qualities_by_type_name_and_id()
    {
        $fourthQuality = Quality::factory()->create();
        $secondQuality = Quality::factory()->for($this->type)->create(['name' => 'b']);
        $firstQuality = Quality::factory()->for($this->type)->create(['name' => 'a']);
        $thirdQuality = Quality::factory()->for($this->type)->create(['name' => 'b']);

        Livewire::test(QualitiesOverviewPage::class)
            ->assertViewHas('qualities', function ($items) use ($firstQuality, $secondQuality, $thirdQuality, $fourthQuality) {
                $this->assertInstanceOf(LengthAwarePaginator::class, $items);
                $this->assertSame($firstQuality->id, $items[0]->id);
                $this->assertSame($secondQuality->id, $items[1]->id);
                $this->assertSame($thirdQuality->id, $items[2]->id);
                $this->assertSame($fourthQuality->id, $items[3]->id);

                return true;
            });
    }

    /** @test */
    public function it_returns_success_response_if_view_any_quality_allowed_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldAllow('viewAny');

        Livewire::test(QualitiesOverviewPage::class)->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_view_any_quality_denied_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldDeny('viewAny');

        Livewire::test(QualitiesOverviewPage::class)->assertForbidden();
    }
}
