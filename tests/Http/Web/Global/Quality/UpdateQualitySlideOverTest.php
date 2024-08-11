<?php

namespace Tests\Http\Web\Global\Quality;

use App\Http\Livewire\Global\Quality\UpdateQualitySlideOver;
use App\Models\Quality;
use App\Models\Type;
use App\Policies\AdminDashboard\QualityPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class UpdateQualitySlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Quality $quality;

    public string $name;

    public Type $type;

    public function setUp() : void
    {
        parent::setUp();

        $this->quality = Quality::factory()->create();

        $this->name = $this->faker->name();
        $this->type = Type::factory()->create();
    }

    /** @test */
    public function it_updates_quality()
    {
        Livewire::test(UpdateQualitySlideOver::class, [$this->quality->id])
            ->set('quality.name', $this->name)
            ->set('quality.type_id', $this->type->id)
            ->call('update')
            ->assertHasNoErrors()
            ->assertDispatched('qualityUpdated');

        $dbQuality = Quality::first();

        $this->assertNotNull($dbQuality);
        $this->assertSame(strtolower($this->name), $dbQuality->name);
        $this->assertSame($this->type->id, $dbQuality->type->id);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(UpdateQualitySlideOver::class, [$this->quality->id])
            ->set('quality.name', null)
            ->set('quality.type_id', null)
            ->call('update')
            ->assertHasErrors([
                'quality.name',
                'quality.type_id',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_update_quality_allowed_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldAllow('update', $this->quality);

        Livewire::test(UpdateQualitySlideOver::class, [$this->quality->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_update_quality_denied_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldDeny('update', $this->quality);

        Livewire::test(UpdateQualitySlideOver::class, [$this->quality->id])->assertForbidden();
    }
}
