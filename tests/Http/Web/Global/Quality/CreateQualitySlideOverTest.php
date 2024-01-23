<?php

namespace Tests\Http\Web\Global\Quality;

use App\Http\Livewire\Global\Quality\CreateQualitySlideOver;
use App\Models\Quality;
use App\Models\Type;
use App\Policies\AdminDashboard\QualityPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateQualitySlideOverTest extends TestCase
{
    use AuthenticateAsWebUser;

    public string $name;

    public Type $type;

    public function setUp() : void
    {
        parent::setUp();

        $this->name = $this->faker->name();
        $this->type = Type::factory()->create();
    }

    /** @test */
    public function it_creates_quality()
    {
        Livewire::test(CreateQualitySlideOver::class)
            ->set('quality.name', $this->name)
            ->set('quality.type_id', $this->type->id)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('qualityCreated')
            ->assertDispatched('slide-over.close');

        $dbQuality = Quality::first();

        $this->assertNotNull($dbQuality);
        $this->assertSame($this->name, $dbQuality->name);
        $this->assertSame($this->type->id, $dbQuality->type->id);
    }

    /** @test */
    public function it_throws_validation_error_if_required_data_is_missing()
    {
        Livewire::test(CreateQualitySlideOver::class)
            ->set('quality.name', null)
            ->set('quality.type_id', null)
            ->call('create')
            ->assertHasErrors([
                'quality.name',
                'quality.type_id',
            ]);
    }

    /** @test */
    public function it_returns_success_response_if_create_quality_allowed_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldAllow('create');

        Livewire::test(CreateQualitySlideOver::class)->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_create_quality_denied_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldDeny('create');

        Livewire::test(CreateQualitySlideOver::class)->assertForbidden();
    }
}
