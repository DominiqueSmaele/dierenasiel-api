<?php

namespace Tests\Http\Animal;

use App\Models\Animal;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetAnimalsTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_animals()
    {
        $animals = Animal::factory()->count(5)->create();

        $this->actingAsOAuthUser()
            ->getJson('/api/animals')
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
        $otherAnimal = Animal::factory()->create();
        $animal = Animal::factory()->create(['name' => 'aFoobar']);
        $secondAnimal = Animal::factory()->create(['race' => 'aFoobar']);

        $this->actingAsOAuthUser()
            ->getJson('/api/animals?q=foo')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $animal->id)
            ->assertJsonPath('data.1.id', $secondAnimal->id);
    }

    /** @test */
    public function it_sorts_shelters_by_name()
    {
        $thirdAnimal = Animal::factory()->create(['name' => 'c']);
        $firstAnimal = Animal::factory()->create(['name' => 'a']);
        $secondAnimal = Animal::factory()->create(['name' => 'b']);

        $this->actingAsOAuthUser()
            ->getJson('/api/animals')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $firstAnimal->id)
            ->assertJsonPath('data.1.id', $secondAnimal->id)
            ->assertJsonPath('data.2.id', $thirdAnimal->id);
    }

    /** @test */
    public function it_returns_animals_with_cursor_pagination()
    {
        $firstAnimal = Animal::factory()->create(['name' => 'a']);
        $secondAnimal = Animal::factory()->create(['name' => 'b']);

        $response = $this->actingAsOAuthUser()
            ->getJson('/api/animals?per_page=1')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $firstAnimal->id);

        $this->actingAsOAuthUser()
            ->getJson("/api/animals?per_page=1&cursor={$response->json('meta.next_cursor')}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $secondAnimal->id);
    }
}
