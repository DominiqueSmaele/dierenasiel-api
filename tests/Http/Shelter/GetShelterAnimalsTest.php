<?php

namespace Tests\Http\Shelter;

use App\Models\Animal;
use App\Models\Shelter;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetShelterAnimalsTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    public Shelter $shelter;

    protected function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();
    }

    /** @test */
    public function it_returns_animals_of_shelter()
    {
        $otherAnimals = Animal::factory()->count(2)->create();
        $animals = Animal::factory()->for($this->shelter)->count(3)->create();

        $this->actingAsOAuthUser()
            ->getJson("/api/shelter/{$this->shelter->id}/animals")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'sex',
                        'birth_date',
                        'race',
                        'description',
                        'type',
                        'shelter',
                    ],
                ],
            ])->assertJsonArray('data.*.id', $animals->pluck('id'));
    }

    /** @test */
    public function it_can_search_by_name_or_race()
    {
        $otherAnimal = Animal::factory()->for($this->shelter)->create();
        $animal = Animal::factory()->for($this->shelter)->create(['name' => 'aFoobar']);
        $secondAnimal = Animal::factory()->for($this->shelter)->create(['race' => 'aFoobar']);

        $this->actingAsOAuthUser()
            ->getJson("/api/shelter/{$this->shelter->id}/animals?q=foo")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $animal->id)
            ->assertJsonPath('data.1.id', $secondAnimal->id);
    }

    /** @test */
    public function it_sorts_shelters_by_name()
    {
        $thirdAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'c']);
        $firstAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'a']);
        $secondAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'b']);

        $this->actingAsOAuthUser()
            ->getJson("/api/shelter/{$this->shelter->id}/animals")
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $firstAnimal->id)
            ->assertJsonPath('data.1.id', $secondAnimal->id)
            ->assertJsonPath('data.2.id', $thirdAnimal->id);
    }

    /** @test */
    public function it_returns_animals_with_cursor_pagination()
    {
        $firstAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'a']);
        $secondAnimal = Animal::factory()->for($this->shelter)->create(['name' => 'b']);

        $response = $this->actingAsOAuthUser()
            ->getJson("/api/shelter/{$this->shelter->id}/animals?per_page=1")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $firstAnimal->id);

        $this->actingAsOAuthUser()
            ->getJson("/api/shelter/{$this->shelter->id}/animals?per_page=1&cursor={$response->json('meta.next_cursor')}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $secondAnimal->id);
    }
}
