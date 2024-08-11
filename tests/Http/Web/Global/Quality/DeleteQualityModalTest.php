<?php

namespace Tests\Http\Web\Global\Quality;

use App\Http\Livewire\Global\Quality\DeleteQualityModal;
use App\Models\Animal;
use App\Models\Quality;
use App\Policies\AdminDashboard\QualityPolicy;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class DeleteQualityModalTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Animal $animal;

    public Quality $quality;

    public function setUp() : void
    {
        parent::setUp();

        $this->animal = Animal::factory()->create();

        $this->quality = Quality::factory()->for($this->animal->type)->create();

        $this->animal->qualities()->syncWithoutDetaching([
            $this->quality->id => [
                'value' => $this->faker->boolean(),
            ],
        ]);
    }

    /** @test */
    public function it_deletes_quality_and_pivot_records()
    {
        Livewire::test(DeleteQualityModal::class, [$this->quality->id])
            ->call('delete')
            ->assertDispatched('qualityDeleted')
            ->assertDispatched('modal.close');

        $this->assertNull(Quality::find($this->quality->id));
        $this->assertCount(0, $this->animal->qualities);
    }

    /** @test */
    public function it_returns_success_response_if_delete_quality_allowed_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldAllow('delete', $this->quality);

        Livewire::test(DeleteQualityModal::class, [$this->quality->id])->assertSuccessful();
    }

    /** @test */
    public function it_returns_unauthorized_response_if_delete_quality_denied_by_policy()
    {
        $this->partialMockPolicy(QualityPolicy::class)->forUser($this->user)->shouldDeny('delete', $this->quality);

        Livewire::test(DeleteQualityModal::class, [$this->quality->id])->assertForbidden();
    }
}
