<?php

namespace Tests\Http\Web\Shelter\Animal;

use App\Http\Livewire\Shelter\Animal\CreateAnimalSlideOver;
use App\Models\Animal;
use App\Models\Quality;
use App\Models\Shelter;
use App\Models\Type;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\AuthenticateAsWebUser;
use Tests\TestCase;

class CreateAnimalQualityTest extends TestCase
{
    use AuthenticateAsWebUser;

    public Shelter $shelter;

    public UploadedFile $image;

    public string $name;

    public Type $type;

    public string $sex;

    public int $years;

    public int $months;

    public string $race;

    public string $description;

    public Collection $qualities;

    public function setUp() : void
    {
        parent::setUp();

        $this->shelter = Shelter::factory()->create();

        $this->image = UploadedFile::fake()->image('image.png');
        $this->name = $this->faker->name();
        $this->type = Type::factory()->create();
        $this->sex = $this->faker->randomElement(['male', 'female']);
        $this->years = $this->faker->numberBetween(0, 25);
        $this->months = $this->faker->numberBetween(0, 11);
        $this->race = $this->faker->word();
        $this->description = $this->faker->text();

        $this->qualities = Quality::factory()->for($this->type)->count(3)->create();
    }

    /** @test */
    public function it_creates_animal_qualities()
    {
        Livewire::test(CreateAnimalSlideOver::class, [$this->shelter->id])
            ->set('image', $this->image)
            ->set('animal.name', $this->name)
            ->set('animal.type_id', $this->type->id)
            ->set('animal.sex', $this->sex)
            ->set('animal.years', $this->years)
            ->set('animal.months', $this->months)
            ->set('animal.race', $this->race)
            ->set('animal.description', $this->description)
            ->call('create')
            ->assertHasNoErrors()
            ->assertDispatched('animalCreated')
            ->assertDispatched('slide-over.close');

        $dbAnimalQualities = Animal::first()->qualities()->get();

        $this->assertNotNull($dbAnimalQualities);
        $this->assertEqualsArray($this->qualities->pluck('id'), $dbAnimalQualities->pluck('pivot.quality_id'));
    }
}
