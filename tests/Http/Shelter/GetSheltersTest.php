<?php

namespace Tests\Http\Shelter;

use App\Models\Shelter;
use App\Models\Values\Point;
use Tests\AuthenticateAsOAuthUser;
use Tests\TestCase;

class GetSheltersTest extends TestCase
{
    use AuthenticateAsOAuthUser;

    /** @test */
    public function it_returns_shelters()
    {
        $shelters = Shelter::factory()->count(4)->create();

        $this->actingAsOAuthUser()
            ->getJson('/api/shelters')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'facebook',
                        'instagram',
                        'tiktok',
                        'address',
                        'image',
                    ],
                ],
            ])->assertJsonArray('data.*.id', $shelters->pluck('id'));
    }

    /** @test */
    public function it_can_search_by_name()
    {
        $otherShelter = Shelter::factory()->create();
        $shelter = Shelter::factory()->create(['name' => 'aFoobar']);

        $this->actingAsOAuthUser()
            ->getJson('/api/shelters?q=foo')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $shelter->id);
    }

    /** @test */
    public function it_sorts_shelters_by_distance_of_provided_coordinates()
    {
        $lat = $this->faker->latitude();
        $long = $this->faker->longitude(-177);

        $thirdShelter = Shelter::factory()->forAddress(['coordinates' => new Point($lat, $long - 3, 4326)])->create();
        $firstShelter = Shelter::factory()->forAddress(['coordinates' => new Point($lat, $long - 1, 4326)])->create();
        $secondShelter = Shelter::factory()->forAddress(['coordinates' => new Point($lat, $long - 2, 4326)])->create();

        $this->actingAsOAuthUser()
            ->getJson("/api/shelters?latitude={$lat}&longitude={$long}")
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $firstShelter->id)
            ->assertJsonPath('data.1.id', $secondShelter->id)
            ->assertJsonPath('data.2.id', $thirdShelter->id);
    }

    /** @test */
    public function it_sorts_shelters_by_name_if_no_coordinates_provided()
    {
        $thirdShelter = Shelter::factory()->create(['name' => 'c']);
        $firstShelter = Shelter::factory()->create(['name' => 'a']);
        $secondShelter = Shelter::factory()->create(['name' => 'b']);

        $this->actingAsOAuthUser()
            ->getJson('/api/shelters')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.id', $firstShelter->id)
            ->assertJsonPath('data.1.id', $secondShelter->id)
            ->assertJsonPath('data.2.id', $thirdShelter->id);
    }

    /** @test */
    public function it_returns_shelters_with_cursor_pagination()
    {
        $firstShelter = Shelter::factory()->create(['name' => 'a']);
        $secondShelter = Shelter::factory()->create(['name' => 'b']);

        $response = $this->actingAsOAuthUser()
            ->getJson('/api/shelters?per_page=1')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $firstShelter->id);

        $this->actingAsOAuthUser()
            ->getJson("/api/shelters?per_page=1&cursor={$response->json('meta.next_cursor')}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $secondShelter->id);
    }

    /** @test */
    public function it_returns_shelters_with_cursor_pagination_ordered_by_distance()
    {
        $lat = $this->faker->latitude(-88);
        $long = $this->faker->longitude();

        $firstShelter = Shelter::factory()->forAddress(['coordinates' => new Point($lat - 1, $long, 4326)])->create();
        $secondShelter = Shelter::factory()->forAddress(['coordinates' => new Point($lat - 2, $long, 4326)])->create();

        $response = $this->actingAsOAuthUser()
            ->getJson("/api/shelters?per_page=1&latitude={$lat}&longitude={$long}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $firstShelter->id);

        $this->actingAsOAuthUser()
            ->getJson("/api/shelters?per_page=1&latitude={$lat}&longitude={$long}&cursor={$response->json('meta.next_cursor')}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $secondShelter->id);
    }
}
